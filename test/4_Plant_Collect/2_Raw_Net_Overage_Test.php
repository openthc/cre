<?php
/**
 *
 */

namespace Test\Plant_Collect;

class Raw_Net_Overage extends \Test\Components\OpenTHC_Test_Case
{
	protected function setUp() : void
	{
		parent::setUp();
		$this->auth($_ENV['api-service-a'], $_ENV['api-company-g0'], $_ENV['api-license-g0']);
	}

	function test_too_much_net()
	{
		$x = $this->find_random_plant(2);
		$pA = $x[0];
		$pB = $x[1];

		$this->assertNotEquals($pA['id'], $pB['id']);
		$this->assertNotEmpty($pA['strain_id']);
		$this->assertNotEmpty($pB['strain_id']);

		// Collect A
		$url = sprintf('/plant/%s/collect', $pA['id']);
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
		// print_r($pcA);
		$this->assertIsArray($pcA['collect_item']);
		$this->assertCount(7, $pcA['collect_item']);
		$this->assertEquals(1234.56, $pcA['collect_item']['qty']);

		// Collect B
		$url = sprintf('/plant/%s/collect', $pA['id']);
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
		// print_r($pcB);
		$this->assertIsArray($pcB['collect_item']);
		$this->assertCount(7, $pcB['collect_item']);
		$this->assertEquals(2345.67, $pcB['collect_item']['qty']);

		$this->assertEquals($pcA['id'], $pcB['id']);

		$raw = 1234.56 + 2345.67;

		// Now this Plant Collect Group / Production Run is Together

		// And we can see it?
		$res = $this->httpClient->get('/plant-collect/' . $pcA['id']);
		$res = $this->assertValidResponse($res, 200);
		$this->assertCount(2, $res);
		$this->assertNotEmpty($res['data']['id']); //
		$pcC = $res['data'];

		$this->assertCount(13, $pcC);
		$this->assertEquals($raw, $pcC['raw']);
		$this->assertCount(2, $pcC['collect_list']);

		// Commit this to Lock this Raw Weight and record Net
		$net = $raw + 1;

		$url = sprintf('/plant-collect/%s/commit', $pcA['id']);
		$arg = [
			'strain_id' => $pA['strain_id'],
			'net' => $net,
		];
		$res = $this->_post($url, $arg);

		$res = $this->assertValidResponse($res, 413);
		$this->assertCount(2, $res);
		$this->assertIsArray($res['meta']);
		$this->assertNotEmpty($res['meta']['detail']);
		$this->assertRegExp('/net too large/i', $res['meta']['detail']);
		$this->assertIsArray($res['data']);
		$this->assertEquals($raw, $res['data']['raw']);

	}
}
