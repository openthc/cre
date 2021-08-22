<?php
/**
 * Create a B2B Sale owned by the Origin License
 * Create a Global B2B Sale visible to Target License
 */

namespace App\Controller\B2B;

class Create extends \App\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		$dbc = $this->_container->DB;

		$oid = _ulid();

		// B2B Object
		$obj = array(
			'id' => $oid,
			// 'license_target' => array(),
			// 'tranfer_item_list' => array(),
		);

		// Source License
		$sql = 'SELECT id, name FROM license WHERE id = :g';
		$arg = array(':g' => $_ENV['license_id']);
		$L_Source = $dbc->fetchRow($sql, $arg);

		// Build B2B Here
		$sql = 'SELECT id, name FROM license WHERE id = :g';
		$arg = array(':g' => $_POST['license_id_target']);
		$L_Target = $dbc->fetchRow($sql, $arg);
		if (empty($L_Target['id'])) {
			return $RES->withJSON(array(
				'data' => null,
				'meta' => [ 'detail' => 'Invalid Target License [CBC-036]' ],
			), 400);
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

		$this->logAudit('B2B/Create', $oid, $obj);

		$this->_container->DB->query('BEGIN');
		$this->_container->DB->insert('b2b_incoming', $rec_incoming);
		$this->_container->DB->insert('b2b_outgoing', $rec_outgoing);
		$this->_container->DB->query('COMMIT');

		unset($rec_incoming['meta']);

		return $RES->withJSON(array(
			'data' => $rec_incoming,
			'meta' => [],
		), 201);

	}
}
