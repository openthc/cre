<?php
/**
 * Update Company
 */

namespace App\Controller\Company;

class Update extends \App\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		$dbc = $this->_container->DB;

		$sql = 'SELECT id, meta FROM company WHERE id = :g';
		$arg = array(
			':g' => $ARG['id'],
		);

		$chk = $dbc->fetchRow($sql, $arg);
		if (empty($chk)) {
			return $this->send404('Company not found [CCU#021]');
		}

		// Old Object
		$meta = json_decode($chk['meta'], true);
		if (empty($meta)) {
			$meta = array();
		}
		$meta = array_merge($meta, $_POST);

		// // New Object
		// $obj1 = array(
		// 	'name' => $_POST['name'],
		// 	'type' => $_POST['type'],
		// );

		$meta = json_encode($meta);

		$dbc->query('BEGIN');

		// Update the record
		$sql = 'UPDATE company SET name = :n1, hash = :h1,  meta = :m WHERE id = :g';
		$arg = array(
			':g' => $chk['id'],
			':n1' => trim($_POST['name']),
			//':t1' => $obj1['type'],
			':h1' => sha1($meta),
			':m' => $meta
		);
		$dbc->query($sql, $arg);

		// Log the change
		$this->logAudit('Company/Update', $chk['id'], $_POST);

		$dbc->query('COMMIT');

		return $RES->withJSON([
			'meta' => [],
			'data' => $chk,
		]);

	}
}
