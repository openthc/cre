<?php
/**
 *
 */

namespace Test\H_Laboratory;

class B_Lab_Result_Create_Test extends \Test\Components\OpenTHC_Test_Case
{
	protected function setUp() : void
	{
		parent::setUp();

	}

	function test_create_sample()
	{
		$this->auth($_ENV['api-service-a'], $_ENV['api-company-g0'], $_ENV['api-license-g0']);

		$res = $this->httpClient->get('/lot');
		$res = $this->assertValidResponse($res);
		$this->assertIsArray($res['meta']);
		$this->assertGreaterThan(1, count($res['data']));
		$i = \array_rand($res['data']);
		$l0 = $res['data'][$i];


		// Now Send as a Sample to a Laboratory through b2b_transfer
		$res = $this->_post('/b2b', [
			'license_id_target' => $_ENV['api-license-l0'],
			'depart' => date(\DateTime::RFC3339, time() + 3600),
			'arrive' => date(\DateTime::RFC3339, time() + 86400),
			'method' => 'deliver', // deliver, pick-up, carrier
			'contact' => [
				'id' => $_ENV['api-contact-g0'],
			]
		]);

		$res = $this->assertValidResponse($res, 201);
		$T0 = $res['data'];

		$l = $this->find_random_lot();

		$url = sprintf('/b2b/%s', $T0['id']);
		$res = $this->_post($url, [
			'lot_id' => $l['id'],
			'qty' => 10,
		]);
		$res = $this->assertValidResponse($res, 201);

		// Examine Pending Transfer
		$res = $this->httpClient->get(sprintf('/b2b/%s', $T0['id']));
		$res = $this->assertValidResponse($res, 200);
		$T1 = $res['data'];
		$this->assertIsArray($T1);
		$this->assertCount(6, $T1);
		$this->assertNotEmpty($T1['id']);
		$this->assertIsArray($T1['line_item_list']);
		$this->assertCount(1, $T1['line_item_list']);

		// Commit Transfer
		$res = $this->_post(sprintf('/b2b/%s', $T0['id']), [ 'status' => 'commit' ]);
		$res = $this->assertValidResponse($res, 202);
		$T2 = $res['data'];
		// print_r($T2);
		$this->assertIsArray($T2);
		$this->assertCount(7, $T2);
		$this->assertNotEmpty($T2['id']);
		$this->assertEquals(307, $T2['stat']);
		$this->assertEquals(307, $T2['transfer_outgoing_stat']);

	}


	function test_create_result()
	{
		$this->auth($_ENV['api-service-a'], $_ENV['api-company-l0'], $_ENV['api-license-l0']);


		$res = $this->httpClient->get('/b2b/incoming');
		$res = $this->assertValidResponse($res);
		// var_dump($res);

		$this->assertIsArray($res['meta']);
		$this->assertGreaterThan(1, count($res['data']));

		// $res = $this->httpClient->get('/lot');
		// $res = $this->assertValidResponse($res);
		// print_r($res);
		// $this->assertIsArray($res['meta']);
		// $this->assertGreaterThan(1, count($res['data']));
		// $i = \array_rand($res['data']);
		// $l0 = $res['data'][$i];

	}

}
