<?php
/**
 * Delete a Contact
 */

namespace OpenTHC\CRE\Controller\Contact;

class Delete extends \OpenTHC\CRE\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		$dbc = $this->_container->DB;

		// Lookup
		$sql = 'SELECT * FROM contact WHERE id = :c1 AND company_id = :c0';
		$arg = array(
			':c0' => $_ENV['company_id'],
			':c1' => $ARG['id']
		);
		$rec = $dbc->fetch_row($sql, $arg);

		if (empty($rec)) {
			return $this->send404('Contact not found [CCD#023]');
		}

		// Find and Update
		$rec_code = 405;
		$ret_data = array(
			'meta' => [],
			'data' => null,
		);

		$dbc->query('BEGIN');

		$sql = 'SELECT id, stat FROM contact WHERE company_id = :c0 AND id = :c1 FOR UPDATE NOWAIT';
		$arg = array(
			':c0' => $_ENV['company_id'],
			':c1' => $ARG['id']
		);
		$cur = $dbc->fetchRow($sql, $arg);
		switch ($cur['stat']) {
		case 200:
			$ret_code = 423;
			$sql = 'UPDATE contact SET stat = 423 WHERE company_id = :c0 AND id = :c1';
			$chk = $dbc->query($sql, $arg);
			$this->logAudit('Contact/Delete/Create', $ARG['id'], null);
			$ret_data['meta']['detail'] = 'Delete Requested';
			break;
		case 410:
			$ret_code = 410;
			$ret_data['meta']['detail'] = 'Delete Completed';
			break;
		case 423:
			$ret_code = 410;
			$sql = 'UPDATE contact SET stat = 410 WHERE company_id = :c0 AND id = :c1';
			$chk = $dbc->query($sql, $arg);
			$this->logAudit('Contact/Delete/Commit', $ARG['id'], null);
			$ret_data['meta']['detail'] = 'Delete Confirmed';
			break;
		default:
			// Failure
			throw new \Exception('Invalid Contact Status [CCD#064]');
		}

		$dbc->query('COMMIT');
		return $RES->withJSON($ret_data, $ret_code);

	}
}
