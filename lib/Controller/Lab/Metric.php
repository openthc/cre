<?php
/**
 * Lab Metrics List
 */

namespace App\Controller\Lab;

class Metric extends \App\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		$dbc = $this->_container->DB;

		$res = $dbc->fetchAll('SELECT id, type, name, meta FROM lab_metric');

		return $RES->withJSON(array(
			'meta' => [],
			'data' => $res,
		), 200);
	}
}
