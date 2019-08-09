<?php
/**
 *
 */

namespace Test\Plant_Collect;

class Alpha extends \Test\Components\OpenTHC_Test_Case
{
	protected function setUp() : void
	{
		parent::setUp();
		$this->auth($_ENV['api-program-a'], $_ENV['api-company-g0'], $_ENV['api-license-g0']);
	}

	function x_test_public()
	{
		$this->auth('foo', 'bar' , 'baz');
		$res = $this->httpClient->get('/plant');
		$res = $this->assertValidResponse($res, 403, __METHOD__);

		$res = $this->httpClient->get('/plant/four_zero_four/collect');
		$res = $this->assertValidResponse($res, 403);

	}

	public function test_collect_one_wet()
	{
		$obj = $this->find_random_plant();
		$url = sprintf('/plant/%s/collect', $obj['id']);
		$arg = [
			'type' => 'wet',
			'qty' => 12.34,
			'uom' => 'g',
		];
		$res = $this->_post($url, $arg);
		$res = $this->assertValidResponse($res, 201);

		$this->assertIsArray($res['meta']);
		$this->assertIsArray($res['data']);
		$this->assertCount(2, $res['data']);

		$obj = $res['data'];
		$this->_data_stash_put($obj);
	}


	public function test_collect_all_wet()
	{
		$obj = $this->find_random_plant();
		$this->assertNotempty($obj['id']);

		$url = sprintf('/plant/%s/collect', $obj['id']);
		$arg = [
			'type' => 'wet',
			'qty' => 12.34,
			'uom' => 'g',
		];
		$res = $this->_post($url, $arg);
		$this->assertValidResponse($res, 201);

		$obj = $this->find_random_plant();
		$this->assertNotempty($obj['id']);

		$url = sprintf('/plant/%s/collect', $obj['id']);
		$arg = [
			'type' => 'wet',
			'qty' => 12.34,
			'uom' => 'g',
		];
		$res = $this->_post($url, $arg);
		$this->assertValidResponse($res, 201);

	}

	public function test_collect_one_dry()
	{
		// $obj = $this->find_random_plant();
		$obj = $this->_data_stash_get();
		$this->assertNotempty($obj['plant_id']);

		$url = sprintf('/plant/%s/collect', $obj['plant_id']);
		$arg = [
			'type' => 'dry',
			'qty' => 12.34,
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
		// $obj = $this->find_random_plant();
		$obj = $this->_data_stash_get();
		$this->assertNotempty($obj['plant_id']);

		$url = sprintf('/plant/%s/collect', $obj['plant_id']);
		$arg = [
			'type' => 'net',
			'qty' => 12.34,
			'uom' => 'g',
		];
		$res = $this->_post($url, $arg);
		$res = $this->assertValidResponse($res, 201);

		$this->assertIsArray($res['meta']);
		$this->assertIsArray($res['data']);
		$this->assertCount(2, $res['data']);

		$obj = $res['data'];
		// $this->_data_stash_put($obj);

	}

	/**
	 * Here is when Plant Matter, with a Plant is closed to Inventory
	 */
	public function x_test_collect_all_new()
	{
		$obj = $this->_data_stash_get();
		$this->assertNotempty($obj['plant_id']);

		$url = sprintf('/plant/%s/collect', $obj['plant_id']);
		$arg = [
			'lot_id' => $obj['id'],
			'type' => 'net',
			'qty' => 12.34,
			'uom' => 'g',
		];
		$res = $this->_post($url, $arg);
		$this->assertValidResponse($res, 201);

		$url = sprintf('/plant/%s/collect', $obj['id']);
		$arg = [
			'lot_id' => $obj['id'],
			'type' => 'net',
			'qty' => 12.34,
			'uom' => 'g',
		];
		$res = $this->_post($url, $arg);
		$this->assertValidResponse($res, 201);

	}


}
