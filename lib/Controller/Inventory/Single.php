<?php
/**
 * Single Inventory
 */

namespace App\Controller\Inventory;

class Single extends \App\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		$sql = 'SELECT * FROM inventory WHERE license_id = :l AND id = :g';
		$arg = array(
			':l' => $_ENV['license_id'],
			':g' => $ARG['id']
		);

		$rec =$this->_container->DB->fetch_row($sql, $arg);
		if (empty($rec['id'])) {
			return $this->send404('Inventory not found [CLS#020]');
		}

		return $RES->withJSON([
			'meta' => [],
			'data' => $rec,
		]);

	}
}
