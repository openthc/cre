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
		$this->assertEquals(404, $res->getStatusCode());
		// $this->assertValidResponse($res, 404);

		$res = $this->httpClient->get('/auth/open');
		$this->assertValidResponse($res, 405);

		$res = $this->httpClient->post('/auth/open');
		$res = $this->assertValidResponse($res, 401);
		$this->assertIsArray($res['meta']);
		$this->assertArrayHasKey('note', $res['meta']);
		$this->assertEquals('Invalid Bearer [MCA-019]', $res['meta']['note']);

		$res = $this->httpClient->get('/auth/ping');
		$this->assertValidResponse($res, 401);

		$res = $this->httpClient->post('/auth/ping');
		$this->assertValidResponse($res, 405);

		$res = $this->httpClient->get('/auth/shut');
		$this->assertValidResponse($res, 405);

		$res = $this->httpClient->post('/auth/shut');
		$this->assertValidResponse($res, 200);

	}

	public function test_open_fail() : void
	{
		// Fail
		$res = $this->_post('/auth/open', [
			'service' => 'garbage-data',
			'company' => 'garbage-data',
			'license' => 'garbage-data',
		]);

		$res = $this->assertValidResponse($res, 401);

		$this->assertEquals('Invalid Bearer [MCA-019]', $res['meta']['note']);
	}

	/**
	 * Auth by POST of the Context Data
	 * @todo Move to auth_box
	 */
	function x_test_open_fail_license() : void
	{
		// TEST COMPANY A
		$arg = [
			'service' => OPENTHC_TEST_SERVICE_ID,
			'company' => OPENTHC_TEST_COMPANY_ID,
			'contact' => OPENTHC_TEST_CONTACT_ID,
			// 'license' => OPENTHC_TEST_LICENSE_ID,
		];
		$res = $this->_post('/auth/open', $arg);
		$res = $this->assertValidResponse($res);

		$this->assertMatchesRegularExpression('/\w{26,256}/', $res['data']);
	}

	/**
	 * This test will get a 400 response because Company and License don't match
	 * @todo Move to auth_box
	 */
	function x_test_open_fail_company_license() : void
	{
		$res = $this->_post('/auth/open', [
			'service' => OPENTHC_TEST_CLIENT_SERVICE_A,
			'company' => OPENTHC_TEST_CLIENT_COMPANY_A,
			'license' => OPENTHC_TEST_CLIENT_LICENSE_C
		]);
		$res = $this->assertValidResponse($res, 403);

		$this->assertMatchesRegularExpression('/CAO.125/', $res['meta']['note']);
	}

	/**
	 * Test v2024 authentication scheme
	 */
	function test_open_pass() : string
	{
		$client_service_pk = \OpenTHC\Config::get('openthc/cre/public');
		$client_service_sk = \OpenTHC\Config::get('openthc/cre/secret');
		$server_pk = $client_service_pk;

		$plain_data = json_encode([
			'pk' => $client_service_pk,
			'ts' => time(),
			'service' => OPENTHC_TEST_SERVICE_ID,
			'company' => OPENTHC_TEST_COMPANY_ID,
			'contact' => OPENTHC_TEST_CONTACT_ID,
			'license' => OPENTHC_TEST_LICENSE_ID,
		]);
		$crypt_box = \OpenTHC\Sodium::encrypt($plain_data, $client_service_sk, $server_pk);
		$crypt_box = \OpenTHC\Sodium::b64encode($crypt_box);
		$token = sprintf('%s/%s', $client_service_pk, $crypt_box);
		$bearer = sprintf('Bearer v2024/%s', $token);

		$res = $this->httpClient->post('/auth/open', [
			'headers' => [
				'Authorization' => $bearer,
			],
		]);
		$res = $this->assertValidResponse($res);
		// var_dump($res);

		$this->assertNotEmpty($res['data']['sid']);

		$sid = $res['data']['sid'];
		$this->assertMatchesRegularExpression('/\w{26,256}/', $sid);

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

}
