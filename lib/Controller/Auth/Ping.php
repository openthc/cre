<?php
/**
 * Ping a Session
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Controller\Auth;

class Ping extends \OpenTHC\CRE\Controller\Base
{
	/**
	 *
	 */
	function __invoke($REQ, $RES, $ARG)
	{
		// $env = $this->open_auth_header_tok();

		// Check Token
		$chk = $_SERVER['HTTP_AUTHORIZATION'];
		if ( ! preg_match('/^Bearer v2024\/([\w\-]{43})$/', $chk, $m)) {
			return $RES->withJson([
				'data' => null,
				'meta' => [ 'note' => 'Invalid Bearer [LMA-068]' ],
			], 401);
		}

		$sid = $m[1];

		$chk = $this->_container->Redis->get($sid);
		if (empty($chk)) {
			return $RES->withJson([
				'data' => null,
				'meta' => [ 'note' => 'Invalid Authentication State [LMA-079]' ],
			], 401);
		}

		$ret = [];
		// $RES->getAttribute('sid');

		$ret_code = $RES->getStatusCode();
		switch ($ret_code) {
			case 200:
				// OK
				$ret = [
					'data' => [
						'sid' => $sid,
					],
					'meta' => [],
				];
				break;
			default:
				$ret['data'] = null;
				$ret['meta'] = [
					'note' => 'Invalid Session State [LMA-054]'
				];
		}

		return $RES->withJSON($ret, $ret_code);
	}
}
