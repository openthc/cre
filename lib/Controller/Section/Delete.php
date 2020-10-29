<?php
/**
 * Delete a Section
 */

namespace App\Controller\Section;

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
		$cur = $dbc->fetchRow('SELECT id, stat FROM section WHERE license_id = :l AND id = :g FOR UPDATE NOWAIT', $arg);
		if (empty($cur['id'])) {
			return $this->send404('Section not found [CSZ#027]');
		}

		switch ($cur['stat']) {
		case 200:
			$ret_code = 202;
			$ret_text = 'Section Delete Requested';
			$chk = $dbc->query('UPDATE section SET stat = 410 WHERE license_id = :l AND id = :g', $arg);
			$this->logAudit('Section/Delete/Create', $ARG['id'], null);
			break;
		case 410:
			$ret_code = 410;
			$ret_text = 'Section Delete Confirmed';
			$chk = $dbc->query('UPDATE section SET stat = 423 WHERE license_id = :l AND id = :g', $arg);
			$this->logAudit('Section/Delete/Commit', $ARG['id'], null);
			break;
		case 423:
			$ret_code = 423;
			$ret_text = 'Section Locked';
			break;
		default:
			// Failure
			throw new \Exception('Invalid Section Status [ZCD#039]');
		}

		$dbc->query('COMMIT');

		return $RES->withJSON(array(
			'data' => [],
			'meta' => [ 'detail' => $ret_text ],
		), $ret_code);

	}
}
