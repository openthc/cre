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

namespace OpenTHC\CRE\Test\B_Auth;

class A_Alpha_Test extends \OpenTHC\CRE\Test\Base
{
	public function test_auth()
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
		$this->assertValidResponse($res, 400);

		$res = $this->httpClient->get('/auth/ping');
		$this->assertValidResponse($res, 200);

		$res = $this->httpClient->post('/auth/ping');
		$this->assertValidResponse($res, 405);

		$res = $this->httpClient->get('/auth/shut');
		$this->assertValidResponse($res, 405);

		$res = $this->httpClient->post('/auth/shut');
		$this->assertValidResponse($res, 200);

	}

	public function test_open_fail()
	{
		// Fail
		$res = $this->_post('/auth/open', [
			'service' => 'garbage-data',
			'company' => 'garbage-data',
			'license' => 'garbage-data',
		]);

		$res = $this->assertValidResponse($res, 403);

		$this->assertMatchesRegularExpression('/CAO.098/', $res['meta']['note']);
	}

	function test_open_pass()
	{
		// TEST COMPANY A
		$arg = [
			'service' => OPENTHC_TEST_SERVICE_ID,
			'company' => OPENTHC_TEST_COMPANY_ID,
			'contact' => OPENTHC_TEST_CONTACT_ID,
			'license' => OPENTHC_TEST_LICENSE_ID,
		];
		$res = $this->_post('/auth/open', $arg);
		$res = $this->assertValidResponse($res);

		$this->assertMatchesRegularExpression('/\w{26,256}/', $res['data']);
	}

	/**
	 * This test will get a 400 response because Company and License don't match
	 */
	function test_open_fail_company_license()
	{
		$res = $this->_post('/auth/open', [
			'service' => OPENTHC_TEST_CLIENT_SERVICE_A,
			'company' => OPENTHC_TEST_CLIENT_COMPANY_A,
			'license' => OPENTHC_TEST_CLIENT_LICENSE_C
		]);
		$res = $this->assertValidResponse($res, 403);

		$this->assertMatchesRegularExpression('/CAO.125/', $res['meta']['note']);
	}

	// public function testAuthPlain()
	// {
	// 	$api = $this->_api();
	//
	// 	$res = $api->post('/auth/open', array(
	// 		'form_params' => array(
	// 			'business' => '123456789',
	// 			'username' => 'test@openthc.org',
	// 			'password' => 'password',
	// 			'client-key' => api-client-key,
	// 			'vendor-key' => api-vendor-key,
	// 		)
	// 	));
	//
	// 	$hsc = $res->getStatusCode();
	//
	// 	$this->assertEquals(400, $res->getStatusCode());
	// 	$this->assertEquals('application/json;charset=utf-8', $res->getHeaderLine('Content-Type'));
	//
	// 	$res = json_decode($res->getBody(), true);
	//
	// 	// $this->assertMatchesRegularExpression('success', /\w{64,256}/');
	//
	// }

	/**
	 * Test v2024 authentication scheme
	 */
	function test_auth_open()
	{
		$client_service_pk = \OpenTHC\Config::get('openthc/cre/public');
		$client_service_sk = \OpenTHC\Config::get('openthc/cre/secret');
		$server_pk = $client_service_pk;

		$plain_data = json_encode([
			'pk' => $client_service_pk,
			'ts' => time(),
		]);
		$crypt_box = \OpenTHC\Sodium::encrypt($plain_data, $client_service_sk, $server_pk);
		$crypt_box = \OpenTHC\Sodium::b64encode($crypt_box);
		$token = sprintf('%s/%s', $client_service_pk, $crypt_box);
		$bearer = sprintf('Bearer v2024/%s', $token);

		$res = $this->httpClient->post('/auth/open', [
			'headers' => [
				'Authorization' => $bearer,
				'OpenTHC-Contact' => OPENTHC_TEST_CLIENT_CONTACT_0,
				'OpenTHC-Company' => OPENTHC_TEST_CLIENT_COMPANY_0,
				'OpenTHC-License' => OPENTHC_TEST_CLIENT_LICENSE_0,
			],
		]);

		// var_dump(($res->getBody()->getContents()));
		$this->assertEquals(200, $res->getStatusCode());
		// $this->assertValidResponse($res);
		// $this->assertNotEmpty($res['data']['sid']);
		// $this->assertMatchesRegularExpression('/\w{26,256}/', $res['data']['sid']);

		// $this->assertNotEmpty
	}


	/**
	 * Test the full v2024 authentication handshake
	 */
	function test_auth_ping()
	{
		$client_service_pk = \OpenTHC\Config::get('openthc/cre/public');
		$client_service_sk = \OpenTHC\Config::get('openthc/cre/secret');
		$server_pk = $client_service_pk;

		$plain_data = json_encode([
			'pk' => $client_service_pk,
			'ts' => time(),
		]);
		$crypt_box = \OpenTHC\Sodium::encrypt($plain_data, $client_service_sk, $server_pk);
		$crypt_box = \OpenTHC\Sodium::b64encode($crypt_box);
		$token = sprintf('%s/%s', $client_service_pk, $crypt_box);
		$bearer = sprintf('Bearer v2024/%s', $token);

		$res = $this->httpClient->post('/auth/open', [
			'headers' => [
				'Authorization' => $bearer,
				'OpenTHC-Contact' => OPENTHC_TEST_CLIENT_CONTACT_0,
				'OpenTHC-Company' => OPENTHC_TEST_CLIENT_COMPANY_0,
				'OpenTHC-License' => OPENTHC_TEST_CLIENT_LICENSE_0,
			],
		]);

		$body = $res->getBody()->getContents();
		$body = json_decode($body);
		$this->assertEquals(200, $res->getStatusCode());
		$this->assertNotEmpty($body->data->sid);
		$this->assertMatchesRegularExpression('/\w{26,256}/', $body->data->sid);

		$token0 = $body->data->sid;
		$bearer0 = sprintf('Bearer v2024/%s', $token0);
		$res0 = $this->httpClient->get('/auth/ping', [
			'headers' => [
				'Authorization' => $bearer0,
			],
		]);
		$this->assertEquals(200, $res0->getStatusCode());
	}

}
