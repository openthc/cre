<?php
/**
 * Update Product
 */

namespace OpenTHC\CRE\Controller\Product;

class Update extends \OpenTHC\CRE\Controller\Base
{
	use \OpenTHC\Traits\JSONValidator;

	function __invoke($REQ, $RES, $ARG)
	{

		$sql = 'SELECT id, meta FROM product WHERE license_id = :l AND id = :g';
		$arg = array(
			':l' => $_SESSION['License']['id'],
			':g' => $ARG['id'],
		);

		$P0 = $this->dic->get('DB')->fetch_row($sql, $arg);
		if (empty($chk)) {
			return $this->send404('Product not found [CPU-033]');
		}

		// Old Object
		$obj0 = json_decode($P0['meta'], true);

		// New Object
		$obj1 = array(
			'name' => $_POST['name'],
			'type' => $_POST['type'],
		);

		$meta = json_encode($obj1);


		$this->dic->get('DB')->query('BEGIN');

		// Update the record
		$sql = 'UPDATE product SET hash = :h, meta = :m WHERE id = :g';
		$arg = array(
			':g' => $ARG['id'],
			':h' => sha1($meta),
			':m' => $meta
		);
		$this->dic->get('DB')->query($sql, $arg);

		// Log the change
		$this->logAudit('Product/Update', $ARG['id'], $_POST);

		$this->dic->get('DB')->query('COMMIT');

		return $RES->withJSON(array(
			'status' => 'success',
			'result' => $obj1
		));

	}
}
