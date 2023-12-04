<?php
/**
 * Search B2C
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Controller\B2C;

class Search extends \OpenTHC\CRE\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		$sql = 'SELECT id,hash FROM b2c_sale WHERE license_id = :l ORDER BY id OFFSET 0 LIMIT 250';
		$arg = array(':l' => $_SESSION['License']['id']);
		$res = $this->_container->DB->fetchAll($sql, $arg);
		return $RES->withJSON(array(
			'meta' => [],
			'data' => $res,
		));

	}
}
