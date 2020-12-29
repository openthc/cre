<?php
/**
 * Update License
 */

namespace App\Controller\License;

class Update extends \App\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{

		$sql = 'SELECT id, meta FROM license WHERE id = :g';
		$arg = array(
			':g' => $ARG['id'],
		);

		$chk = $this->_container->DB->fetch_row($sql, $arg);
		if (empty($chk)) {
			return $this->send404('License not found [CLU#020]');
		}

		// Old Object
		$obj0 = json_decode($chk['meta'], true);

		// New Object
		$obj1 = array(
			'name' => $_POST['name'],
			'type' => $_POST['type'],
		);

		$meta = json_encode($obj1);


		$this->_container->DB->query('BEGIN');

		// Update the record
		$sql = 'UPDATE license SET hash = :h, meta = :m WHERE id = :g';
		$arg = array(
			':g' => $ARG['id'],
			':h' => sha1($meta),
			':m' => $meta
		);
		$this->_container->DB->query($sql, $arg);

		// Log the change
		$this->logAudit('License/Update', $ARG['id'], $meta);

		$this->_container->DB->query('COMMIT');

		return $RES->withJSON([
			'meta' => [],
			'data' => $obj1,
		]);

	}
}
