<?php
/**
 * Return a List of Company Objects
 */

namespace OpenTHC\CRE\Controller\Company;

class Search extends \OpenTHC\CRE\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		$res = $this->dic->get('DB')->fetchAll('SELECT id,hash,name FROM company ORDER BY id');
		return $RES->withJSON([
			'meta' => [],
			'data' => $res,
		]);
	}
}
