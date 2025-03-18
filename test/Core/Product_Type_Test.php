<?php
/**
 * Make a bunch of different Product of different Types
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\C_Core;

class D_Product_Type_Test extends \OpenTHC\CRE\Test\Base
{
	protected function setUp() : void
	{
		parent::setUp();
		$this->httpClient = $this->makeHTTPClient([
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_A'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A'],
		]);
	}

	/*
	 *
	 */
	function test_public_access()
	{
		$res = $this->httpClient->get('/product/type');
		$this->assertValidResponse($res, 200);

		$res = $this->httpClient->get('/product/type/four_zero_four');
		$this->assertValidResponse($res, 404);

		$res = $this->httpClient->get('/product/type/1');
		$this->assertValidResponse($res, 404);

		$res = $this->httpClient->get('/product/type?q=1');
		$res = $this->assertValidResponse($res, 200);

	}

	/*
	 *
	 */
	function test_search()
	{
		$res = $this->httpClient->get('/product/type');
		$res = $this->assertValidResponse($res, 200);
	}

	/**
	 * Only Authorized can Create Product Type
	 */
	function x_test_create()
	{
		$res = $this->httpClient->post('/product/type', [ 'form_params' => [
			'name' => 'Budder',
			'type' => '018NY6XC00PTACC942KY9DCERR',
		]]);

		$res = $this->assertValidResponse($res, 401);
		var_dump($res);
	}

	/**
	 * Only Authorized can Create Product Type
	 */
	function test_create_as_root()
	{
		$httpClient = $this->makeHTTPClient();
		$res = $httpClient->post('/product/type', [ 'form_params' => [
			'name' => 'Budder',
			'type' => '018NY6XC00PTACC942KY9DCERR',
		]]);

		// $res = $this->assertValidResponse($res, 201);
		// var_dump($res);
	}

}
