<?php
/**
 * Collect Raw Materials and Lot Directly
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\F_Crop_Collect;

class B_Raw_Lot_Test extends \OpenTHC\CRE\Test\Base
{
	protected function setUp() : void
	{
		parent::setUp();
		$this->auth(OPENTHC_TEST_CLIENT_SERVICE_A, OPENTHC_TEST_CLIENT_COMPANY_A, OPENTHC_TEST_CLIENT_LICENSE_A);
	}

	function test_raw_inventory()
	{
		$x = $this->find_random_crop(2);
		$pA = $x[0];
		$pB = $x[1];

		$this->assertNotEquals($pA['id'], $pB['id']);
		$this->assertNotEmpty($pA['variety_id']);
		$this->assertNotEmpty($pB['variety_id']);

		// Collect P0
		$res = $this->post_crop_collect([], $pA, 500, 'raw');
		$pcA = $res['data'];

		$res = $this->post_crop_collect($pcA, $pB, 500, 'raw');
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
		$net = $pcC['raw'] * 0.25;

		$PR0 = $this->find_random_product();
		$url = sprintf('/crop-collect/%s/commit', $pcA['id']);
		$arg = [
			'product_id' => $PR0['id'],
			'variety_id' => $pA['variety_id'],
			'qty' => $net,
		];
		$res = $this->_post($url, $arg);
		$res = $this->assertValidResponse($res, 201);
		$this->assertCount(2, $res);
		$this->assertIsArray($res['data']);

		$pcD = $res['data'];
		$this->assertCount(2, $pcD);
		$this->assertNotEmpty($pcD['plant_collect']['id']);
		$this->assertEquals($pcC['raw'], $pcD['plant_collect']['raw']);
		$this->assertEquals($net, $pcD['plant_collect']['net']);

		$this->assertNotEmpty($pcD['inventory']['id']);
		$this->assertEquals($net, $pcD['inventory']['qty']);
		// $this->assertCount(2, $pcD['collect_list']);

	}
}
