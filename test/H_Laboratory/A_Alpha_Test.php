<?php
/**
 * Tests the inventory laboratory section of the API
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\H_Laboratory;

class A_Alpha_Test extends \OpenTHC\CRE\Test\Base_Case
{
	function test_lab()
	{
		$this->auth($_ENV['api-service-a'], $_ENV['api-company-a'], $_ENV['api-license-a']);

		$res = $this->httpClient->get('/lab');
		$res = $this->assertValidResponse($res);

		$res = $this->httpClient->get('/lab/metric');
		$res = $this->assertValidResponse($res);

		$res = $this->httpClient->get('/lab/sample');
		$res = $this->assertValidResponse($res);

		$res = $this->httpClient->get('/lab/result');
		$res = $this->assertValidResponse($res);

	}

}
