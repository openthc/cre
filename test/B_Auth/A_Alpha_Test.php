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
}
