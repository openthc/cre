<?php
/**
 * Make a bunch of different Product of different Types
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\C_Core;

class D_Product_Type_Test extends \OpenTHC\CRE\Test\Base_Case
{

	protected function setUp() : void
	{
		parent::setUp();
		$this->auth($_ENV['api-service-a'], $_ENV['api-company-a'], $_ENV['api-license-a']);
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

}
