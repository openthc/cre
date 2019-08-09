<?php
/**
 *
 */
namespace Test\Lot_Package;

class Alpha extends \Test\Components\OpenTHC_Test_Case
{
	protected function setUp() : void
	{
		parent::setUp();
		$this->auth($_ENV['api-program-a'], $_ENV['api-company-p0'], $_ENV['api-license-p0']);
	}

	public function test_create()
	{
		$res = $this->_post('/lot', [
			'source_id' => '',
			'product_id' => '',
			'qty' => '',
		]);
		$this->assertValidResponse($res, 201);

		$json = $res->getBody(true);
		$res = json_decode($json, true);

		$this->assertIsArray($res);
		$this->assertCount(2, $res);
		$this->assertIsArray($res['data']);

		$lot0 = $res['data'][0];

	}

	public function test_delete()
	{
		$res = $this->httpClient->delete('/lot/four_zero_four');
		$this->assertValidResponse($res, 404);

		$res = $this->httpClient->delete('/lot/4564564564564564564564');
		$this->assertValidResponse($res);

	}

	public function testSearch()
	{
		$res = $this->httpClient->get('/lot');
		$this->assertValidResponse($res);

		$this->assertTrue(false, 'Finish Testing');

		$res = $this->httpClient->get('/lot?' . http_build_query([
			'guid' => '1'
		]));
		$this->assertEquals(200, $res->getStatusCode());
		$this->assertEquals('application/json', $res->getHeaderLine('Content-Type'));

		// Name
		$res = $this->httpClient->get('/lot/name/WeedTraQR');
		$this->assertEquals(200, $res->getStatusCode());
		$this->assertEquals('application/json', $res->getHeaderLine('Content-Type'));

		$res = $this->httpClient->get('/lot?' . http_build_query([
			'name' => 'WeedTraQR'
		]));
		$this->assertEquals(200, $res->getStatusCode());
		$this->assertEquals('application/json', $res->getHeaderLine('Content-Type'));

		// PArtial Name
		$res = $this->httpClient->get('/lot/name/OpenT');
		$this->assertEquals(200, $res->getStatusCode());

		$res = $this->httpClient->get('/lot?' . http_build_query([
			'name' => 'OpenT'
		]));
		$this->assertEquals(200, $res->getStatusCode());
		$this->assertTrue(false);

	}

	public function test_single_200()
	{
		// GUID
		$res = $this->httpClient->get('/lot/fdafdsafdsa');
		$this->assertValidResponse($res);

		$res = $this->httpClient->get('/lot/1');
		$this->assertValidResponse($res);

	}

	public function test_single_404()
	{
		$res = $this->httpClient->get('/lot/four_zero_four');
		$this->assertValidResponse($res, 404);
	}

	public function test_update()
	{
		$this->assertTrue(false);
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
