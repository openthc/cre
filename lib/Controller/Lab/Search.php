<?php
/**
 * Lab Search for Results or Samples
 */

namespace OpenTHC\CRE\Controller\Lab;

class Search extends \OpenTHC\CRE\Controller\Base
{
	/**
	 *
	 */
	function __invoke($REQ, $RES, $ARG)
	{
		$sql = <<<SQL
		SELECT lab_result.*
		FROM lab_result
		JOIN inventory_lab_result ON lab_result.id = inventory_lab_result.lab_result_id
		JOIN inventory ON inventory_lab_result.inventory_id = inventory.id
		WHERE (inventory.license_id = :l0 OR lab_result.license_id = :l0)
		ORDER BY lab_result.id
		SQL;

		$arg = [
			':l0' => $_SESSION['License']['id'],
		];

		$res = $this->_container->DB->fetchAll($sql, $arg);

		return $RES->withJSON(array(
			'data' => $res,
			'meta' => [],
		), 200);

	}
}
