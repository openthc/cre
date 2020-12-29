<?php
/**
 * Authenticate group handler
 */

namespace App\Module;

class Auth extends \OpenTHC\Module\Base
{
	function __invoke($a)
	{
		$a->post('/oauth', function($REQ, $RES, $ARG) {

			$cfg = \OpenTHC\Config::get('openthc/sso/hostname');
			if (empty($cfg)) {
				return $RES->withJSON([
					'data' => null,
					'meta' => [ 'detail' => 'Invalid Configuration [AMA-018]' ],
				], 403);
			}

			$url = sprintf('https://%s/oauth2/token', $cfg);
			$RES = $RES->withJSON([]);
			$RES = $RES->withRedirect($url, 307);

			return $RES;

		});

		$a->post('/open', 'App\Controller\Auth\Open')
			// ->add('Custom\Middleware\AutoCreate')
			;

		$a->get('/ping', function($REQ, $RES, $ARG) {

			$ret = [
				'data' => [
					'sid' => session_id(),
					// '_ENV' => $_ENV,
					// '_SESSION' => $_SESSION,
				],
				'meta' => [],
			];

			return $RES->withJSON($ret);

		});

		$a->post('/shut', 'OpenTHC\Controller\Auth\Shut');
	}
}
