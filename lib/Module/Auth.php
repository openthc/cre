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
		// $a->get('', function($REQ, $RES, $ARG) {
		// 	if ( ! empty($_GET['a'])) {
		// 		// Decipher Token?
		// 		// Start Session?
		// 	}
		// 	// __exit_text($_GET);
		// });
		// ->add('Custom\Middleware\AutoCreate')
		// ->add('OpenTHC\CRE\Middleware\Check_Authorization')

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

		$a->post('/open', 'OpenTHC\CRE\Controller\Auth\Open')
			// ->add('Custom\Middleware\AutoCreate')
			->add('OpenTHC\CRE\Middleware\Check_Authorization')
		;

		$a->get('/ping', function($REQ, $RES, $ARG) {

			$ret = [];
			// $RES->getAttribute('sid');

			$ret_code = $RES->getStatusCode();
			switch ($ret_code) {
				case 200:
					// OK
					$ret = [
						'data' => [
							'sid' => $_SESSION['id'],
							// '_SESSION' => $_SESSION,
						],
						'meta' => [],
					];
					break;
				default:
					$ret['data'] = null;
					$ret['meta'] = [
						'note' => 'Invalid Session State [LMA-054]'
					];
			}

			return $RES->withJSON($ret, $ret_code);

		})
			->add('OpenTHC\CRE\Middleware\Auth\Token')
		;

		$a->post('/shut', 'OpenTHC\Controller\Auth\Shut');
	}
}
