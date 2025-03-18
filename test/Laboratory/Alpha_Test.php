<?php
/**
 * Tests the inventory laboratory section of the API
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\Laboratory;

class Alpha_Test extends \OpenTHC\CRE\Test\Base
{
	function test_lab()
	{
		$this->auth($_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'], $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'], $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A']);

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
