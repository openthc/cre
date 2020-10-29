<?php
/**
 * Create a Strain owned by a License
 */

namespace App\Controller\Strain;

class Create extends \App\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		$oid = \Edoceo\Radix\ULID::generate();

		// Strain Object
		$obj = array(
			'id' => $oid,
			'name' => $_POST['name'],
			//'type' => $_POST['type'],
		);

		// Check Strain Record
		$sql = 'SELECT id FROM strain WHERE license_id = :l AND name = :n';
		$arg = array(
			':l' => $_ENV['license_id'],
			':n' => $_POST['name'],
		);
		$chk = $this->_container->DB->fetchRow($sql, $arg);
		if (!empty($chk)) {
			return $RES->withJSON([
				'meta' => [ 'detail' => 'Strain Duplicate [CSC#030]' ],
				'data' => $chk,
			], 409);
		}

		// Strain Record
		$rec = array(
			'license_id' => $_ENV['license_id'],
			'id' => $oid,
			'hash' => null,
			'name' => $obj['name'],
			'meta' => json_encode($obj),
		);
		$rec['hash'] = sha1($rec['meta']);

		$this->_container->DB->query('BEGIN');
		$this->_container->DB->insert('strain', $rec);
		$this->logAudit('Strain/Create', $oid, $rec['meta']);
		$this->_container->DB->query('COMMIT');

		return $RES->withJSON([
			'meta' => [],
			'data' => $obj,
		], 201);

	}
}
