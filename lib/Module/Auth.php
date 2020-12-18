<?php
/**
 * Authenticate group handler
 */

namespace App\Module;

class Auth extends \OpenTHC\Module\Base
{
	function __invoke($a)
	{
		$a->post('/open', 'App\Controller\Auth\Open')
			->add('App\Middleware\Auth\Simple')
			;

		$a->post('/oauth', function($REQ, $RES, $ARG) {
			$cfg = \OpenTHC\Config::get('openthc_sso');
			$url = $cfg['url'];
			if (empty($url)) {
				return $RES->withJSON([
					'meta' => [ 'detail' => 'Invalid Configuration [AMA#023]' ],
					'data' => null,
				], 403);
			}
			$url.= '/oauth2/token';
			$RES = $RES->withJSON([]);
			$RES = $RES->withRedirect($url, 307);
			return $RES;
		});

		$a->get('/ping', function($REQ, $RES, $ARG) {

			return $RES->withJSON([
				'meta' => [],
				'data' => [
					'sid' => session_id(),
					'_ENV' => $_ENV,
					'_SESSION' => $_SESSION,
				]
			]);
		});

		$a->post('/shut', 'OpenTHC\Controller\Auth\Shut');
	}
}
