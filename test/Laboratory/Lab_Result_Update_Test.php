<?php
/**
 * Update Lab Results
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\Laboratory;

class Lab_Result_Update_Test extends \OpenTHC\CRE\Test\Base
{
	function test_update_result()
	{
		$this->auth($_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'], $_ENV['OPENTHC_TEST_CLIENT_COMPANY_C'], $_ENV['OPENTHC_TEST_CLIENT_LICENSE_C']);

		$res = $this->httpClient->get('/lab/result');
		$res = $this->assertValidResponse($res);


		$this->assertIsArray($res['meta']);
		// $this->assertGreaterThan(1, count($res['data']));

		// Find Random

		// Update It

	}

}
