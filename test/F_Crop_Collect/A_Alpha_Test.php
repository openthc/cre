<?php
/**
 *
 */

namespace Test\F_Crop_Collect;

class A_Alpha_Test extends \Test\Components\OpenTHC_Test_Case
{
	protected function setUp() : void
	{
		parent::setUp();
		$this->auth($_ENV['api-service-a'], $_ENV['api-company-g0'], $_ENV['api-license-g0']);
	}

	function test_public()
	{
		$res = $this->httpClient->post('/auth/shut');
		$res = $this->assertValidResponse($res, 200);

		$res = $this->httpClient->get('/plant');
		$res = $this->assertValidResponse($res, 403);

		$res = $this->httpClient->get('/plant/four_zero_four');
		$res = $this->assertValidResponse($res, 403);

	}

	public function test_collect_one_wet()
	{
		$p = $this->find_random_plant();
		$url = sprintf('/plant/%s/collect', $p['id']);
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
		$p = $this->find_random_plant();
		$this->assertNotempty($p['id']);

		$url = sprintf('/plant/%s/collect', $p['id']);
		$arg = [
			'type' => 'wet',
			'qty' => 1234.56,
			'uom' => 'g',
		];
		$res = $this->_post($url, $arg);
		$this->assertValidResponse($res, 201);

		$p = $this->find_random_plant();
		$this->assertNotempty($p['id']);

		$url = sprintf('/plant/%s/collect', $p['id']);
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
		$p = $this->find_random_plant();
		$this->assertNotempty($p['id']);

		$url = sprintf('/plant/%s/collect', $p['id']);
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
		$p = $this->find_random_plant();
		$this->assertNotempty($p['id']);

		$url = sprintf('/plant/%s/collect', $p['id']);
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
		$p = $this->find_random_plant();
		$this->assertNotempty($p['id']);

		$url = sprintf('/plant/%s/collect', $p['id']);
		$arg = [
			'type' => 'net',
			'qty' => 1234.56,
			'uom' => 'g',
		];
		$res = $this->_post($url, $arg);
		$this->assertValidResponse($res, 201);

		$url = sprintf('/plant/%s/collect', $p['id']);
		$arg = [
			'lot_id' => $p['id'],
			'type' => 'net',
			'qty' => 12.34,
			'uom' => 'g',
		];
		$res = $this->_post($url, $arg);
		$res = $this->assertValidResponse($res, 201);

		$pc = $res['data'];

	}


}
