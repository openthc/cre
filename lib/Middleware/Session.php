<?php
/**
 * Start A Session
 *
 * SPDX-License-Identifier: MIT
 */

namespace App\Middleware;

class Session extends \OpenTHC\Middleware\Base
{
	public function __invoke($REQ, $RES, $NMW)
	{
		$start = true;

		// $start = false;
		if ( ! empty($_SERVER['HTTP_OPENTHC_SESSION'])) {
			if ('NONE' == $_SERVER['HTTP_OPENTHC_SESSION']) {
				$start = false;
			}
		}

		// $sn = session_name();

		// if ( ! empty($_COOKIE[$sn])) {

			// Session ID provided here, use normal PHP methods, wrapped by Radix

		// } elseif (!empty($_SERVER['HTTP_AUTHORIZATION'])) {

		// 	$x = (preg_match('/^Bearer (.+)$/', $_SERVER['HTTP_AUTHORIZATION'], $m) ? $m[1] : null);

		// 	if (!empty($x)) {
		// 		session_id($x);
		// 		$start = true;
		// 	}

		// } elseif (!empty($_GET['sid'])) {

		// 	$x = (preg_match('/^(\w+)$/', $_GET['sid'], $m) ? $m[1] : null);

		// 	if (!empty($x)) {
		// 		session_id($x);
		// 		$start = true;
		// 	}
		// }

		if ($start) {

			session_start();

			if (empty($_SESSION['id'])) {
				// The Session is NOT VALID
				// @todo Session Destroy Here
				// $RES = $RES->withJSON([
				// 	'data' => null,
				// 	'meta' => [ 'detail' => 'Invalid Session State [LMS-048]' ]
				// ], 403);
			}

			// Export from session into $_ENV
			// Attach to $REQ->withAttribute?
			foreach ($_SESSION as $k => $v) {
				if ('_' != substr($k, 0, 1)) {
					$_ENV[$k] = $v;
				}
			}

		}

		// Next
		$RES = $NMW($REQ, $RES);

		return $RES;

	}
}
