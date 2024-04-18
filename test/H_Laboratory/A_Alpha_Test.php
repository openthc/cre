<?php
/**
 * Tests the inventory laboratory section of the API
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\H_Laboratory;

class A_Alpha_Test extends \OpenTHC\CRE\Test\Base
{
	function test_lab()
	{
		$this->auth(OPENTHC_TEST_CLIENT_SERVICE_A, OPENTHC_TEST_CLIENT_COMPANY_A, OPENTHC_TEST_CLIENT_LICENSE_A);

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
