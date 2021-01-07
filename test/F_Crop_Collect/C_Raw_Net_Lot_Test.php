<?php
/**
 *
 */

namespace Test\F_Crop_Collect;

class C_Raw_Net_Lot_Test extends \Test\Components\OpenTHC_Test_Case
{
	protected function setUp() : void
	{
		parent::setUp();
		$this->auth($_ENV['api-service-a'], $_ENV['api-company-g0'], $_ENV['api-license-g0']);
	}

	function test_raw_net_lot()
	{
		$x = $this->find_random_plant(2);
		$pA = $x[0];
		$pB = $x[1];

		$this->assertNotEquals($pA['id'], $pB['id']);
		$this->assertNotEmpty($pA['variety_id']);
		$this->assertNotEmpty($pB['variety_id']);

		// Collect Raw
		$pcA = $this->_plant_collect([], $pA, 500);
		$pcB = $this->_plant_collect($pcA, $pB, 500);
		$this->assertEquals($pcA['id'], $pcB['id']);

		// Collect Net
		$pc2 = $this->_plant_collect($pcA, $pA, 125, 'net');
		$this->assertEquals($pcA['id'], $pc2['id']);
		$pc3 = $this->_plant_collect($pcA, $pB, 125, 'net');
		$this->assertEquals($pcA['id'], $pc3['id']);

		// $pc3 = $this->_plant_collect($pcA, $pB, 125, 'net');
		// $this->assertEquals($pcA['id'], $pc3['id']);

		// Get Collect Object
		$res = $this->httpClient->get('/crop-collect/' . $pcA['id']);
		$res = $this->assertValidResponse($res, 200);
		$this->assertCount(2, $res);

		$pcA = $res['data'];
		$this->assertCount(13, $pcA);
		$this->assertCount(4, $pcA['collect_list']);
		$this->assertEquals(1000, $pcA['raw']);
		$this->assertEquals(250, $pcA['net']);


		$PR0 = $this->find_random_product();
		$url = sprintf('/crop-collect/%s/commit', $pcA['id']);
		$arg = [
			'product_id' => $PR0['id'],
			'variety_id' => $pA['variety_id'],
			'qty' => 250,
		];
		$res = $this->_post($url, $arg);
		$res = $this->assertValidResponse($res, 201);
		print_r($res);
		$this->assertCount(2, $res);
		$this->assertIsArray($res['data']);

		$PC301 = $res['data'];
		$this->assertCount(2, $PC301);
		$this->assertNotEmpty($PC301['plant_collect']['id']);
		$this->assertEquals($pcA['raw'], $PC301['plant_collect']['raw']);
		$this->assertEquals($pcA['net'], $PC301['plant_collect']['net']);

		$this->assertNotEmpty($PC301['lot']['id']);
		$this->assertEquals(250, $PC301['lot']['qty']);

	}
}
