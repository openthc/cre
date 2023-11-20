<?php
/**
 * Adjust a Inventory owned by a License
 */

namespace App\Controller\Inventory;

class Adjust extends \App\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		// Check for existance
		$sql = 'SELECT id, qty, meta FROM lot WHERE license_id = :l AND id = :id';
		$arg = array(
			':l' => $_ENV['license_id'],
			':id' => $ARG['id'],
		);
		$chk = $this->_container->DB->fetchRow($sql, $arg);
		if (empty($chk['id'])) {
			return $this->send404('Inventory not found [CLA-020]');
		}

		// Adjust the Quantity
		$qty = floatval($_POST['qty']) ?: floatval($chk['qty']);

		$sql = 'UPDATE lot SET qty = :q WHERE id = :pk';
		$arg = array(
			':pk' => $ARG['id'],
			':q' => $qty,
		);
		$this->_container->DB->query($sql, $arg);
		$this->logAudit('Inventory/Adjust', $ARG['id'], \json_encode($_POST));

		return $RES->withJSON([
			'data' => null,
			'meta' => [ 'note' => 'Inventory Adjusted' ],
		], 202);

	}
}
