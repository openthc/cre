<?php
/**
 * Decrypt the Token or Throw
 *
 * SPDX-License-Identifier: MIT
 *
 * Copied from POS
 */

namespace OpenTHC\CRE\Traits;

trait OpenAuthBox
{
	/**
	 *
	 */
	function open_auth_box_header() : object
	{
		$chk = $_SERVER['HTTP_AUTHORIZATION'];
		if ( ! preg_match('/^Bearer v2024\/([\w\-]{43})\/([\w\-]+)$/', $chk, $m)) {
			throw new \Exception('Invalid Bearer [OAB-017]', 401);
		}

		return $this->open_auth_box($m[1], $m[2]);

	}

	/**
	 *
	 */
	function open_auth_box(string $cpk, string $box) : object
	{
		$box = \OpenTHC\Sodium::b64decode($box);

		$ssk = \OpenTHC\Config::get('openthc/cre/secret');
		$act = \OpenTHC\Sodium::decrypt($box, $ssk, $cpk);
		if (empty($act)) {
			throw new \Exception('Authentication Box Invalid Service Key [PCB-025]', 401);
		}
		$act = json_decode($act);
		if (empty($act)) {
			throw new \Exception('Authentication Box Invalid Service Key [PCB-029]', 401);
		}
		if (sodium_compare($act->pk, $cpk) !== 0) {
			throw new \Exception('Authentication Box Invalid Service Key [PCB-032]', 401);
		}

		// Time Check
		$dt0 = new \DateTime();
		$dt1 = \DateTime::createFromFormat('U', $act->ts);
		$age = $dt0->diff($dt1, true);
		if (($age->d != 0) || ($age->h != 0) || ($age->i > 5)) {
			throw new \Exception('Authentication Box Expired [PCB-040]', 401);
		}

		if (empty($act->contact)) {
			throw new \Exception('Authentication Box Data Corrupted [PCB-103]', 401);
		}

		if (empty($act->company)) {
			throw new \Exception('Authentication Box Data Corrupted [PCB-110]', 401);
		}

		return $act;

	}

}
