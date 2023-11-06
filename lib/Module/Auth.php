<?php
/**
 * Authenticate Routes
 *
 * SPDX-License-Identifier: MIT
 */

namespace App\Module;

class Auth extends \OpenTHC\Module\Base
{
	function __invoke($a)
	{
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

		$a->post('/open', 'App\Controller\Auth\Open')
			// ->add('Custom\Middleware\AutoCreate')
			;

		$a->get('/ping', function($REQ, $RES, $ARG) {

			$ret = [];

			$ret_code = $RES->getStatusCode();
			switch ($ret_code) {
				case 200:
					// OK
					$ret = [
						'data' => [
							'sid' => session_id(),
							// '_ENV' => $_ENV,
							// '_SESSION' => $_SESSION,
						],
						'meta' => [],
					];
					break;
				default:
					$ret['data'] = null;
					$ret['meta'] = [
						'detail' => 'Invalid Session State [LMA-054]'
					];
			}

			return $RES->withJSON($ret, $ret_code);

		});

		$a->post('/shut', 'OpenTHC\Controller\Auth\Shut');
	}
}
