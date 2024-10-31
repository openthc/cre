<?php
/**
 * Check and Validate a v2024 Authorization Request
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Middleware;

class Check_Authorization extends \OpenTHC\Middleware\Base
{
	use \OpenTHC\CRE\Traits\OpenAuthBox;

	/**
	 *
	 */
	public function __invoke($REQ, $RES, $NMW)
	{
		// Authorization key
		if ( ! preg_match('/Bearer v2024\/([\w\-]{43})\/([\w\-]+)/', $_SERVER['HTTP_AUTHORIZATION'], $m)) {
			return $RES->withJson([
				'data' => null,
				'meta' => [ 'note' => 'Invalid Bearer [MCA-019]' ],
			], 401);
		}

		$client_pk = $m[1];
		$crypt_box = $m[2];

		$act = $this->open_auth_box($client_pk, $crypt_box);

		if (empty($act->license)) {
			throw new \Exception('Authentication Box Data Corrupted [MCA-033]', 401);
		}

		/*
		$dbc_auth = _dbc('auth');

		// Get Company
		$this->Company = $dbc_auth->fetchRow('SELECT id FROM auth_company WHERE id = :c0', [ ':c0' => $act->company ]);
		if (empty($this->Company)) {
			return $RES->withStatus(400)->withJson([
				'data' => null,
				'meta' => [ 'note' => 'Invalid Company [MCA-069]' ],
			]);
		}

		// Get Contact
		$this->Contact = $dbc_auth->fetchRow('SELECT id FROM auth_contact WHERE id = :c0', [ ':c0' => $contact_id ]);
		if (empty($this->Contact)) {
			return $RES->withStatus(400)->withJson([
				'data' => null,
				'meta' => [ 'note' => 'Invalid Contact [MCA-078]' ],
			]);
		}

		*/

		$_SESSION['Service'] = [ 'id' => $act->pk ];
		$_SESSION['Contact'] = [ 'id' => $act->contact ];
		$_SESSION['Company'] = [ 'id' => $act->company ];
		$_SESSION['License'] = [ 'id' => $act->license];

		// Identity Headers (Should Match?)
		// if (empty($_SERVER['HTTP_OPENTHC_CONTACT_ID'])) {
		// 	return $RES->withJson([
		// 		'data' => null,
		// 		'meta' => [ 'note' => 'Invalid Contact Header [MCA-041]' ],
		// 	], 400);
		// }
		// if (empty($_SERVER['HTTP_OPENTHC_COMPANY_ID'])) {
		// 	return $RES->withJson([
		// 		'data' => null,
		// 		'meta' => [ 'note' => 'Invalid Company Header [MCA-048]' ],
		// 	], 400);
		// }
		// if (empty($_SERVER['HTTP_OPENTHC_LICENSE_ID'])) {
		// 	// var_dump($_SERVER);
		// 	return $RES->withJson([
		// 		'data' => null,
		// 		'meta' => [ 'note' => 'Invalid License Header [MCA-054]' ],
		// 	], 400);
		// }

		// $contact_id = $_SERVER['HTTP_OPENTHC_CONTACT_ID'];
		// $company_id = $_SERVER['HTTP_OPENTHC_COMPANY_ID'];
		// $license_id = $_SERVER['HTTP_OPENTHC_LICENSE_ID'];


		return $NMW($REQ, $RES);

	}
}
