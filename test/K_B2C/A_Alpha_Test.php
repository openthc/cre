<?php
/**
 * Execute Test for Retail Sales
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\K_B2C;

class A_Alpha_Test extends \OpenTHC\CRE\Test\Base
{
	protected function setUp() : void
	{
		parent::setUp();
		$this->auth(OPENTHC_TEST_CLIENT_SERVICE_C, $_ENV['api-company-d'], $_ENV['api-license-d']);
	}

	/**
	 * Creata a Retail B2C Sale
	 */
	public function test_create()
	{
		$res = $this->httpClient->get('/inventory');
		$res = $this->assertValidResponse($res, 200);
		$this->assertIsArray($res['data']);

		$this->assertGreaterThanOrEqual(2, count($res['data']), 'Not Enough Retail Products for Sale');

		$obj0 = $res['data'][0];
		$obj1 = $res['data'][1];

		$res = $this->_post('/b2c', []);
		$res = $this->assertValidResponse($res, 201);

		// Add Item
		$this->_post(sprintf('/b2c/%s', $res['data']['id']), [
			'inventory_id' => $obj0['id'],
			'qty' => 1,
			'unit_price' => 12,
		]);
		$res = $this->assertValidResponse($res, 201);

		// Add Item
		$this->_post(sprintf('/b2c/%s', $res['data']['id']), [
			'inventory_id' => $obj1['id'],
			'qty' => 2,
			'unit_price' => 23,
		]);
		$res = $this->assertValidResponse($res, 201);

		$res = $this->_post(sprintf('/b2c/%s/commit', $res['data']['id']), [
			'action' => 'COMMIT',
		]);
		$res = $this->assertValidResponse($res);

	}


	// public function testSearch()
	// {
	// 	$this->assertTrue(false);
	// }
	//
	//
	// public function test_single()
	// {
	// 	$this->assertTrue(false);
	// }
	//
	//
	// public function test_update()
	// {
	// 	$this->assertTrue(false);
	// }
	//
	//
	// public function test_delete()
	// {
	// 	$this->assertTrue(false);
	// }

}


// public function testSale()
// {
// 	/**
// 	 * Unauthenticated Tests
// 	 */
// 	$response = $this->httpClient->get('/sale');
// 	$this->assertEquals(403, $response->getStatusCode());
//
// 	$response = $this->httpClient->get('/sale/FourOhFour');
// 	$this->assertEquals(403, $response->getStatusCode());
//
// 	$response = $this->httpClient->get('/sale/1');
// 	$this->assertEquals(403, $response->getStatusCode());
//
// 	$response = $this->httpClient->get('/sale?' . http_build_query([
// 		'guid' => '1',
// 	]));
// 	$this->assertEquals(403, $response->getStatusCode());
//
// 	/**
// 	 * Authenticated Tests
// 	 */
// 	$this->auth('user', 'pass');
// 	$response = $this->httpClient->get('/sale');
// 	$this->assertEquals(200, $response->getStatusCode());
//
// 	$response = $this->httpClient->get('/sale/FourOhFour');
// 	$this->assertEquals(404, $response->getStatusCode());
//
// 	$response = $this->httpClient->get('/sale/1');
// 	$this->assertEquals(200, $response->getStatusCode());
//
// 	$response = $this->httpClient->get('/sale?' . http_build_query([
// 		'guid' => '1'
// 	]));
// 	$this->assertEquals(200, $response->getStatusCode());
// }
