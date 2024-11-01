<?php
/**
 * Shut a Session
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Controller\Auth;

class Shut extends \OpenTHC\CRE\Controller\Base
{
	// use Traits\LoadSession;

	/**
	 *
	 */
	function __invoke($REQ, $RES, $ARG)
	{
		$chk = $_SERVER['HTTP_AUTHORIZATION'];
		if ( ! preg_match('/^Bearer v2024\/([\w\-]{43})$/', $chk, $m)) {
			return $RES->withJson([
				'data' => null,
				'meta' => [ 'note' => 'Invalid Bearer [CAS-021]' ],
			], 401);
		}

		$sid = $m[1];

		$rdb = $this->_container->Redis;
		$chk = $rdb->get($sid);
		$del = $rdb->del($sid);

		if (empty($chk)) {

		}

		return $RES->withJSON([
			'data' => null,
			'meta' => [ 'note' => 'Session Shut' ]
		]);
	}

}
