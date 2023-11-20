<?php
/**
 * Lab Metrics List
 */

namespace OpenTHC\CRE\Controller\Lab;

class Metric extends \OpenTHC\CRE\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		return $RES->withJSON(array(
			'data' => [],
			'meta' => [],
		), 200);
	}
}
