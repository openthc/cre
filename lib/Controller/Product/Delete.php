<?php
/**
 * Delete a Product
 */

namespace App\Controller\Product;

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
		$ret_text = 'Unexpected Error';

		// Find and Update
		$cur = $dbc->fetchRow('SELECT id, stat FROM product WHERE license_id = :l AND id = :g FOR UPDATE NOWAIT', $arg);
		if (empty($cur['id'])) {
			return $this->send404('Product not found [CPD#027]');
		}

		// Process Delete
		switch ($cur['stat']) {
		case 200:
			$ret_code = 202;
			$ret_text = 'Product Delete Requested';
			$sql = 'UPDATE product SET stat = 410 WHERE license_id = :l AND id = :g';
			$chk = $dbc->query($sql, $arg);
			$this->logAudit('Product/Delete/Create', $ARG['id'], null);
			break;
		case 410:
			$ret_code = 410;
			$ret_text = 'Product Delete Confirmed';
			$sql = 'UPDATE product SET stat = 423 WHERE license_id = :l AND id = :g';
			$chk = $dbc->query($sql, $arg);
			$this->logAudit('Product/Delete/Commit', $ARG['id'], null);
			break;
		case 423:
			$ret_code = 423;
			$ret_text = 'Product Locked';
			break;
		default:
			// Failure
			throw new \Exception(sprintf('Invalid Product Status "%s/%d" [CPD#046]', $cur['id'], $cur['stat']));
		}

		$dbc->query('COMMIT');

		return $RES->withJSON(array(
			'data' => [],
			'meta' => [ 'detail' => $ret_text ],
		), $ret_code);

	}
}
