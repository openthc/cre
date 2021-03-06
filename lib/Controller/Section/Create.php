<?php
/**
 * Create a Section owned by a License
 */

namespace App\Controller\Section;

class Create extends \App\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		$_POST['name'] = trim($_POST['name']);
		$_POST['type'] = trim($_POST['type']);

		if (empty($_POST['name'])) {
			return $RES->withJSON([
				'data' => [],
				'meta' => [
					'failure' => 'Invalid Section Name [CZC#019]',
				]
			], 400);
		}

		if (empty($_POST['type'])) {
			$_POST['type'] = 'section';
		}


		$oid = $_POST['id'];
		if (empty($oid)) {
			$oid = _ulid();
		}

		// Section Object
		$obj = array(
			'id' => $oid,
			'name' => $_POST['name'],
			'type' => $_POST['type'],
		);

		// Section Record
		$rec = array(
			'license_id' => $_ENV['license_id'],
			'id' => $oid,
			'hash' => null,
			'name' => $obj['name'],
			'meta' => json_encode($obj),
		);
		$rec['hash'] = sha1($rec['meta']);

		$this->_container->DB->query('BEGIN');
		$this->_container->DB->insert('section', $rec);
		$this->logAudit('Section/Create', $rec['id'], $rec['meta']);
		$this->_container->DB->query('COMMIT');

		return $RES->withJSON(array(
			'data' => $obj,
			'meta' => [
				'detail' => 'Section Created',
			]
		), 201);

	}
}
