<?php
/**
 * Delete a B2C Record
 */

namespace App\Controller\B2C;

class Delete extends \App\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		$dbc = $this->_container->DB;

		$dbc->query('BEGIN');

		$sql = 'SELECT id, stat FROM b2c_sale WHERE license_id = :l0 AND id = :c1 FOR UPDATE NOWAIT';
		$arg = array(
			':l0' => $_ENV['company_id'],
			':c1' => $ARG['id']
		);

		$cur = $dbc->fetchRow($sql, $arg);
		if (empty($cur['id'])) {
			return $this->send404('B2C Sale not found [CSD-022]');
		}

		switch ($cur['stat']) {
		case 200:
			$ret_code = 423;
			$sql = 'UPDATE b2c_sale SET stat = 423 WHERE license_id = :l0 AND id = :c1';
			$chk = $dbc->query($sql, $arg);
			$this->logAudit('B2C/Delete/Create', $ARG['id'], null);
			$ret_data['meta']['detail'] = 'B2C Sale Delete Requested';
			break;
		case 410:
			$ret_code = 410;
			$ret_data['meta']['detail'] = 'B2C Sale Delete Completed';
			break;
		case 423:
			$ret_code = 410;
			$sql = 'UPDATE b2c_sale SET stat = 410 WHERE license_id = :l0 AND id = :c1';
			$chk = $dbc->query($sql, $arg);
			$this->logAudit('B2C/Delete/Commit', $ARG['id'], null);
			$ret_data['meta']['detail'] = 'B2C Sale Delete Confirmed';
			break;
		default:
			// Failure
			throw new \Exception('Invalid B2C Sale status [CSD-048]');
		}

		$dbc->query('COMMIT');
		$ret_data['data'] = null;
		return $RES->withJSON($ret_data, $ret_code);
	}
}
