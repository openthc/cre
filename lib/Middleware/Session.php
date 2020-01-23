<?php
/**

*/

namespace App\Middleware;

class Session extends \OpenTHC\Middleware\Base
{
	public function __invoke($REQ, $RES, $NMW)
	{
		$start = false;

		$sn = session_name();

		if (!empty($_COOKIE[$sn])) {

			// Session ID provided here, use normal PHP methods, wrapped by Radix
			$start = true;

		} elseif (!empty($_SERVER['HTTP_AUTHORIZATION'])) {

			$x = (preg_match('/^Bearer (.+)$/', $_SERVER['HTTP_AUTHORIZATION'], $m) ? $m[1] : null);
			if (!empty($x)) {
				session_id($x);
				$start = true;
			}

		} elseif (!empty($_GET['sid'])) {

			$x = (preg_match('/^(\w+)$/', $_GET['sid'], $m) ? $m[1] : null);
			if (!empty($x)) {
				session_id($x);
				$start = true;
			}
		}

		if ($start) {
			session_start();

			// Export from session into $_ENV
			// Atach to $REQ->withAttribute?
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
