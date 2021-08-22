<?php
/**
 * Test Packaging Functions
 */

namespace Test\I_Package;

class A_Alpha_Test extends \Test\Base_Case
{
	/**
	 * Unauthenticated Tests
	 */
	public function test_access()
	{
		$res = $this->httpClient->get('/lot');
		$this->assertValidResponse($res, 403);

		$res = $this->httpClient->get('/lot/four_zero_four');
		$this->assertValidResponse($res, 403);

		$res = $this->httpClient->get('/lot/1');
		$this->assertValidResponse($res, 403);

		$res = $this->httpClient->get('/lot?' . http_build_query([
			'guid' => '1',
		]));
		$this->assertValidResponse($res, 403);

		$res = $this->_post('/lot/four_zero_four', [
			'qty' => 1,
		]);
		$this->assertValidResponse($res, 405);

	}

	/**
	 * Authenticated Tests
	 */
	public function test_access_auth()
	{
		$this->auth($_ENV['api-service-a'], $_ENV['api-company-g0'], $_ENV['api-license-g0']);

		$res = $this->httpClient->get('/lot');
		$this->assertValidResponse($res);

		$res = $this->httpClient->get('/lot/four_zero_four');
		$this->assertValidResponse($res, 404);

	}

	public function test_create()
	{
		$this->auth($_ENV['api-service-a'], $_ENV['api-company-g0'], $_ENV['api-license-g0']);

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

	}

	function test_convert()
	{
		$this->auth($_ENV['api-service-a'], $_ENV['api-company-g0'], $_ENV['api-license-g0']);

		$l0 = $this->find_random_lot();
		$p0 = $this->find_random_product();

		$res = $this->_post('/lot', [
			'source' => $l0['id'],
			'product_id' => $p0['id'],
			'qty' => 10,
		]);
		$res = $this->assertValidResponse($res, 201);

		$this->assertIsArray($res);
		$this->assertCount(2, $res);
		$this->assertIsArray($res['data']);

		$l1 = $res['data'];

	}

	public function test_delete()
	{
		$res = $this->httpClient->delete('/lot/four_zero_four');
		$this->assertValidResponse($res, 403);

		$this->auth($_ENV['api-service-a'], $_ENV['api-company-g0'], $_ENV['api-license-g0']);
		$res = $this->httpClient->delete('/lot/four_zero_four');
		$this->assertValidResponse($res, 404);

		$l0 = $this->find_random_lot();

	}

	public function test_search()
	{
		$this->auth($_ENV['api-service-a'], $_ENV['api-company-g0'], $_ENV['api-license-g0']);

		$res = $this->httpClient->get('/lot');
		$this->assertValidResponse($res);

	}

	public function test_single_404()
	{
		$this->auth($_ENV['api-service-a'], $_ENV['api-company-g0'], $_ENV['api-license-g0']);

		$res = $this->httpClient->get('/lot/four_zero_four');
		$this->assertValidResponse($res, 404);
	}

	public function test_update()
	{
		$this->auth($_ENV['api-service-a'], $_ENV['api-company-g0'], $_ENV['api-license-g0']);

		$l0 = $this->find_random_lot();
		$l0['qty'] = floatval($l0['qty']);

		$l0['qty'] = $l0['qty'] * 2;

		$res = $this->httpClient->patch('/lot/' . $l0['id'], [ 'json' => $l0 ]);
		$res = $this->assertValidResponse($res, 200);

		$this->assertIsArray($res['data']);
		$this->assertCount(14, $res['data']);

		$l1 = $res['data'];
		$this->assertNotEmpty($l1['hash']);
		$this->assertEquals($l0['id'], $l1['id']);
		$this->assertEquals($l0['qty'], $l1['qty']);
		$this->assertNotEquals($l0['hash'], $l1['hash']);

	}

}
