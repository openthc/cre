<?php
/**
 * Tests the inventory section of the API
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\Inventory_Propagate;

class Alpha_Test extends \OpenTHC\CRE\Test\Base
{
	protected $_url_path = '/inventory';

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

	function test_create()
	{
		$p = $this->find_random_product();
		$v = $this->find_random_variety();
		$s = $this->find_random_section();

		$res = $this->_post($this->_url_path, [
			// 'source' => '', // A Plant
			'product' => $p['id'],
			'variety' => $v['id'],
			'section' => $s['id'],
			'qty' => 1234,
		]);
		$res = $this->assertValidResponse($res, 201);

		$this->assertCount(2, $res);
		$this->assertIsArray($res['meta']);
		$this->assertIsArray($res['data']);
		$this->assertGreaterThan(1, count($res['data']));

		$Inventory0 = $res['data'];

		return $Inventory0;

	}

	function test_create_clone()
	{
		$this->markTestSkipped('Skip: ' . __METHOD__);
	}

	function test_create_plant()
	{
		$this->markTestSkipped('Skip: ' . __METHOD__);
		// $this->assertTrue(true);
	}

	function test_create_seeds()
	{
		// $this->markTestSkipped('Skip: ' . __METHOD__);
	}

	function test_create_tissue()
	{
		$this->markTestSkipped('Skip: ' . __METHOD__);
		// $this->assertTrue(true);
	}

	/**
	 * @depends test_create
	 */
	function test_search($Inventory0)
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

	/**
	 * @depends test_create
	 */
	function test_single_200($Inventory0)
	{
		$res = $this->httpClient->get($this->_url_path);
		$res = $this->assertValidResponse($res);

		$obj = $res['data'][0];
		$this->assertCount(3, $obj);

		// Good Request
		$res = $this->httpClient->get($this->_url_path . '/' . $obj['id']);
		$res = $this->assertValidResponse($res, 200);

		$this->assertCount(2, $res);
		$this->assertIsArray($res['meta']);
		$this->assertIsArray($res['data']);

		$obj = $res['data'];
		$this->assertCount(13, $obj);

	}

	/**
	 * @depends test_create
	 */
	function test_update($Inventory0)
	{
		$this->assertNotEmpty($Inventory0);
		$this->assertIsArray($Inventory0);

		$req_path = sprintf('/inventory/%s', $Inventory0['id']);
		$res = $this->httpClient->get($req_path);
		$res = $this->assertValidResponse($res);
		$this->assertIsArray($res['data']);

		$res = $this->httpClient->post($req_path, [
			'variety' => '',
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

	/**
	 * @depends test_create
	 */
	function test_finish($Inventory0)
	{
		$this->assertNotEmpty($Inventory0);
	}

	/**
	 * @depends test_create
	 */
	function test_delete($Inventory0)
	{
		$this->assertNotEmpty($Inventory0);
	}

}
