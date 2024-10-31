<?php
/**
 * Check the v2024 token issued by /auth/open
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Middleware\Auth;

class Token extends \OpenTHC\Middleware\Base
{
	/**
	 *
	 */
	public function __invoke($REQ, $RES, $NMW)
	{
		// Authorization key
		$auth = $_SERVER['HTTP_AUTHORIZATION'];
		if ( ! preg_match('/^Bearer v2024\/([\w\-]{43})$/', $auth, $m)) {
			return $RES->withJson([
				'data' => null,
				'meta' => [ 'note' => 'Invalid Bearer [MAT-021]' ],
			], 401);
		}

        $token = $m[1];
        $session = $this->_container->Redis->get($token);
        if (empty($session)) {
            return $RES->withJson([
				'data' => null,
				'meta' => [ 'note' => 'Expired Bearer [MAT-030]' ],
			], 401);
        }

        $session = json_decode($session);
        if (empty($session->Contact)) {
            return $RES->withJson([
				'data' => null,
				'meta' => [ 'note' => 'Invalid Contact [MAT-038]' ],
			], 401);
        }
        if (empty($session->Company)) {
            return $RES->withJson([
				'data' => null,
				'meta' => [ 'note' => 'Invalid Company [MAT-044]' ],
			], 401);
        }
        if (empty($session->License)) {
            return $RES->withJson([
				'data' => null,
				'meta' => [ 'note' => 'Invalid License [MAT-050]' ],
			], 401);
        }

		$_SESSION = [];
		$_SESSION['Service'] = [ 'id' => $session->Service ];
		$_SESSION['Contact'] = [ 'id' => $session->Contact ];
		$_SESSION['Company'] = [ 'id' => $session->Company ];
		$_SESSION['License'] = [ 'id' => $session->License ];

		// $RES = $RES->withStatus(200);
		// $REQ = $RES->withAttribute('Contact', $session->Contact);

		$RES = $NMW($REQ, $RES);

        return $RES;
    }
}
