<?php
/**
 * Search Variety
 */

namespace OpenTHC\CRE\Controller\Variety;

class Search extends \OpenTHC\CRE\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		$sql = 'SELECT id,hash,name FROM variety WHERE license_id = :l ORDER BY id';
		$arg = array(':l' => $_SESSION['License']['id']);
		$res = $this->_container->DB->fetchAll($sql, $arg);

		return $RES->withJSON([
			'meta' => [],
			'data' => $res,
		]);

	}
}
