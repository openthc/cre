<?php
/**
 * Return a List of Company Objects
 */

namespace App\Controller\Company;

class Search extends \App\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		$res = $this->_container->DB->fetchAll('SELECT id,hash,name FROM company ORDER BY id');
		return $RES->withJSON([
			'meta' => [],
			'data' => $res,
		]);
	}
}
