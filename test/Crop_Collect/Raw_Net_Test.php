<?php
/**
 * Collect Raw Materials, Net Materials
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\Crop_Collect;

class Raw_Net_Test extends \OpenTHC\CRE\Test\Base
{
	protected function setUp() : void
	{
		parent::setUp();
		$this->auth($_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'], $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'], $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A']);
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

		$this->assertNotEmpty($pcD['inventory']['id']);
		$this->assertEquals($net, $pcD['inventory']['qty']);
		// $this->assertCount(2, $pcD['collect_list']);

	}
}
