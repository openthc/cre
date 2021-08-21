<?php
/**
 * Authenticate the Session
 */

namespace App\Middleware;

class Authenticate extends \OpenTHC\Middleware\Base
{
	public function __invoke($REQ, $RES, $NMW)
	{
		if (empty($_SESSION['service_id'])) {
			return $RES->withJSON([
				'data' => null,
				'meta' => [ 'detail' => 'Not Authorized [LMA-015]' ]
			], 403);
		}

		if (empty($_SESSION['company_id'])) {
			return $RES->withJSON([
				'data' => null,
				'meta' => [ 'detail' => 'Not Authorized [LMA-022]' ]
			], 403);
		}

		if (empty($_SESSION['license_id'])) {
			return $RES->withJSON([
				'data' => null,
				'meta' => [ 'detail' => 'Not Authorized [LMA-029]' ]
			], 403);
		}

		$RES = $NMW($REQ, $RES);

		return $RES;

	}
}
