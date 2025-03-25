<?php
/**
 * Authenticate the Session
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Middleware;

class Authenticate extends \OpenTHC\Middleware\Base
{
	// use \OpenTHC\CRE\Traits\OpenAuthBox;

	/**
	 *
	 */
	public function __invoke($REQ, $RES, $NMW)
	{
		// Authorization key
		$chk = $_SERVER['HTTP_AUTHORIZATION'];
		if ( ! preg_match('/^Bearer v2024\/([\w\-]{43})$/', $chk, $m)) {
			return $RES->withJson([
				'data' => null,
				'meta' => [ 'note' => 'Invalid Bearer [MAT-021]' ],
			], 401);
		}

		$sid = $m[1];
		$chk = $this->_container->Redis->get($sid);
		if (empty($chk)) {
			return $RES->withJson([
				'data' => null,
				'meta' => [ 'note' => 'Expired Bearer [MAT-030]' ],
			], 401);
		}

		$_SESSION = json_decode($chk, true);

		if (empty($_SESSION['Service']['id'])) {
			return $RES->withJSON([
				'data' => null,
				'meta' => [ 'note' => 'Not Authorized [CMA-015]' ]
			], 401);
		}

		if (empty($_SESSION['Contact']['id'])) {
			return $RES->withJSON([
				'data' => null,
				'meta' => [ 'note' => 'Not Authorized [CMA-022]' ]
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
