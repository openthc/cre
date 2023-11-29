<?php
/**
 * Mark a Variety as Deleted
 */

namespace OpenTHC\CRE\Controller\Variety;

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
		$cur = $dbc->fetchRow('SELECT id, stat FROM variety WHERE license_id = :l AND id = :g FOR UPDATE NOWAIT', $arg);
		if (empty($cur['id'])) {
			return $this->send404('Variety not Found [CSD-026]');
		}

		switch ($cur['stat']) {
		case 200:
			$ret_code = 202;
			$ret_text = 'Variety Delete Requested';
			$chk = $dbc->query('UPDATE variety SET stat = 410 WHERE license_id = :l AND id = :g', $arg);
			$this->logAudit('Variety/Delete/Create', $ARG['id'], null);
			break;
		case 410:
			$ret_code = 410;
			$ret_text = 'Variety Delete Confirmed';
			$chk = $dbc->query('UPDATE variety SET stat = 423 WHERE license_id = :l AND id = :g', $arg);
			$this->logAudit('Variety/Delete/Commit', $ARG['id'], null);
			break;
		case 423:
			$ret_code = 423;
			$ret_text = 'Variety Locked';
			break;
		default:
			// Failure
			throw new \Exception('Invalid Variety Status [ZCD-039]');
		}

		$dbc->query('COMMIT');

		return $RES->withJSON(array(
			'meta' => [ 'note' => $ret_text ],
			'data' => [],
		), $ret_code);

	}
}
