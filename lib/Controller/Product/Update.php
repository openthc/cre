<?php
/**
 * Update Product
 */

namespace App\Controller\Product;

class Update extends \App\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{

		$sql = 'SELECT id, meta FROM product WHERE license_id = :l AND id = :g';
		$arg = array(
			':l' => $_ENV['license_id'],
			':g' => $ARG['id'],
		);

		$P0 = $this->_container->DB->fetch_row($sql, $arg);
		if (empty($chk)) {

			$auto_create = true;
			if ($auto_create) {
				$P0 = array(
					'id' => \Edoceo\Radix\ULID::generate(),
					'license_id' => $_SESSION['license_id'],
					'product_type_id' => '',
					'name' => $_POST['name'],
					'hash' => '-',
					'meta' => json_encode($_POST),
				);
				$this->_container->DB->insert('product', $P0);
			} else {
				return $this->send404('Product not found [CPU#033]');
			}

		}

		// Old Object
		$obj0 = json_decode($P0['meta'], true);

		// New Object
		$obj1 = array(
			'name' => $_POST['name'],
			'type' => $_POST['type'],
		);

		$meta = json_encode($obj1);


		$this->_container->DB->query('BEGIN');

		// Update the record
		$sql = 'UPDATE product SET hash = :h, meta = :m WHERE id = :g';
		$arg = array(
			':g' => $ARG['id'],
			':h' => sha1($meta),
			':m' => $meta
		);
		$this->_container->DB->query($sql, $arg);

		// Log the change
		$this->logAudit('Product/Update', $ARG['id'], $_POST);

		$this->_container->DB->query('COMMIT');

		return $RES->withJSON(array(
			'status' => 'success',
			'result' => $obj1
		));

	}
}
