<?php
/**
 * Authenticate Routes
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Module;

class Auth extends \OpenTHC\Module\Base
{
	/**
	 *
	 */
	function __invoke($a)
	{
		$a->get('', function($REQ, $RES, $ARG) {
			if ( ! empty($_GET['_'])) {
				__exit_text('Decipher Token', 501);
				// Decipher Token?
				// Start Session?
			}
			$txt = <<<TEXT
			OpenTHC Compliance Reporting Engine

			Authenticate:
			  - GET /auth?_={token}
			  - GET /auth/open (does SSO)
			  - POST /auth with Authorization Header
			TEXT;
			__exit_text($txt);
		});

		$a->get('/open', function($REQ, $RES, $ARG) {
			__exit_text('Should Redirect to SSO', 501);
		});

		// POST to Open
		$a->post('/open', 'OpenTHC\CRE\Controller\Auth\Open');

		// oAuth Back?
		$a->post('/oauth', function($REQ, $RES, $ARG) {

			$cfg = \OpenTHC\Config::get('openthc/sso/origin');
			if (empty($cfg)) {
				return $RES->withJSON([
					'data' => null,
					'meta' => [ 'note' => 'Invalid Configuration [AMA-018]' ],
				], 403);
			}

			$url = sprintf('%s/oauth2/token', $cfg);
			$RES = $RES->withHeader('content-type', 'text/plain');
			$RES = $RES->withRedirect($url, 307);

			return $RES;

		});

		$a->get('/ping', 'OpenTHC\CRE\Controller\Auth\Ping')
			// ->add('OpenTHC\CRE\Middleware\Auth\Token')
			;

		// Destroy Session
		$a->get('/shut', 'OpenTHC\CRE\Controller\Auth\Shut');
		$a->post('/shut', 'OpenTHC\CRE\Controller\Auth\Shut');
	}
}
