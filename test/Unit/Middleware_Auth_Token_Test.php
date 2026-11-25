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
		$container = (new class implements \Psr\Container\ContainerInterface {
			private $services = [];
			public function get(string $id) {
				$s = $this->services[$id];
				if (is_callable($s)) return $s($this);
				return $s;
			}
			public function has(string $id): bool { return isset($this->services[$id]); }
			public function set(string $id, $value) { $this->services[$id] = $value; }
		});
		$container->set('Redis', function($c) {
			return new class {
				public function get($x) {
					return json_encode([
						'Contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_0'],
						'Company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_0'],
						'License' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_0'],
					]);
				}
			};
		});
		$auth_token = new \OpenTHC\CRE\Middleware\Auth\Token($container);
		$req = new \GuzzleHttp\Psr7\ServerRequest("GET", "/");
		$res = new \GuzzleHttp\Psr7\Response();

		$token = _random_hash();

		$_SERVER = [
			'HTTP_AUTHORIZATION' => sprintf('Bearer v2024/%s', $token),
		];
		$handler = new class implements \Psr\Http\Server\RequestHandlerInterface {
			public function handle(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface {
				return (new \GuzzleHttp\Psr7\Response())->withStatus(200);
			}
		};
		$x = $auth_token($req, $handler);
		$x->getBody()->rewind();
		$this->assertEquals(200, $x->getStatusCode());
	}
}
