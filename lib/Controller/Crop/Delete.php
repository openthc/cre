<?php
/**
 * Delete a Crop Object
 */

namespace OpenTHC\CRE\Controller\Crop;

class Delete extends \OpenTHC\CRE\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		$dbc = $this->_container->DB;

		$sql = 'SELECT id, stat FROM plant WHERE license_id = :l0 AND id = :pk FOR UPDATE NOWAIT';
		$arg = array(
			':l0' => $_SESSION['License']['id'],
			':pk' => $ARG['id']
		);

		// Find and Update
		$cur = $dbc->fetchRow($sql, $arg);
		if (empty($cur['id'])) {
			return $this->send404('Crop not found [CPD-022]');
		}

		$this->logAudit('Crop/Delete', $ARG['id'], null);

		// Status State Machine
		switch ($cur['stat']) {
		case 200:
			$ret_code = 202;
			$ret_text = 'Crop Delete Requested';
			$chk = $dbc->query('UPDATE plant SET stat = 410 WHERE license_id = :l0 AND id = :pk', $arg);
			$this->logAudit('Crop/Delete/Create', $ARG['id'], null);
			break;
		case 410:
			$ret_code = 410;
			$ret_text = 'Crop Delete Confirmed';
			$chk = $dbc->query('UPDATE plant SET stat = 423 WHERE license_id = :l0 AND id = :pk', $arg);
			$this->logAudit('Crop/Delete/Commit', $ARG['id'], null);
			break;
		case 423:
			$ret_code = 423;
			$ret_text = 'Crop Locked';
			break;
		default:
			// Failure
			throw new \Exception('Invalid Crop Status [CPD-039]');
		}

		$dbc->query('COMMIT');

		return $RES->withJSON(array(
			'meta' => [ 'note' => $ret_text ],
			'data' => [],
		), $ret_code);

	}

}
