<?php
/**
 * Execute Test for Retail Sales
 */

namespace Test\B2C;

class Alpha extends \Test\Components\OpenTHC_Test_Case
{
	protected function setUp() : void
	{
		parent::setUp();
		$this->auth($_ENV['api-program-c'], $_ENV['api-company-r0'], $_ENV['api-license-r0']);
	}

	/**
	 * Creata a Retail B2C Sale
	 */
	public function test_create()
	{
		$res = $this->httpClient->get('/lot');
		$res = $this->assertValidResponse($res, 200);
		$this->assertIsArray($res['data']);

		$this->assertGreaterThanOrEqual(2, count($res['data']), 'Not Enough Retail Products for Sale');

		$obj0 = $res['data'][0];
		$obj1 = $res['data'][1];

		$res = $this->_post('/sale');
		$res = $this->assertValidResponse($res, 201);

		// Add Item
		$this->post('/sale/' . $res['data']['id'], [
			'lot_id' => $obj0['id'],
			'qty' => 1,
			'unit_price' => 12,
		]);
		$res = $this->assertValidResponse($res, 201);

		// Add Item
		$this->post('/sale/' . $res['data']['id'], [
			'lot_id' => $obj1['id'],
			'qty' => 2,
			'unit_price' => 23,
		]);
		$res = $this->assertValidResponse($res, 201);

		$res = $this->_post('/sale/' . $res['data']['id']);
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
