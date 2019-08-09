<?php
/**
 * Test the Update of an Incoming Transfer
 */

namespace Test\B2B;

class Update_Incoming extends \Test\Components\OpenTHC_Test_Case
{
	protected function setUp() : void
	{
		parent::setUp();
		$this->auth($_ENV['api-program-c'], $_ENV['api-company-r0'], $_ENV['api-license-r0']);
	}

	/**
	 * Test Update Proper
	 */
	function x_test_updateProper()
	{
		$res = $this->httpClient->get('/transfer/incoming');
		$res = $this->assertValidResponse($res, 200, __METHOD__);

		$this->assertIsArray($res['meta']);
		$this->assertIsArray($res['data']);

		$s0 = $res['data'][0];
		$this->assertNotEmpty($s0['id']);
		$this->assertEquals('UNITTEST Strain CREATE', $s0['name']);


		$res = $this->post('/transfer/{guid}/accept', array(
			'something',
		));

	}


	function test_accept_with_invalid_lot()
	{
		// Attempt to Update the Wrong Lot
		// Protect against LW4821 Origin Lot Scramble by Target
		$res = $this->httpClient->get('/transfer/incoming');
		$res = $this->assertValidResponse($res, 200);

		// Accept
		$res = $this->_post('/transfer/{id}/accept', [
			'something',
		]);

	}

}
