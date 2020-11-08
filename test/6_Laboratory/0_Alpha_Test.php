<?php
/**
 * Tests the inventory laboratory section of the API
 */

namespace Test\Laboratory;

class Alpha extends \Test\Components\OpenTHC_Test_Case
{
	function test_lab()
	{
		$this->auth($_ENV['api-service-a'], $_ENV['api-company-g0'], $_ENV['api-license-g0']);

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
