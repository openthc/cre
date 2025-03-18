<?php
/**
 *
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\F_Crop_Collect;

class A_Alpha_Test extends \OpenTHC\CRE\Test\Base
{
	protected function setUp() : void
	{
		parent::setUp();
		$this->auth($_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'], $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'], $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A']);
	}

	function test_public()
	{
		$res = $this->httpClient->post('/auth/shut');
		$res = $this->assertValidResponse($res, 200);

		$res = $this->httpClient->get('/crop');
		$res = $this->assertValidResponse($res, 403);

		$res = $this->httpClient->get('/crop/four_zero_four');
		$res = $this->assertValidResponse($res, 403);

	}

	public function test_collect_one_wet()
	{
		$p = $this->find_random_crop();
		$url = sprintf('/crop/%s/collect', $p['id']);
		$arg = [
			'type' => 'wet',
			'qty' => 1234.56,
			'uom' => 'g',
		];
		$res = $this->_post($url, $arg);
		$res = $this->assertValidResponse($res, 201);

		$this->assertIsArray($res['meta']);
		$this->assertIsArray($res['data']);
		$this->assertCount(4, $res['data']);

		$p = $res['data'];
		$this->_data_stash_put($p);
	}


	public function test_collect_all_wet()
	{
		$p = $this->find_random_crop();
		$this->assertNotempty($p['id']);

		$url = sprintf('/crop/%s/collect', $p['id']);
		$arg = [
			'type' => 'wet',
			'qty' => 1234.56,
			'uom' => 'g',
		];
		$res = $this->_post($url, $arg);
		$this->assertValidResponse($res, 201);

		$p = $this->find_random_crop();
		$this->assertNotempty($p['id']);

		$url = sprintf('/crop/%s/collect', $p['id']);
		$arg = [
			'type' => 'wet',
			'qty' => 1234.56,
			'uom' => 'g',
		];
		$res = $this->_post($url, $arg);
		$this->assertValidResponse($res, 201);

	}

	public function test_collect_one_dry()
	{
		$p = $this->find_random_crop();
		$this->assertNotempty($p['id']);

		$url = sprintf('/crop/%s/collect', $p['id']);
		$arg = [
			'type' => 'dry',
			'qty' => 1234.56,
			'uom' => 'g',
		];
		$res = $this->_post($url, $arg);
		$this->assertValidResponse($res, 201);

	}

	/**
	 * Here is when Plant Matter, with a Plant is closed to Inventory
	 */
	public function test_collect_one_net()
	{
		$p = $this->find_random_crop();
		$this->assertNotempty($p['id']);

		$url = sprintf('/crop/%s/collect', $p['id']);
		$arg = [
			'type' => 'net',
			'qty' => 1234.56,
			'uom' => 'g',
		];
		$res = $this->_post($url, $arg);
		$res = $this->assertValidResponse($res, 201);

		$this->assertIsArray($res['meta']);
		$this->assertIsArray($res['data']);
		$this->assertCount(4, $res['data']);

		$pc = $res['data'];

	}

	/**
	 * Here is when Plant Matter, with a Plant is closed to Inventory
	 */
	public function test_collect_all_new()
	{
		$p = $this->find_random_crop();
		$this->assertNotempty($p['id']);

		$url = sprintf('/crop/%s/collect', $p['id']);
		$arg = [
			'type' => 'net',
			'qty' => 1234.56,
			'uom' => 'g',
		];
		$res = $this->_post($url, $arg);
		$this->assertValidResponse($res, 201);

		$url = sprintf('/crop/%s/collect', $p['id']);
		$arg = [
			'inventory_id' => $p['id'],
			'type' => 'net',
			'qty' => 12.34,
			'uom' => 'g',
		];
		$res = $this->_post($url, $arg);
		$res = $this->assertValidResponse($res, 201);

		$pc = $res['data'];

	}


}
