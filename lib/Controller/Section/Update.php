<?php
/**
 * Update Section
 */

namespace OpenTHC\CRE\Controller\Section;

class Update extends \OpenTHC\CRE\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{

		$sql = 'SELECT id, meta FROM section WHERE license_id = :l AND id = :g';
		$arg = array(
			':l' => $_ENV['license_id'],
			':g' => $ARG['id'],
		);

		$chk = $this->_container->DB->fetch_row($sql, $arg);
		if (empty($chk)) {
			// Insert?
			$chk = [
				'id' => $ARG['id'],
				'license_id' => $_ENV['license_id'],
				'name' => $ARG['id'],
				'hash' => '-',
			];
			$this->_container->DB->insert('section', $chk);
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
		$sql = 'UPDATE section SET hash = :h, meta = :m WHERE id = :pk';
		$arg = array(
			':pk' => $ARG['id'],
			':h' => sha1($meta),
			':m' => $meta
		);
		$this->_container->DB->query($sql, $arg);

		// Log the change
		$this->logAudit('Section/Update', $ARG['id'], json_encode(array(
			'old' => $obj0,
			'new' => $obj1
		)));

		$this->_container->DB->query('COMMIT');

		return $RES->withJSON(array(
			'data' => $obj1,
			'meta' => [ 'note' => 'Section Updated' ],
		));

	}

}
