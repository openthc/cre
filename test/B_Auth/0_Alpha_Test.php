<?php
/**
 * Notes about the Auth module
 * The "service-key" cooresponds to a code that is a company object identifier
 * The "license-key" cooresponds to a code that is a license object identifier
 *
 * Licenses can belong to a company in a 1:M way
 * Companies can have different permissions to act on a license's object
 *
 */

namespace Test\Auth;

class Alpha extends \Test\Components\OpenTHC_Test_Case
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
		$this->assertValidResponse($res, 403);

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

		$this->assertIsArray($res);
		$this->assertCount(1, $res);
		$this->assertRegExp('/MAS#043/', $res['meta']['detail']);

	}

	function test_open_pass()
	{
		// TEST COMPANY A
		$res = $this->_post('/auth/open', [
			'service' => $_ENV['api-service-a'],
			'company' => $_ENV['api-company-g0'],
			'license' => $_ENV['api-license-g0']
		]);
		$res = $this->assertValidResponse($res);

		$this->assertIsArray($res);
		$this->assertCount(2, $res);
		$this->assertRegExp('/\w{26,256}/', $res['data']);

	}

	/**
	 * This test will get a 400 response because Company and License don't match
	 * @return [type] [description]
	 */
	function test_open_fail_company_license()
	{
		$res = $this->_post('/auth/open', [
			'service' => $_ENV['api-service-a'],
			'company' => $_ENV['api-company-g0'],
			'license' => $_ENV['api-license-p0']
		]);
		$res = $this->assertValidResponse($res, 403);

		$this->assertIsArray($res);
		$this->assertCount(1, $res);
		$this->assertRegExp('/MAS#077/', $res['meta']['detail']);

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
	// 			'client-key' => $_ENV['api-client-key'],
	// 			'vendor-key' => $_ENV['api-vendor-key'],
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
	// 	// $this->assertRegEx('success', /\w{64,256}/');
	//
	// }
}
