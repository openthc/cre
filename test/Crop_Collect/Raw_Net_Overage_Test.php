<?php
/**
 *
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\Crop_Collect;

class Raw_Net_Overage_Test extends \OpenTHC\CRE\Test\Base
{
	protected function setUp() : void
	{
		parent::setUp();
		$this->httpClient = $this->makeHTTPClient([
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_A'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A'],
		]);
	}

	function test_too_much_net()
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
		$res = $this->assertValidResponse($res, 201);
		$this->assertCount(2, $res);
		$this->assertNotEmpty($res['data']['id']);
		$pcA = $res['data'];
		$this->assertIsArray($pcA['collect_item']);
		$this->assertCount(7, $pcA['collect_item']);
		$this->assertEquals(1234.56, $pcA['collect_item']['qty']);

		// Collect B
		$url = sprintf('/crop/%s/collect', $pA['id']);
		$arg = [
			'plant_collect_id' => $pcA['id'],
			'type' => 'raw',
			'qty' => 2345.67,
			'uom' => 'g',
		];
		$res = $this->_post($url, $arg);
		$res = $this->assertValidResponse($res, 201);
		$this->assertCount(2, $res);
		$this->assertNotEmpty($res['data']['id']); //
		$pcB = $res['data'];
		$this->assertIsArray($pcB['collect_item']);
		$this->assertCount(7, $pcB['collect_item']);
		$this->assertEquals(2345.67, $pcB['collect_item']['qty']);

		$this->assertEquals($pcA['id'], $pcB['id']);

		$raw = 1234.56 + 2345.67;

		// Now this Plant Collect Group / Production Run is Together

		// And we can see it?
		$res = $this->httpClient->get('/crop-collect/' . $pcA['id']);
		$res = $this->assertValidResponse($res, 200);
		$this->assertCount(2, $res);
		$this->assertNotEmpty($res['data']['id']); //
		$pcC = $res['data'];

		$this->assertCount(12, $pcC);
		$this->assertEquals($raw, $pcC['raw']);
		$this->assertCount(2, $pcC['collect_list']);

		// Commit this to Lock this Raw Weight and record Net
		$net = $raw + 1;

		$url = sprintf('/crop-collect/%s/commit', $pcA['id']);
		$arg = [
			'variety_id' => $pA['variety_id'],
			'qty' => $net,
		];
		$res = $this->_post($url, $arg);

		$res = $this->assertValidResponse($res, 413);
		$this->assertCount(2, $res);
		$this->assertIsArray($res['meta']);
		$this->assertNotEmpty($res['meta']['note']);
		$this->assertMatchesRegularExpression('/net too large/i', $res['meta']['note']);
		$this->assertIsArray($res['data']);
		$this->assertEquals($raw, $res['data']['raw']);

	}
}
