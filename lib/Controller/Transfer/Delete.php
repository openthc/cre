<?php
/**
 * Mark a Transfer as Deleted
 */

namespace App\Controller\Transfer;

class Delete extends \App\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		$dbc = $this->_container->DB;

		$arg = array(
			':l' => $_ENV['license_id'],
			':g' => $ARG['id']
		);

		$dbc->query('BEGIN');

		$ret_code = 500;
		$ret_text = 'Server Error';

		// Find and Update
		$cur = $dbc->fetchRow('SELECT id, stat FROM b2b_outgoing WHERE license_id_source = :l AND id = :g FOR UPDATE NOWAIT', $arg);
		if (empty($cur['id'])) {
			return $this->send404('Transfer not Found [CTD#027]');
		}

		switch ($cur['stat']) {
		case 200:
			$ret_code = 202;
			$ret_text = 'Transfer Delete Requested';
			$chk = $dbc->query('UPDATE b2b_outgoing SET stat = 410 WHERE license_id_source = :l AND id = :g', $arg);
			$chk = $dbc->query('UPDATE b2b_incoming SET stat = 410 WHERE license_id_source = :l AND id = :g', $arg);
			$this->logAudit('Transfer/Delete/Create', $ARG['id'], null);
			break;
		case 410:
			$ret_code = 410;
			$ret_text = 'Transfer Delete Confirmed';
			$chk = $dbc->query('UPDATE b2b_outgoing SET stat = 423 WHERE license_id_source = :l AND id = :g', $arg);
			$chk = $dbc->query('UPDATE b2b_incoming SET stat = 410 WHERE license_id_source = :l AND id = :g', $arg);
			$this->logAudit('Transfer/Delete/Commit', $ARG['id'], null);
			break;
		case 423:
			$ret_code = 423;
			$ret_text = 'Transfer Locked';
			break;
		default:
			// Failure
			throw new \Exception('Invalid Transfer Status [CTD#039]');
		}

		$dbc->query('COMMIT');

		return $RES->withJSON(array(
			'meta' => [ 'detail' => $ret_text ],
			'data' => [],
		), $ret_code);

	}
}
