<?php
/**
 * Lab Metrics List
 */

namespace App\Controller\Lab;

class Metric extends \App\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		return $RES->withJSON(array(
			'data' => [],
			'meta' => [],
		), 200);
	}
}
