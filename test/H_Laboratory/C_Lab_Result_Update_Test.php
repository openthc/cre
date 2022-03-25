<?php
/**
 * Update Lab Results
 */

namespace Test\H_Laboratory;

class C_Lab_Result_Update_Test extends \Test\Base_Case
{
	function test_update_result()
	{
		$this->auth($_ENV['api-service-a'], $_ENV['api-company-c'], $_ENV['api-license-c']);

		$res = $this->httpClient->get('/lab/result');
		$res = $this->assertValidResponse($res);


		$this->assertIsArray($res['meta']);
		// $this->assertGreaterThan(1, count($res['data']));

		// Find Random

		// Update It

	}

}
