<?php
/**
 * Collect Raw Materials and Lot Directly
 */

namespace Test\Crop_Collect;

class Raw_Lot extends \Test\Components\OpenTHC_Test_Case
{
	protected function setUp() : void
	{
		parent::setUp();
		$this->auth($_ENV['api-service-a'], $_ENV['api-company-g0'], $_ENV['api-license-g0']);
	}

	function test_raw_lot()
	{
		$x = $this->find_random_plant(2);
		$pA = $x[0];
		$pB = $x[1];

		$this->assertNotEquals($pA['id'], $pB['id']);
		$this->assertNotEmpty($pA['strain_id']);
		$this->assertNotEmpty($pB['strain_id']);

		// Collect P0
		$pcA = $this->_plant_collect([], $pA, 500);
		$pcB = $this->_plant_collect($pcA, $pB, 500);

		// $url = sprintf('/plant/%s/collect', $pA['id']);
		// $arg = [
		// 	'plant_collect_id' => $pcA['id'],
		// 	'type' => 'raw',
		// 	'qty' => 2345.67,
		// 	'uom' => 'g',
		// ];
		// $res = $this->_post($url, $arg);
		// //
		// $res = $this->assertValidResponse($res, 201);
		// // Should Have Collect Information?
		// $this->assertCount(2, $res);
		// $this->assertNotEmpty($res['data']['id']); //
		// $pcB = $res['data'];

		$this->assertEquals($pcA['id'], $pcB['id']);

		// Now this Plant Collect Group / Production Run is Together
		// And we can see it?
		$res = $this->httpClient->get('/plant-collect/' . $pcA['id']);
		$res = $this->assertValidResponse($res, 200);
		$this->assertCount(2, $res);
		$this->assertNotEmpty($res['data']['id']); //

		$pcC = $res['data'];
		$this->assertCount(13, $pcC);
		$this->assertCount(2, $pcC['collect_list']);

		// Commit this to Lock this Raw Weight and record Net
		// 25% Yield
		$net = $pcC['raw'] * 0.25;


		$PR0 = $this->find_random_product();
		$url = sprintf('/plant-collect/%s/commit', $pcA['id']);
		$arg = [
			'product_id' => $PR0['id'],
			'strain_id' => $pA['strain_id'],
			'qty' => $net,
		];
		$res = $this->_post($url, $arg);
		$res = $this->assertValidResponse($res, 201);
		print_r($res);
		$this->assertCount(2, $res);
		$this->assertIsArray($res['data']);

		$pcD = $res['data'];
		$this->assertCount(2, $pcD);
		$this->assertNotEmpty($pcD['plant_collect']['id']);
		$this->assertEquals($pcC['raw'], $pcD['plant_collect']['raw']);
		$this->assertEquals($pcC['net'], $pcD['plant_collect']['net']);

		$this->assertNotEmpty($pcD['lot']['id']);
		$this->assertEquals($net, $pcD['lot']['qty']);
		// $this->assertCount(2, $pcD['collect_list']);

	}
}
