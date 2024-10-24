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
		if ( ! preg_match('/Bearer v2024\/([\w\-]{43})/', $_SERVER['HTTP_AUTHORIZATION'], $m)) {
			return $RES->withJson([
				'data' => null,
				'meta' => [ 'note' => 'Invalid Bearer [MAT-021]' ],
			], 400);
		}

        $token = $m[1];
        $session = $this->_container->Redis->get($token);
        if (empty($session)) {
            return $RES->withJson([
				'data' => null,
				'meta' => [ 'note' => 'Expired Bearer [MAT-030]' ],
			], 400);
        }

        $session = json_decode($session);
        if (empty($session->Contact)) {
            return $RES->withJson([
				'data' => null,
				'meta' => [ 'note' => 'Invalid Contact [MAT-038]' ],
			], 400);
        }
        if (empty($session->Company)) {
            return $RES->withJson([
				'data' => null,
				'meta' => [ 'note' => 'Invalid Company [MAT-044]' ],
			], 400);
        }
        if (empty($session->License)) {
            return $RES->withJson([
				'data' => null,
				'meta' => [ 'note' => 'Invalid License [MAT-050]' ],
			], 400);
        }

        return $RES->withStatus(200);
    }
}
