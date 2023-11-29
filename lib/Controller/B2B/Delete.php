<?php
/**
 * B2B Deleted
 */

namespace OpenTHC\CRE\Controller\B2B;

class Delete extends \OpenTHC\CRE\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		$dbc = $this->_container->DB;

		$arg = array(
			':l' => $_SESSION['License']['id'],
			':g' => $ARG['id']
		);

		$dbc->query('BEGIN');

		$ret_code = 500;
		$ret_text = 'Server Error';

		// Find and Update
		$cur = $dbc->fetchRow('SELECT id, stat FROM b2b_outgoing WHERE license_id_source = :l AND id = :g FOR UPDATE NOWAIT', $arg);
		if (empty($cur['id'])) {
			return $this->send404('B2B Sale not found [CTD-027]');
		}

		switch ($cur['stat']) {
		case 200:
			$ret_code = 202;
			$ret_text = 'B2B Delete Requested';
			$chk = $dbc->query('UPDATE b2b_outgoing SET stat = 410 WHERE license_id_source = :l AND id = :g', $arg);
			$chk = $dbc->query('UPDATE b2b_incoming SET stat = 410 WHERE license_id_source = :l AND id = :g', $arg);
			$this->logAudit('B2B/Delete/Create', $ARG['id'], null);
			break;
		case 410:
			$ret_code = 410;
			$ret_text = 'B2B Delete Confirmed';
			$chk = $dbc->query('UPDATE b2b_outgoing SET stat = 423 WHERE license_id_source = :l AND id = :g', $arg);
			$chk = $dbc->query('UPDATE b2b_incoming SET stat = 410 WHERE license_id_source = :l AND id = :g', $arg);
			$this->logAudit('B2B/Delete/Commit', $ARG['id'], null);
			break;
		case 423:
			$ret_code = 423;
			$ret_text = 'B2B Locked';
			break;
		default:
			// Failure
			throw new \Exception('Invalid B2B Sale status [CTD-039]');
		}

		$dbc->query('COMMIT');

		return $RES->withJSON(array(
			'data' => [],
			'meta' => [ 'note' => $ret_text ],
		), $ret_code);

	}
}
