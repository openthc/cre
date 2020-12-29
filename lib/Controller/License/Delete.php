<?php
/**
 * Delete License
 */

namespace App\Controller\License;

class Delete extends \App\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		// But Deny Anyway
		return $this->sendError('Not Allowed [CCD#014]', 405);

		// return $this->one_step_delete($REQ, $RES, $ARG);
		// return $this->two_step_delete($REQ, $RES, $ARG);
	}


	/**
	 * Wrapper for doing a one step delete
	 * @param [type] $REQ [description]
	 * @param [type] $RES [description]
	 * @param [type] $ARG [description]
	 * @return [type] [description]
	 */
	function one_step_delete($REQ, $RES, $ARG)
	{

	}


	/**
	 * Wrapper for doing a two step delete
	 * @param [type] $REQ [description]
	 * @param [type] $RES [description]
	 * @param [type] $ARG [description]
	 * @return [type] [description]
	 */
	function two_step_delete($REQ, $RES, $ARG)
	{

		$dbc = $this->_container->DB;

		// Find and Update
		$rec_code = 405;
		$ret_data = array(
			'status' => 'success',
		);

		$dbc->query('BEGIN');

		$sql = 'SELECT id, stat FROM license WHERE company_id = :c0 AND id = :c1 FOR UPDATE NOWAIT';
		$arg = array(
			':c0' => $_ENV['company_id'],
			':c1' => $ARG['id']
		);
		$obj = $dbc->fetchRow($sql, $arg);

		if (empty($obj['id'])) {
			return $this->send404('License not found [CLD#064]');
		}

		switch ($obj['stat']) {
		case 200:
			$ret_code = 423;
			$sql = 'UPDATE license SET stat = 423 WHERE company_id = :c0 AND id = :c1';
			$chk = $dbc->query($sql, $arg);
			$this->logAudit('License/Delete/Create', $ARG['id'], null);
			$ret_data['detail'] = 'Delete Requested';
			break;
		case 410:
			$ret_code = 410;
			$ret_data['status'] = 'failure';
			$ret_data['detail'] = 'Delete Completed';
			break;
		case 423:
			$ret_code = 410;
			$sql = 'UPDATE license SET stat = 410 WHERE company_id = :c0 AND id = :c1';
			$chk = $dbc->query($sql, $arg);
			$this->logAudit('License/Delete/Commit', $ARG['id'], null);
			$ret_data['detail'] = 'Delete Confirmed';
			break;
		default:
			return $RES->withJSON(array(
				'data' => array(),
				'meta' => array(
					'detail' => sprintf('Invalid License Status "%d" [CPD#046]', $obj['stat']),
				),
			), 500);
		}

		$dbc->query('COMMIT');

		return $RES->withJSON($ret_data, $ret_code);

		// return $RES->withJSON(array(
		// 	'status' => 'failure',
		// 	'result' => 'Not Allowed [CLD#014]',
		// ), 405);
	}
}
