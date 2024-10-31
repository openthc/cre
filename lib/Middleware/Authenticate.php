<?php
/**
 * Authenticate the Session
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Middleware;

class Authenticate extends \OpenTHC\Middleware\Base
{
	use \OpenTHC\CRE\Traits\OpenAuthBox;

	/**
	 *
	 */
	public function __invoke($REQ, $RES, $NMW)
	{
		// Authorization key
		if ( ! preg_match('/Bearer v2024\/([\w\-]{43})\/([\w\-]+)/', $_SERVER['HTTP_AUTHORIZATION'], $m)) {
			return $RES->withJson([
				'data' => null,
				'meta' => [ 'note' => 'Invalid Bearer [CMA-019]' ],
			], 401);
		}

		$act = $this->open_auth_box($m[1], $m[2]);

		if (empty($_SESSION['Service']['id'])) {
			return $RES->withJSON([
				'data' => null,
				'meta' => [ 'note' => 'Not Authorized [CMA-015]' ]
			], 401);
		}

		if (empty($_SESSION['Company']['id'])) {
			return $RES->withJSON([
				'data' => null,
				'meta' => [ 'note' => 'Not Authorized [CMA-022]' ]
			], 401);
		}

		if (empty($_SESSION['License']['id'])) {
			return $RES->withJSON([
				'data' => null,
				'meta' => [ 'note' => 'Not Authorized [CMA-029]' ]
			], 401);
		}

		$RES = $NMW($REQ, $RES);

		return $RES;

	}

}
