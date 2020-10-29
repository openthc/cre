<?php
/**
 * Tests the inventory section of the API
 */

namespace Test\Lot_Source;

class Alpha extends \Test\Components\OpenTHC_Test_Case
{
	protected $_url_path = '/lot';

	protected function setUp() : void
	{
		parent::setUp();
		$this->auth($_ENV['api-program-a'], $_ENV['api-company-g0'], $_ENV['api-license-g0']);
	}

	function test_create()
	{
		$p = $this->find_random_product();
		$s = $this->find_random_strain();
		$z = $this->find_random_section();

		$res = $this->_post($this->_url_path, [
			// 'source' => '', // A Plant
			'product' => $p['id'],
			'strain' => $s['id'],
			'section' => $z['id'],
			'qty' => 1234,
		]);
		$res = $this->assertValidResponse($res, 201);

		$this->assertCount(2, $res);
		$this->assertIsArray($res['meta']);
		$this->assertIsArray($res['data']);
		$this->assertGreaterThan(1, count($res['data']));

		$obj = $res['data'];
		$this->_data_stash_put($obj);

	}

	function test_search()
	{
		$res = $this->httpClient->get($this->_url_path);
		$res = $this->assertValidResponse($res);

		$this->assertCount(2, $res);
		$this->assertIsArray($res['meta']);
		$this->assertIsArray($res['data']);
		$this->assertGreaterThanOrEqual(1, count($res['data']));

		// $obj = $res['data'][0];
		// $this->assertCount(5, $obj);

	}

	function test_single_404()
	{
		$res = $this->httpClient->get($this->_url_path . '/four_zero_four');
		$res = $this->assertValidResponse($res, 404);
	}

	function test_single_200()
	{
		$res = $this->httpClient->get($this->_url_path);
		$res = $this->assertValidResponse($res);

		$obj = $res['data'][0];
		$this->assertCount(14, $obj);

		// Good Request
		$res = $this->httpClient->get($this->_url_path . '/' . $obj['id']);
		$res = $this->assertValidResponse($res, 200);

		$this->assertCount(2, $res);
		$this->assertIsArray($res['meta']);
		$this->assertIsArray($res['data']);

		$obj = $res['data'];
		$this->assertCount(14, $obj);

	}

	function x_test_update()
	{
		$obj = $this->_data_stash_get();
		$this->assertIsArray($obj);

		$res = $this->httpClient->get($this->_url_path . '/' . $obj['id']);
		$res = $this->assertValidResponse($res);

		$chk = $res['data'];
		$this->assertCount(6, $chk);

		$res = $this->httpClient->post($this->_url_path . '/' . $obj['id'], [
			'strain' => '',
			'qty' => 9,
		]);

		$res = $this->assertValidResponse($res);

	}

	// function testInventoryQASample()
	// {
	// 	// @todo fill these out
	// 	$this->assertTrue(false);
	// }
	//
	// function testInventoryEmployeeSample()
	// {
	// 	// $this->assertEquals(false, true, 'testInventoryEmployeeSample is not defined');
	// 	$this->assertTrue(false);
	// }
	//
	// function testInventoryVendorSample()
	// {
	// 	$this->assertTrue(false);
	//
	// }
	//
	// function testInventoryMove()
	// {
	// 	$this->assertTrue(false);
	//
	// }
	//
	// function testInventoryAdjust()
	// {
	// 	$this->assertTrue(false);
	//
	// }
	//
	// function testInventoryNote()
	// {
	// 	$this->assertTrue(false);
	//
	// }
	//
	//
	// function testInventoryRevert()
	// {
	// 	$this->assertTrue(false);
	//
	// }
	//
	// function testInventoryDestroy()
	// {
	// 	$this->assertTrue(false);
	//
	// }
	//
	// function testInventoryTransfer()
	// {
	// 	$this->assertTrue(false);
	//
	// }
	//
	// function testInventorySubLot()
	// {
	// 	$this->assertTrue(false);
	//
	// }

}
