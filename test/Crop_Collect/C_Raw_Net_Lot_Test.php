<?php
/**
 *
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\Crop_Collect;

class C_Raw_Net_Lot_Test extends \OpenTHC\CRE\Test\Base
{
	protected function setUp() : void
	{
		parent::setUp();
		$this->auth($_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'], $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'], $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A']);
	}

	function test_raw_net_inventory()
	{
		$x = $this->find_random_crop(2);
		$pA = $x[0];
		$pB = $x[1];

		$this->assertNotEquals($pA['id'], $pB['id']);
		$this->assertNotEmpty($pA['variety_id']);
		$this->assertNotEmpty($pB['variety_id']);

		// Collect Raw
		$res = $this->post_crop_collect([], $pA, 500, 'raw');
		$pcA = $res['data'];

		$res = $this->post_crop_collect($pcA, $pB, 500, 'raw');
		$pcB = $res['data'];

		$this->assertEquals($pcA['id'], $pcB['id']);

		// Collect Net
		$res = $this->post_crop_collect($pcA, $pA, 125, 'net');
		$pc2 = $res['data'];
		$this->assertEquals($pcA['id'], $pc2['id']);

		$res = $this->post_crop_collect($pcA, $pB, 125, 'net');
		$pc3 = $res['data'];
		$this->assertEquals($pcA['id'], $pc3['id']);

		// $res = $this->post_crop_collect($pcA, $pB, 125, 'net');
		// $pc3 = $res['data'];
		// $this->assertEquals($pcA['id'], $pc3['id']);

		// Get Collect Object
		$res = $this->httpClient->get('/crop-collect/' . $pcA['id']);
		$res = $this->assertValidResponse($res, 200);
		$this->assertCount(2, $res);

		$pcA = $res['data'];
		$this->assertCount(12, $pcA);
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
		$this->assertCount(2, $res);
		$this->assertIsArray($res['data']);

		$PC301 = $res['data'];
		$this->assertCount(2, $PC301);
		$this->assertNotEmpty($PC301['plant_collect']['id']);
		$this->assertEquals(1000, $PC301['plant_collect']['raw']);
		$this->assertEquals(500, $PC301['plant_collect']['net']);

		$this->assertNotEmpty($PC301['inventory']['id']);
		$this->assertEquals(250, $PC301['inventory']['qty']);

	}
}
