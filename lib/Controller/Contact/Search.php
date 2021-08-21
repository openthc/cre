<?php
/**
 * Contact Search Interface
 */

namespace App\Controller\Contact;

class Search extends \App\Controller\Base
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
