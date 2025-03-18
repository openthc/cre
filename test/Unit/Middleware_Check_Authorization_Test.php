<?php
/**
 * Test the Check Signature Middleware
 */

namespace OpenTHC\CRE\Test\Unit;

use OpenTHC\CRE\Middleware\Check_Authorization;

class Middleware_Check_Authorization_Test extends \OpenTHC\Test\Base
{
	/**
	 * The happy path
	 */
	public function test_authorization()
	{
		// Environment setup
		$authorization = new Check_Authorization(new \Slim\Container());
		$req = \Slim\Http\Request::createFromEnvironment(\Slim\Http\Environment::mock())
			->withMethod('GET')
			->withUri(\Slim\Http\Uri::createFromString('/'))
		;
		$res = new \Slim\Http\Response();

		// Credentials
		$client_service_pk = \OpenTHC\Config::get('openthc/cre/public');
		$client_service_sk = \OpenTHC\Config::get('openthc/cre/secret');
		$server_pk = $client_service_pk; // Because cre is making the request to cre

		$plain_data = json_encode([
			// These are the minimum required in the payload
			'pk' => $client_service_pk,
			'ts' => time(),
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_0'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_0'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_0']
		]);
		$crypt_box = \OpenTHC\Sodium::encrypt($plain_data, $client_service_sk, $server_pk);
		$crypt_box = \OpenTHC\Sodium::b64encode($crypt_box);
		$token = sprintf('%s/%s', $client_service_pk, $crypt_box);

		$_SERVER = [
			'HTTP_AUTHORIZATION' => sprintf('Bearer v2024/%s', $token),
			// 'HTTP_OPENTHC_CONTACT_ID' => OPENTHC_TEST_CLIENT_CONTACT_0,
			// 'HTTP_OPENTHC_COMPANY_ID' => OPENTHC_TEST_CLIENT_COMPANY_0,
			// 'HTTP_OPENTHC_LICENSE_ID' => OPENTHC_TEST_CLIENT_LICENSE_0,
		];
		$x = $authorization($req, $res, function($req, $res) {
			return $res->withStatus(200);
		});
		$x->getBody()->rewind();
		// var_dump($x->getBody()->getContents());
		$this->assertEquals(200, $x->getStatusCode());
	}
}
