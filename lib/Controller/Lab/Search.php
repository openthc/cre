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
		JOIN lab_result_inventory ON lab_result.id = lab_result_inventory.lab_result_id
		JOIN inventory ON lab_result_inventory.inventory_id = inventory.id
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
