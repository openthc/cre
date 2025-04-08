<?php
/**
 *
 *
 * SPDX-License-Identifier: MIT
 *
 * Notes about the Auth module
 * The "service-key" cooresponds to a code that is a company object identifier
 * The "license-key" cooresponds to a code that is a license object identifier
 *
 * Licenses can belong to a company in a 1:M way
 * Companies can have different permissions to act on a license's object
 *
 */

namespace OpenTHC\CRE\Test\Auth;

class Alpha_Test extends \OpenTHC\CRE\Test\Base
{
	public function test_auth() : void
	{
		/**
		 * Unauthenticated Tests
		 */
		$res = $this->httpClient->get('/auth');
		$res = $this->assertValidResponse($res, 200, 'text/plain');

		// @todo Should Redirect to SSO
		$res = $this->httpClient->get('/auth/open');
		$res = $this->assertValidResponse($res, 501, 'text/plain');

		$res = $this->httpClient->post('/auth/open');
		$res = $this->assertValidResponse($res, 401);
		$this->assertIsArray($res['meta']);
		$this->assertArrayHasKey('note', $res['meta']);
		$this->assertEquals('Invalid Bearer [OAB-017]', $res['meta']['note']);

		$res = $this->httpClient->get('/auth/ping');
		$res = $this->assertValidResponse($res, 401);
		$this->assertIsArray($res['meta']);
		$this->assertArrayHasKey('note', $res['meta']);
		$this->assertEquals('Invalid Bearer [LMA-068]', $res['meta']['note']);

		$res = $this->httpClient->post('/auth/ping');
		$res = $this->assertValidResponse($res, 405);
		$this->assertEquals('HTTP Method Not Allowed', $res['meta']['note']);

		$res = $this->httpClient->get('/auth/shut');
		$res = $this->assertValidResponse($res, 401);
		$this->assertEquals('Invalid Bearer [CAS-021]', $res['meta']['note']);
		// var_dump($res);

		$res = $this->httpClient->post('/auth/shut');
		$res = $this->assertValidResponse($res, 401);
		$this->assertEquals('Invalid Bearer [CAS-021]', $res['meta']['note']);
		// var_dump($res);

	}

	public function test_open_fail() : void
	{
		// Fail
		$res = $this->httpClient->post('/auth/open');
		$res = $this->assertValidResponse($res, 401);
		$this->assertEquals('Invalid Bearer [OAB-017]', $res['meta']['note']);

		$res = $this->httpClient->get('/auth/ping');
		$res = $this->assertValidResponse($res, 401);
		$this->assertEquals('Invalid Bearer [LMA-068]', $res['meta']['note']);

		$res = $this->httpClient->post('/auth/shut');
		$res = $this->assertValidResponse($res, 401);
		$this->assertEquals('Invalid Bearer [CAS-021]', $res['meta']['note']);

		$res = $this->httpClient->get('/company/search');
		$res = $this->assertValidResponse($res, 401);
		$this->assertEquals('Invalid Bearer [MAT-021]', $res['meta']['note']);

	}

	/**
	 * Auth by POST of the Context Data
	 * @todo Move to auth_box
	 */
	function test_open_fail_license() : void
	{
		// TEST COMPANY A
		$arg = [
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_0'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_0'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_0'],
			// 'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_0'],
		];
		$res = $this->_post('/auth/open', $arg);
		$res = $this->assertValidResponse($res);

		$this->assertMatchesRegularExpression('/\w{26,256}/', $res['data']);
	}

	/**
	 * This test will get a 401 response because Company and License don't match
	 * @todo Move to auth_box
	 */
	function test_open_fail_company_license() : void
	{
		// $box =
		$res = $this->_post('/auth/open', [
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_C']
		]);
		$res = $this->assertValidResponse($res, 401);

		$this->assertMatchesRegularExpression('/CAO.125/', $res['meta']['note']);
	}

	/**
	 * Test v2024 authentication scheme
	 */
	function test_open_pass() : string
	{
		// Lower Level
		$tok = $this->make_bearer_token();
		$res = $this->httpClient->post('/auth/open', [
			'headers' => [
				'Authorization' => $tok,
			],
		]);
		$res = $this->assertValidResponse($res);

		$this->assertIsArray($res['data']);
		$this->assertArrayHasKey('sid', $res['data']);
		$sid = $res['data']['sid'];
		$this->assertMatchesRegularExpression('/^[\w\-]{26,256}$/', $sid);

		return $sid;

	}

	/**
	 * Test the full v2024 authentication handshake
	 * @depends test_open_pass
	 */
	function test_auth_ping(string $sid) : void
	{
		$res = $this->httpClient->get('/auth/ping', [
			'headers' => [
				'Authorization' => sprintf('Bearer v2024/%s', $sid),
			],
		]);
		$this->assertValidResponse($res);
		// $this->assertEquals(200, $res0->getStatusCode());
	}

	/**
	 * Destroy Session
	 * @depends test_open_pass
	 */
	function test_auth_shut(string $sid) : void
	{
		$this->assertNotEmpty($sid);

		// Ping It, should fail
		$res = $this->httpClient->get('/auth/shut', [
			'headers' => [
				'Authorization' => sprintf('Bearer v2024/%s', $sid),
			],
		]);
		$res = $this->assertValidResponse($res);
		// var_dump($res);
		$this->assertEmpty($res['data']['sid']);

		$res = $this->httpClient->get('/auth/ping', [
			'headers' => [
				'Authorization' => sprintf('Bearer v2024/%s', $sid),
			],
		]);
		$res = $this->assertValidResponse($res, 401);
	}

	function test_auth_client() : void
	{
		$httpClientRoot = $this->makeHTTPClient();
		$res = $httpClientRoot->get('/auth/ping');
		$res = $this->assertValidResponse($res, 200);
		// var_dump($res);
		$this->assertIsArray($res);
		$this->assertArrayHasKey('data', $res);
		$this->assertArrayHasKey('sid', $res['data']);
		$this->assertMatchesRegularExpression('/[\w\-]{43}/', $res['data']['sid']);


		$cfg = [];
		$cfg['service'] = $_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'];
		$cfg['contact'] = $_ENV['OPENTHC_TEST_CLIENT_CONTACT_A'];
		$cfg['company'] = $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'];
		$cfg['license'] = $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A'];
		$httpClientUser = $this->makeHTTPClient();
		$res = $httpClientUser->get('/auth/ping');
		$res = $this->assertValidResponse($res, 200);
		// var_dump($res);
		$this->assertIsArray($res);
		$this->assertArrayHasKey('data', $res);
		$this->assertArrayHasKey('sid', $res['data']);
		$this->assertMatchesRegularExpression('/[\w\-]{43}/', $res['data']['sid']);

	}

}
