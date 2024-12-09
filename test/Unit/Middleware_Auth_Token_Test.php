<?php
/**
 * Test the Auth Token Middleware
 */

namespace OpenTHC\CRE\Test\Unit;

class Middleware_Auth_Token_Test extends \OpenTHC\Test\Base
{
	/**
	 * The happy path
	 */
	public function test_token()
	{
		$container = new \Slim\Container();
		$container['Redis'] = function($c) {
			return new class {
				public function get($x) {
					return json_encode([
						'Contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_0'],
						'Company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_0'],
						'License' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_0'],
					]);
				}
			};
		};
		$auth_token = new \OpenTHC\CRE\Middleware\Auth\Token($container);
		$req = \Slim\Http\Request::createFromEnvironment(\Slim\Http\Environment::mock())
			->withMethod('GET')
			->withUri(\Slim\Http\Uri::createFromString('/'))
		;
		$res = new \Slim\Http\Response();

		$token = _random_hash();

		$_SERVER = [
			'HTTP_AUTHORIZATION' => sprintf('Bearer v2024/%s', $token),
		];
		$x = $auth_token($req, $res, function($req, $res) {
			return $res->withStatus(200);
		});
		$x->getBody()->rewind();
		$this->assertEquals(200, $x->getStatusCode());
	}
}
