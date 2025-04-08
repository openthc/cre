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
		$this->httpClient = $this->makeHTTPClient([
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_C'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_C'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_C'],
		]);

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
