<?php
/**
 * Test Plant Stuff
 */

namespace Test\Plant;

class Alpha extends \Test\Components\OpenTHC_Test_Case
{
	protected function setUp() : void
	{
		parent::setUp();
		$this->auth($_ENV['api-program-a'], $_ENV['api-company-g0'], $_ENV['api-license-g0']);
	}

	public function test_create()
	{
		$sl0 = $this->find_random_lot(''); // of type Seed or Clone?
		$s = $this->find_random_strain();
		$z = $this->find_random_zone();

		$res = $this->_post('/plant', [
			'source' => '', // A Lot of Clones, Plants or Seeds
			'strain' => $s['id'], // A New Strain
			'zone' => $z['id'], // Optional
			'qty' => 10,
		]);
		$res = $this->assertValidResponse($res, 201);
		$this->assertIsArray($res);
		$this->assertIsArray($res['data']);

		$obj = $res['data'];
		$this->_data_stash_put($obj);

	}


	public function test_search()
	{
		$res = $this->httpClient->get('/plant');
		$this->assertValidResponse($res, 200);


		$res = $this->httpClient->get('/plant?strain=');
		$this->assertValidResponse($res, 200);

		$res = $this->httpClient->get('/plant?zone=');
		$this->assertValidResponse($res, 200);

	}

	public function test_single()
	{
		// 404
		$res = $this->httpClient->get('/plant/four_zero_four');
		$this->assertValidResponse($res, 404);

		// // NAME
		// $res = $this->httpClient->get('/plant/name/WeedTraQR');
		// $this->assertEquals(200, $res->getStatusCode());
		//
		// $res = $this->httpClient->get('/plant?' . http_build_query([
		// 	'name' => 'WeedTraQR'
		// ]));
		//
		// // Partial Name
		// $res = $this->httpClient->get('/plant/name/WeedTra');
		// $this->assertEquals(200, $res->getStatusCode());
		//
		// $res = $this->httpClient->get('/plant?' . http_build_query([
		// 	'name' => 'WeedTra'
		// ]));
		// $this->assertTrue(false);
	}

	public function test_update()
	{
		// The one we recently created
		$obj = $this->_data_stash_get();
		$res = $this->httpClient->get('/plant/' . $obj['id']);
		$res = $this->assertValidResponse($res);

		$this->_post('/plant/' . $obj['id'], [
			'name' => 'UNITTEST Plant CREATE-UPDATE',
		]);

	}

	function test_delete()
	{
		// 404
		$res = $this->httpClient->delete('/plant/four_zero_four');
		$this->assertValidResponse($res, 404);

		// $obj = $this->_data_stash_get();
		//
		// $res = $this->httpClient->delete('/plant/' . $obj['id']);
		// $this->assertValidResponse($res, 202, 'fdafdas');
		//
		// $res = $this->httpClient->delete('/plant/' . $obj['id']);
		// $this->assertValidResponse($res, 410);
		//
		// $res = $this->httpClient->delete('/plant/' . $obj['id']);
		// $this->assertValidResponse($res, 423);


	}

}


// public function testPlant()
// {
// 	/**
// 	 * Unauthenticated Tests
// 	 */
// 	$response = $this->httpClient->get('/plant');
// 	$this->assertEquals(403, $response->getStatusCode());
//
// 	$response = $this->httpClient->get('/plant/FourOhFour');
// 	$this->assertEquals(403, $response->getStatusCode());
//
// 	$response = $this->httpClient->get('/plant/1');
// 	$this->assertEquals(403, $response->getStatusCode());
//
// 	$response = $this->httpClient->get('/plant?' . http_build_query([
// 		'guid' => '1',
// 	]));
// 	$this->assertEquals(403, $response->getStatusCode());
//
// 	/**
// 	 * Authenticated Tests
// 	 */
// 	$this->auth('user', 'pass');
// 	$response = $this->httpClient->get('/plant');
// 	$this->assertEquals(200, $response->getStatusCode());
//
// 	$response = $this->httpClient->get('/plant/FourOhFour');
// 	$this->assertEquals(404, $response->getStatusCode());
//
// 	$response = $this->httpClient->get('/plant/1');
// 	$this->assertEquals(200, $response->getStatusCode());
//
// 	$response = $this->httpClient->get('/plant?' . http_build_query([
// 		'guid' => '1'
// 	]));
// 	$this->assertEquals(200, $response->getStatusCode());
// }
