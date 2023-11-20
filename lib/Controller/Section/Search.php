<?php
/**
 * Return List of Sections
 */

namespace OpenTHC\CRE\Controller\Section;

class Search extends \OpenTHC\CRE\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		$sql = 'SELECT id,hash,name FROM section WHERE license_id = :l0 ORDER BY id';
		$arg = [ $_ENV['license_id'] ];
		$res = $this->_container->DB->fetchAll($sql, $arg);
		return $RES->withJSON(array(
			'data' => $res,
			'meta' => [],
		));

	}
}
