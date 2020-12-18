<?php
/**
 * B2B Update
 */

namespace App\Controller\B2B;

class Update extends \App\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		$dbc = $this->_container->DB;

		// Find in View
		$sql = 'SELECT * FROM b2b_outgoing WHERE license_id_source = :l AND id = :g';
		$arg = array(
			':l' => $_ENV['license_id'],
			':g' => $ARG['id']
		);
		$T = $dbc->fetchRow($sql, $arg);
		if (empty($T['id'])) {
			return $this->send404('B2B Sale Not Found [CTU#022]');
		}

		if ($_ENV['license_id'] == $T['license_id_source']) {
			return $this->_update_from_source($RES, $T);
		// } elseif ($_ENV['license_id'] == $T['license_id_target']) {
		// 	return $this->_update_from_target($RES, $T);
		}

		return $this->sendError('Request Failed [CTU#030]');
	}

	/*
	 *
	 */
	function _update_from_source($RES, $T)
	{
		$dbc = $this->_container->DB;

		// Add an Item
		if (!empty($_POST['lot_id'])) {

			$obj_i = [
				'id' => _ulid(),
				'b2b_incoming_id' => $T['id'],
				'stat' => 200,
				'name' => '-',
				'hash' => '-',
			];
			$dbc->insert('b2b_incoming_item', $obj_i);

			$obj_o = [
				'id' => $obj_i['id'],
				'b2b_outgoing_id' => $T['id'],
				'lot_id' => $_POST['lot_id'],
				'stat' => 200,
				'qty' => $_POST['qty'],
				'name' => '-',
				'hash' => '-',
			];
			$dbc->insert('b2b_outgoing_item', $obj_o);

			return $RES->withJSON([
				'meta' => [],
				'data' => $obj_o,
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
					return $this->sendError('B2B Sale has no items [CTU#050]');
				}

				// Allowed
				$dbc->query('BEGIN');

				$sql = 'UPDATE b2b_outgoing SET stat = 307 WHERE id = ?';
				$arg = array($T['id']);
				$dbc->query($sql, $arg);
				$T['stat'] = 307;
				$T['transfer_outgoing_stat'] = 307;

				// Decrement Lots
				// $sql = 'UPDATE lot SET qty = qty - (SELECT qty FROM transfer_outgoing_item WHERE b2b_outgoing_id = ? AND lot_id = lot.id)';
				// $arg = array($T['id']);

				$chk1 = $dbc->query($sql, $arg);

				if ($chk0 != $chk1) {
					throw new \Exception("Update Error '$chk0' != '$chk1' [CTU#062]");
				}

				$dbc->query('COMMIT');

				return $RES->withJSON([
					'meta' => [],
					'data' => $T,
				], 202);

				break;
			}
		}

		// Bad Request
		return $this->sendError('Bad Request [CTU#104]', 400);

	}

}
