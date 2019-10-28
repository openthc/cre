<?php
/**
 * Delete a Lot Object
 */

namespace App\Controller\Lot;

class Delete extends \App\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		$dbc = $this->_container->DB;

		$sql = 'SELECT id, stat FROM lot WHERE license_id = :l0 AND id = :pk FOR UPDATE NOWAIT';
		$arg = array(
			':l0' => $_ENV['license_id'],
			':pk' => $ARG['id']
		);

		// Find and Update
		$cur = $dbc->fetchRow($sql, $arg);
		if (empty($cur['id'])) {
			return $this->send404('Lot not found [CLD#023]');
		}

		$this->logAudit('Lot/Delete', $ARG['id'], null);

		// Status State Machine
		switch ($cur['stat']) {
		case 200:
			$ret_code = 202;
			$ret_text = 'Lot Delete Requested';
			$chk = $dbc->query('UPDATE lot SET stat = 410 WHERE license_id = :l0 AND id = :pk', $arg);
			$this->logAudit('Lot/Delete/Create', $ARG['id'], null);
			break;
		case 410:
			$ret_code = 410;
			$ret_text = 'Lot Delete Confirmed';
			$chk = $dbc->query('UPDATE lot SET stat = 423 WHERE license_id = :l0 AND id = :pk', $arg);
			$this->logAudit('Lot/Delete/Commit', $ARG['id'], null);
			break;
		case 423:
			$ret_code = 423;
			$ret_text = 'Lot Locked';
			break;
		default:
			// Failure
			throw new \Exception('Invalid Lot Status [CLD#039]');
		}

		$dbc->query('COMMIT');

		return $RES->withJSON(array(
			'meta' => [ 'detail' => $ret_text ],
			'data' => [],
		), $ret_code);

	}

}
