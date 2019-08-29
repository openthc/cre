<?php
/**
 * Test Packaging Functions
 */

namespace Test\Lot_Package;

class Alpha extends \Test\Components\OpenTHC_Test_Case
{
	protected function setUp() : void
	{
		parent::setUp();
		$this->auth($_ENV['api-program-a'], $_ENV['api-company-g0'], $_ENV['api-license-g0']);
	}

	public function test_create()
	{
		$l0 = $this->find_random_lot();
		$p0 = $this->find_random_product();

		$res = $this->_post('/lot', [
			'source' => $l0['id'],
			'product_id' => $p0['id'],
			'qty' => 500,
		]);
		$res = $this->assertValidResponse($res, 201);

		$this->assertIsArray($res);
		$this->assertCount(2, $res);
		$this->assertIsArray($res['data']);

		$l1 = $res['data'];

		print_r($l1);

	}

	public function test_delete()
	{
		$res = $this->httpClient->delete('/lot/four_zero_four');
		$this->assertValidResponse($res, 404);

	}

	public function testSearch()
	{
		$res = $this->httpClient->get('/lot');
		$this->assertValidResponse($res);

	}

	public function test_single_404()
	{
		$res = $this->httpClient->get('/lot/four_zero_four');
		$this->assertValidResponse($res, 404);
	}

	public function test_update()
	{
		$l0 = $this->find_random_lot();

		$l0['qty'] = $l0['qty'] * 2;

		$res = $this->httpClient->patch('/lot/' . $l0['id']);
		$res = $this->assertValidResponse($res, 200);

		$l1 = $res['data'];

		$this->assertEquals($l0['qty'], $l1['qty']);

	}

}


// public function testLot()
// {
// 	/**
// 	 * Unauthenticated Tests
// 	 */
// 	$response = $this->httpClient->get('/lot');
// 	$this->assertEquals(403, $response->getStatusCode());
//
// 	$response = $this->httpClient->get('/lot/FourOhFour');
// 	$this->assertEquals(403, $response->getStatusCode());
//
// 	$response = $this->httpClient->get('/lot/1');
// 	$this->assertEquals(403, $response->getStatusCode());
//
// 	$response = $this->httpClient->get('/lot?' . http_build_query([
// 		'guid' => '1',
// 	]));
// 	$this->assertEquals(403, $response->getStatusCode());
//
// 	/**
// 	 * Authenticated Tests
// 	 */
// 	$this->auth('user', 'pass');
// 	$response = $this->httpClient->get('/lot');
// 	$this->assertEquals(200, $response->getStatusCode());
//
// 	$response = $this->httpClient->get('/lot/FourOhFour');
// 	$this->assertEquals(404, $response->getStatusCode());
//
// 	$response = $this->httpClient->get('/lot/1');
// 	$this->assertEquals(200, $response->getStatusCode());
//
// 	$response = $this->httpClient->get('/lot?' . http_build_query([
// 		'guid' => '1'
// 	]));
// 	$this->assertEquals(200, $response->getStatusCode());
// }
