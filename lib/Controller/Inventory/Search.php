<?php
/**
 * Search Inventory
 */

namespace OpenTHC\CRE\Controller\Inventory;

class Search extends \OpenTHC\CRE\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		$sql = 'SELECT id, qty, hash FROM inventory WHERE license_id = :l ORDER BY id';
		$arg = array(':l' => $_ENV['license_id']);
		$res = $this->_container->DB->fetchAll($sql, $arg);

		return $RES->withJSON([
			'meta' => [],
			'data' => $res,
		]);

	}
}
