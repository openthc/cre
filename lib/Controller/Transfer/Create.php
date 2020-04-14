<?php
/**
 * Create a Transfer owned by the Origin License
 * Create a Global Transfer visible to Target License
 */

namespace App\Controller\Transfer;

class Create extends \App\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		$dbc = $this->_container->DB;

		$oid = _ulid();

		// Transfer Object
		$obj = array(
			'id' => $oid,
			// 'license_target' => array(),
			// 'tranfer_item_list' => array(),
		);

		// Source License
		$sql = 'SELECT id, name FROM license WHERE id = :g';
		$arg = array(':g' => $_ENV['license_id']);
		$L_Source = $dbc->fetchRow($sql, $arg);

		// Build Transfer Here
		$sql = 'SELECT id, name FROM license WHERE id = :g';
		$arg = array(':g' => $_POST['license_id_target']);
		$L_Target = $dbc->fetchRow($sql, $arg);
		if (empty($L_Target['name'])) {
			_exit_text('Fatal', 500);
		}

		$obj = json_encode($obj);

		$rec_incoming = array(
			'id' => $oid,
			'license_id_source' => $L_Source['id'],
			'license_id_target' => $L_Target['id'],
			'name' => sprintf('From: ' . $L_Source['name']),
			'hash' => sha1($obj),
			'meta' => $obj,
		);

		$rec_outgoing = $rec_incoming;
		$rec_outgoing['name'] = sprintf('Ship To: ' . $L_Target['name']);

		$this->logAudit('Transfer/Create', $oid, $obj);

		$this->_container->DB->query('BEGIN');
		$this->_container->DB->insert('b2b_incoming', $rec_incoming);
		$this->_container->DB->insert('b2b_outgoing', $rec_outgoing);
		$this->_container->DB->query('COMMIT');

		unset($rec_incoming['meta']);

		return $RES->withJSON(array(
			'meta' => [],
			'data' => $rec_incoming
		), 201);

	}
}
