<?php
/**
 * Check and Validate a v2024 Authorization Request
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Middleware;

class Check_Authorization extends \OpenTHC\Middleware\Base
{
	/**
	 *
	 */
	public function __invoke($REQ, $RES, $NMW)
	{
		// Authorization key
		if ( ! preg_match('/Bearer v2024\/([\w\-]{43})\/([\w\-]+)/', $_SERVER['HTTP_AUTHORIZATION'], $m)) {
			return $RES->withStatus(400)->withJson([
				'data' => null,
				'meta' => [ 'note' => 'Invalid Bearer [MCA-019]' ],
			]);
		}
		$client_pk = $m[1];
		$crypt_box = $m[2];

		if (empty($client_pk)) {
			return $RES->withStatus(400)->withJson([
				'data' => null,
				'meta' => [ 'note' => 'Invalid Public Key [MCA-028'],
			]);
		}
		if (empty($crypt_box)) {
			return $RES->withStatus(400)->withJson([
				'data' => null,
				'meta' => [ 'note' => 'Invalid Encrypted Key [MCA-034'],
			]);
		}

		// Identity Headers
		if (empty($_SERVER['HTTP_OPENTHC_CONTACT'])) {
			return $RES->withStatus(400)->withJson([
				'data' => null,
				'meta' => [ 'note' => 'Invalid Contact Header [MCA-041]' ],
			]);
		}
		if (empty($_SERVER['HTTP_OPENTHC_COMPANY'])) {
			return $RES->withStatus(400)->withJson([
				'data' => null,
				'meta' => [ 'note' => 'Invalid Company Header [MCA-048]' ],
			]);
		}
		if (empty($_SERVER['HTTP_OPENTHC_LICENSE'])) {
			var_dump($_SERVER);
			return $RES->withStatus(400)->withJson([
				'data' => null,
				'meta' => [ 'note' => 'Invalid License Header [MCA-054]' ],
			]);
		}
		$contact_id = $_SERVER['HTTP_OPENTHC_CONTACT'];
		$company_id = $_SERVER['HTTP_OPENTHC_COMPANY'];
		$license_id = $_SERVER['HTTP_OPENTHC_LICENSE'];

		// Decrypt the payload
		$sk = \OpenTHC\Config::get('openthc/cre/secret');
		$crypt_box = \OpenTHC\Sodium::b64decode($crypt_box);
		$data = \OpenTHC\Sodium::decrypt($crypt_box
			, $sk	// Secret Key of Recipient
			, $client_pk	// Public Key of Sender
		);
		$data = json_decode($data);
		if (empty($data)) {
			return $RES->withStatus(400)->withJson([
				'data' => null,
				'meta' => [ 'note' => 'Invalid Request [MCA-094]' ],
			]);
		}
		if (0 !== sodium_compare($data->pk, $client_pk)) {
			return $RES->withStatus(400)->withJson([
				'data' => null,
				'meta' => [ 'note' => 'Invalid Request [MCA-100]' ],
			]);
		}

		$dt0 = new \DateTime();
		$dt1 = \DateTime::createFromFormat('U', $data->ts);
		$age = $dt0->diff($dt1, true);
		if (($age->d != 0) || ($age->h != 0) || ($age->i > 5)) {
			return $RES->withStatus(400)->withJson([
				'data' => null,
				'meta' => [ 'note' => 'Invalid Date [MCA-110]' ]
			]);
		}

        /*
        // @note Expectation is that we will be dropping this for methods on Traits which will eventually make requests to SSO
		$dbc_auth = _dbc('auth');

		// Get Company
		$this->Company = $dbc_auth->fetchRow('SELECT id FROM auth_company WHERE id = :c0', [ ':c0' => $company_id ]);
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

		$RES = $RES->withStatus(200);

		return $NMW($REQ, $RES);

	}
}
