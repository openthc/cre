<?php
/**
 * Accept Incoming Transfer
 */

namespace Test\B2B;

class Accept_Incoming extends \Test\Components\OpenTHC_Test_Case
{
	protected $_url_path = '/transfer';
	protected $_tmp_file = '/tmp/unit-test-transfer.json';

	function x_test_accept_g_to_p()
	{
		$this->auth($_ENV['api-program-a'], $_ENV['api-company-p0'], $_ENV['api-license-p0']);

		$res = $this->httpClient->get('/transfer/incoming');
		$res = $this->assertValidResponse($res);
		$this->assertIsArray($res['meta']);
		$this->assertGreaterThan(1, count($res['data']));

		$t0 = $res['data'][0];

		// Patch?
		$res = $this->_post('/transfer/' . $t0['id'], [
			'status' => 'accept',
		]);
		$res = $this->assertValidResponse($res, 202);
		$this->assertIsArray($res['meta']);
		$this->assertIsArray($res['data']);

		$t1 = $res['data'];
		// print_r($t1);

		$this->assertEquals(307, $t1['b2b_outgoing_stat']);
		$this->assertEquals(202, $t1['b2b_incoming_stat']);

	}

	function test_accept_p_to_r()
	{
		$this->auth($_ENV['api-program-a'], $_ENV['api-company-r0'], $_ENV['api-license-r0']);

		$Z = $this->find_random_zone();

		$res = $this->httpClient->get('/transfer/incoming');
		$res = $this->assertValidResponse($res);
		$this->assertIsArray($res['meta']);
		// var_dump($res['meta']);
		$this->assertGreaterThanOrEqual(1, count($res['data']));

		$t0 = $res['data'][0];
		// var_dump($t0);

		// Post Empty Array to Accept ALL, FULL
		$res = $this->_post('/transfer/' . $t0['id'] . '/accept', [
			'zone_id' => $Z['id'],
		]);
		$res = $this->assertValidResponse($res);
		var_dump($res);

		$this->assertIsArray($res['meta']);
		$this->assertIsArray($res['data']);
		$this->assertIsArray($res['data']['transfer']);
		$this->assertIsArray($res['data']['transfer_item']);

		$t1 = $res['data']['transfer'];
		// print_r($t1);

		$this->assertEquals(307, $t1['b2b_outgoing_stat']);
		$this->assertEquals(202, $t1['b2b_incoming_stat']);

	}

}
