<?php
/*
 * Generate ULIDs
 * @param t DateTime to use for ULID
 * @param p String to prefix random segment
 */

namespace App\Controller;

class ULID extends \App\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		$t_want = $_GET['t'];
		$r_want = $_GET['p'];

		$t_make = null;

		try {
			if (!empty($t_want)) {

				$dt = new \DateTime($t_want);

				$s = $dt->format('U'); // / 1000;
				$ms = sprintf('%03d', $v / 1000);
				$t_make = $s . $ms;
			}
		} catch (\Exception $e) {
			_exit_text('Invalid DateTime Value', 400);
		}

		$ulid = \Edoceo\Radix\ULID::generate($t_make);

		$t = substr($ulid, 0, 10);
		$r = substr($ulid, 10);

		if (!empty($r_want)) {
			$p_size = strlen($r_want);
			if ($p_size > 8) {
				$r_want = substr($r_want, 0, 8);
				$p_size = 8;
			}

			$r = substr($r, $p_size);
			$r = $r_want . $r;

		}

		_exit_text($t . $r);

	}
}
