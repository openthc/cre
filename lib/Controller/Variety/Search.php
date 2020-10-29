<?php
/**
 * Search Strain
 */

namespace App\Controller\Strain;

class Search extends \App\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		$sql = 'SELECT id,hash,name FROM strain WHERE license_id = :l ORDER BY id';
		$arg = array(':l' => $_ENV['license_id']);
		$res = $this->_container->DB->fetchAll($sql, $arg);

		return $RES->withJSON([
			'meta' => [],
			'data' => $res,
		]);

	}
}
