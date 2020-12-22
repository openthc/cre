<?php
/**
 * Open a Session
 */

namespace App\Controller\Auth;

class Open extends \App\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		// @todo should be set/handled by middleware
		if (empty($_SESSION['service_id'])) {
			return $RES->withJSON([ 'meta' =>  ['detail' => 'Missing Service [CAO-014]' ]], 400);
		}

		if (empty($_SESSION['company_id'])) {
			return $RES->withJSON([ 'meta' => ['detail' => 'Missing Company [CAO-018]']], 400);
		}

		if (empty($_SESSION['license_id'])) {
			return $RES->withJSON([ 'meta' => ['detail' => 'Missing License [CAO-022]']], 400);
		}

		return $RES->withJSON([
			'meta' => [],
			'data' => session_id(),
		]);

	}
}
