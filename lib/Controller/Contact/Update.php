<?php
/**
 * Update Contact
 */

namespace OpenTHC\CRE\Controller\Contact;

class Update extends \OpenTHC\CRE\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{

		$sql = 'SELECT id, meta FROM contact WHERE id = :g';
		$arg = array(
			':g' => $ARG['id'],
		);

		$chk = $this->_container->DB->fetchRow($sql, $arg);
		if (empty($chk)) {
			return $this->send404('Contact not found [CCU-020]');
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
		$sql = 'UPDATE contact SET hash = :h, meta = :m WHERE id = :g';
		$arg = array(
			':g' => $ARG['id'],
			':h' => sha1($meta),
			':m' => $meta
		);
		$this->_container->DB->query($sql, $arg);

		// Log the change
		$this->logAudit('Contact/Update', $chk['id'], json_encode(array(
			'old' => $obj0,
			'new' => $obj1
		)));

		$this->_container->DB->query('COMMIT');

		return $RES->withJSON(array(
			'meta' => [],
			'data' => $obj1
		));

	}
}
