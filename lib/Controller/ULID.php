<?php
/*
 * Generate ULIDs
 * @param t DateTime to use for ULID
 * @param p String to prefix random segment
 */

namespace App\Controller;

class ULID extends \App\Controller\Base
{
	const PREFIX_LENGTH_MAX = 12;

	function __invoke($REQ, $RES, $ARG)
	{
		$t_want = $_GET['t'];
		$p_want = $_GET['p'];

		$t_make = null;

		if (!empty($t_want)) {
			try {

				$dt = new \DateTime($t_want);

				$u = $dt->format('U');
				$v = $dt->format('v');

				$t_make = sprintf('%d%03d', $u, $v);

			} catch (\Exception $e) {
				_exit_text("Invalid DateTime Value\n", 400);
			}
		}

		$ulid = \Edoceo\Radix\ULID::create($t_make);

		$t = substr($ulid, 0, 10);
		$r = substr($ulid, 10);

		if (!empty($p_want)) {

			$p_want = substr($p_want, 0, self::PREFIX_LENGTH_MAX);
			$p_size = strlen($p_want);

			$r = substr($r, $p_size);
			$r = $p_want . $r;

		}

		$out = $t. $r;

		_exit_text("$out\n");

	}
}
