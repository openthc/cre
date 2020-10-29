<?php
/**
 * Mark a Strain as Deleted
 */

namespace App\Controller\Strain;

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
		$cur = $dbc->fetchRow('SELECT id, stat FROM strain WHERE license_id = :l AND id = :g FOR UPDATE NOWAIT', $arg);
		if (empty($cur['id'])) {
			return $this->send404('Strain not Found [CSD#026]');
		}

		switch ($cur['stat']) {
		case 200:
			$ret_code = 202;
			$ret_text = 'Strain Delete Requested';
			$chk = $dbc->query('UPDATE strain SET stat = 410 WHERE license_id = :l AND id = :g', $arg);
			$this->logAudit('Strain/Delete/Create', $ARG['id'], null);
			break;
		case 410:
			$ret_code = 410;
			$ret_text = 'Strain Delete Confirmed';
			$chk = $dbc->query('UPDATE strain SET stat = 423 WHERE license_id = :l AND id = :g', $arg);
			$this->logAudit('Strain/Delete/Commit', $ARG['id'], null);
			break;
		case 423:
			$ret_code = 423;
			$ret_text = 'Strain Locked';
			break;
		default:
			// Failure
			throw new \Exception('Invalid Strain Status [ZCD#039]');
		}

		$dbc->query('COMMIT');

		return $RES->withJSON(array(
			'meta' => [ 'detail' => $ret_text ],
			'data' => [],
		), $ret_code);

	}
}
