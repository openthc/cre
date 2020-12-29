<?php
/**
 * Lab Search for Results or Samples
 */

namespace App\Controller\Lab;

class Search extends \App\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		$sql = <<<SQL
SELECT lab_result.*
FROM lab_result
JOIN lab_result_lot ON lab_result.id = lab_result_lot.lab_result_id
JOIN lot ON lab_result_lot.lot_id = lot.id
WHERE (lot.license_id = :l0 OR lab_result.license_id = :l0)
ORDER BY lab_result.id
SQL;

		$arg = [
			':l0' => $_ENV['license_id'],
		];

		$res = $this->_container->DB->fetchAll($sql, $arg);

		return $RES->withJSON(array(
			'meta' => [],
			'data' => $res,
		), 200);

	}
}
