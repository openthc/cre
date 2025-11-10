<?php
/**
 * Contact Search Interface
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Controller\Contact;

class Search extends \OpenTHC\CRE\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		$sql = <<<SQL
SELECT id, hash, name
FROM contact
WHERE company_id = :c0
ORDER BY id
SQL;

		$arg = array(
			':c0' => $_SESSION['company_id'],
		);

		$res = $this->_container->DB->fetchAll($sql, $arg);

		return $RES->withJSON(array(
			'data' => $res,
			'meta' => [],
		));

	}
}
