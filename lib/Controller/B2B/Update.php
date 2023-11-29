<?php
/**
 * B2B Update
 */

namespace OpenTHC\CRE\Controller\B2B;

class Update extends \OpenTHC\CRE\Controller\Base
{
	/**
	 *
	 */
	function __invoke($REQ, $RES, $ARG)
	{
		$dbc = $this->_container->DB;

		// Find in View
		$sql = 'SELECT * FROM b2b_outgoing WHERE license_id_source = :l AND id = :g';
		$arg = array(
			':l' => $_SESSION['License']['id'],
			':g' => $ARG['id']
		);
		$T = $dbc->fetchRow($sql, $arg);
		if (empty($T['id'])) {
			return $this->send404('B2B Sale not found [CTU-022]');
		}

		if ($_SESSION['License']['id'] == $T['license_id_source']) {
			return $this->_update_from_source($RES, $T);
		// } elseif ($_SESSION['License']['id'] == $T['license_id_target']) {
		// 	return $this->_update_from_target($RES, $T);
		}

		return $this->sendError('Request Failed [CTU-030]');
	}

	/*
	 *
	 */
	function _update_from_source($RES, $T)
	{
		$dbc = $this->_container->DB;

		// Add an Item
		if (!empty($_POST['inventory_id'])) {

			$obj_o = [
				'id' => _ulid(),
				'b2b_outgoing_id' => $T['id'],
				'inventory_id' => $_POST['inventory_id'],
				'stat' => 200,
				'qty' => $_POST['qty'],
				'name' => '-',
				'hash' => '-',
			];
			$dbc->insert('b2b_outgoing_item', $obj_o);

			$obj_i = [
				'id' => $obj_o['id'],
				'b2b_incoming_id' => $T['id'],
				'stat' => 200,
				'qty' => $_POST['qty'],
				'name' => '-',
				'hash' => '-',
			];
			$dbc->insert('b2b_incoming_item', $obj_i);

			return $RES->withJSON([
				'data' => $obj_o,
				'meta' => [],
			], 201);

		}

		// Update the Status
		if (!empty($_POST['status'])) {
			switch ($_POST['status']) {
			case 'commit':

				$sql = 'SELECT count(id) FROM b2b_outgoing_item WHERE b2b_outgoing_id = ?';
				$arg = array($T['id']);
				$chk0 = $dbc->fetchOne($sql, $arg);
				if (empty($chk0)) {
					return $this->sendError('B2B Sale has no items [CTU-050]');
				}

				// Allowed
				$dbc->query('BEGIN');

				$sql = 'UPDATE b2b_outgoing SET stat = 307 WHERE id = ?';
				$arg = array($T['id']);
				$dbc->query($sql, $arg);
				$T['stat'] = 307;
				$T['transfer_outgoing_stat'] = 307;

				// Decrement Inventory
				// $sql = 'UPDATE inventory SET qty = qty - (SELECT qty FROM transfer_outgoing_item WHERE b2b_outgoing_id = ? AND inventory_id = inventory.id)';
				// $arg = array($T['id']);

				$chk1 = $dbc->query($sql, $arg);

				if ($chk0 != $chk1) {
					throw new \Exception("Update Error '$chk0' != '$chk1' [CTU-062]");
				}

				$dbc->query('COMMIT');

				return $RES->withJSON([
					'data' => $T,
					'meta' => [],
				], 202);

				break;
			}
		}

		// Bad Request
		return $this->sendError('Bad Request [CTU-104]', 400);

	}

}
