<?php
/**
 * Search B2C
 */

namespace App\Controller\B2C;

class Search extends \App\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		$sql = 'SELECT id,hash FROM b2c_sale WHERE license_id = :l ORDER BY id OFFSET 0 LIMIT 250';
		$arg = array(':l' => $_ENV['license_id']);
		$res = $this->_container->DB->fetchAll($sql, $arg);
		return $RES->withJSON(array(
			'meta' => [],
			'data' => $res,
		));

	}
}
