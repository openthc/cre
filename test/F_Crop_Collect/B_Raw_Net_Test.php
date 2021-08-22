<?php
/**
 * Collect Raw Materials, Net Materials
 */

namespace Test\F_Crop_Collect;

class B_Raw_Net_Test extends \Test\Base_Case
{
	protected function setUp() : void
	{
		parent::setUp();
		$this->auth($_ENV['api-service-a'], $_ENV['api-company-g0'], $_ENV['api-license-g0']);
	}

	function test_wet_dry_net_200()
	{
		$x = $this->find_random_crop(2);
		$pA = $x[0];
		$pB = $x[1];

		$this->assertNotEquals($pA['id'], $pB['id']);
		$this->assertNotEmpty($pA['variety_id']);
		$this->assertNotEmpty($pB['variety_id']);

		// Collect A
		$url = sprintf('/crop/%s/collect', $pA['id']);
		$arg = [
			'type' => 'raw',
			'qty' => 1234.56,
			'uom' => 'g',
		];
		$res = $this->_post($url, $arg);
		//
		$res = $this->assertValidResponse($res, 201);
		// Should Have Collect Information?
		$this->assertCount(2, $res);
		$this->assertNotEmpty($res['data']['id']); //

		$pcA = $res['data'];


		// Collect A-2
		$url = sprintf('/crop/%s/collect', $pA['id']);
		$arg = [
			'plant_collect_id' => $pcA['id'],
			'type' => 'raw',
			'qty' => 2345.67,
			'uom' => 'g',
		];
		$res = $this->_post($url, $arg);
		//
		$res = $this->assertValidResponse($res, 201);
		// Should Have Collect Information?
		$this->assertCount(2, $res);
		$this->assertNotEmpty($res['data']['id']); //
		$pcB = $res['data'];

		$this->assertEquals($pcA['id'], $pcB['id']);

		// Now this Plant Collect Group / Production Run is Together
		// And we can see it?
		$res = $this->httpClient->get('/crop-collect/' . $pcA['id']);
		$res = $this->assertValidResponse($res, 200);
		$this->assertCount(2, $res);
		$this->assertNotEmpty($res['data']['id']); //

		$pcC = $res['data'];
		$this->assertCount(12, $pcC);
		$this->assertCount(2, $pcC['collect_list']);


		// Commit this to Lock this Raw Weight and record Net
		// 25% Yield
		$raw = 1234.56 + 2345.67;
		$net = $raw * 0.25;

		$url = sprintf('/crop-collect/%s/commit', $pcA['id']);
		$arg = [
			// 'product_id' => '', // Use Default Product
			'variety_id' => $pA['variety_id'],
			// 'section_id' => '', // Use Default Section
			'qty' => $net,
		];
		$res = $this->_post($url, $arg);

		$res = $this->assertValidResponse($res, 201);
		$this->assertCount(2, $res);
		$this->assertIsArray($res['data']);

		$pcD = $res['data'];
		$this->assertCount(2, $pcD);
		$this->assertNotEmpty($pcD['plant_collect']['id']);
		$this->assertEquals($raw, $pcD['plant_collect']['raw']);
		$this->assertEquals($net, $pcD['plant_collect']['net']);

		$this->assertNotEmpty($pcD['lot']['id']);
		$this->assertEquals($net, $pcD['lot']['qty']);
		// $this->assertCount(2, $pcD['collect_list']);

	}
}
