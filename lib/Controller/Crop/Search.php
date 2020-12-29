<?php
/**
 * Search Crop
 */

namespace App\Controller\Crop;

class Search extends \App\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		$sql = 'SELECT id, variety_id, hash FROM plant WHERE license_id = :l0 ORDER BY id';
		$arg = array(':l0' => $_ENV['license_id']);
		$res = $this->_container->DB->fetchAll($sql, $arg);

		return $RES->withJSON([
			'meta' => [],
			'data' => $res,
		]);
	}
}
