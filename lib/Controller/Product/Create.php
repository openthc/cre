<?php
/**
 * Create a Product owned by a License
 */

namespace OpenTHC\CRE\Controller\Product;

class Create extends \OpenTHC\CRE\Controller\Base
{
	use \OpenTHC\Traits\JSONValidator;

	function __invoke($REQ, $RES, $ARG)
	{
		$source_data = $_POST;
		$source_data = \Opis\JsonSchema\Helper::toJSON($source_data);
		if (empty($source_data->id)) {
			$source_data->id = \Edoceo\Radix\ULID::generate();
		}

		$schema_spec = \OpenTHC\CRE\Product::getJSONSchema();
		$this->validateJSON($source_data, $schema_spec);

		$oid = _ulid();

		// Product Object
		$obj = [
			'id' => $oid,
			'name' => trim($_POST['name']),
			'product_type_id' => trim($_POST['type']),
		];

		if (empty($obj['name'])) {
			return $RES->withJSON([
				'meta' => [ 'note' => 'Invalid Product Name [CPC-023]' ]
			], 400);
		}

		if (empty($obj['product_type_id'])) {
			return $RES->withJSON([
				'meta' => [ 'note' => 'Invalid Product Type [CPC-029]' ],
			], 400);
		}

		// Check Product Record, Return Duplicate?
		$sql = 'SELECT id FROM product WHERE license_id = :l AND name = :n';
		$arg = [
			':l' => $_SESSION['License']['id'],
			':n' => $_POST['name'],
		];
		$chk = $this->_container->DB->fetchRow($sql, $arg);
		if (!empty($chk)) {
			return $RES->withJSON([
				'data' => $chk,
				'meta' => [
					'note' => 'Conflict/Duplicate',
				]
			], 409);
		}

		// Product Record
		$rec = [
			'id' => $oid,
			'license_id' => $_SESSION['License']['id'],
			'product_type_id' => $_POST['type'],
			'stat' => 200,
			'hash' => null,
			'name' => $obj['name'],
			'meta' => json_encode($obj),
		];
		$rec['hash'] = sha1($rec['meta']);

		$this->_container->DB->query('BEGIN');
		$this->_container->DB->insert('product', $rec);
		$this->logAudit('Product/Create', $oid, $rec['meta']);
		$this->_container->DB->query('COMMIT');

		return $RES->withJSON([
			'data' => $obj,
			'meta' => [
				'note' => 'Product Created',
			],
		], 201);

	}
}
