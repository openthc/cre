<?php
/**
 *
 */

namespace Test\Core;

class Company extends \Test\Components\OpenTHC_Test_Case
{
	protected function setUp() : void
	{
		parent::setUp();
		$this->auth($_ENV['api-service-a'], $_ENV['api-company-g0'], $_ENV['api-license-g0']);
	}

	public function test_public_read()
	{
		// Reset Auth
		$this->httpClient = $this->_api();

		$res = $this->httpClient->get('/company');
		$this->assertValidResponse($res, 403);

		$res = $this->httpClient->get('/company/four_zero_four');
		$this->assertValidResponse($res, 403);

		$res = $this->httpClient->get('/company/1');
		$this->assertValidResponse($res, 403);

		$res = $this->httpClient->get('/company?' . http_build_query([
			'q' => 'UNITTEST'
		]));
		$this->assertValidResponse($res, 403);

	}

	public function test_create_as_root()
	{
		$this->auth($_ENV['api-service-0'], $_ENV['api-company-0'], $_ENV['api-license-0']);

		$res = $this->httpClient->post('/company', [ 'form_params' => [
			'name' => 'UNITTEST Company CREATE',
			'code' => 'CO123456',
		]]);
		$res = $this->assertValidResponse($res, 201);

		$this->assertIsArray($res);
		$this->assertCount(2, $res);
		$this->assertIsArray($res['data']);

		$c0 = $res['data'];
		$this->assertIsArray($c0);
		$this->assertNotEmpty($c0['id']);

		$this->_data_stash_put($c0);

	}

	public function test_search()
	{
		$res = $this->httpClient->get('/company');
		$res = $this->assertValidResponse($res);


		$res = $this->httpClient->get('/company?' . http_build_query([
			'name' => 'system'
		]));
		$this->assertValidResponse($res);

		$res = $this->httpClient->get('/company?' . http_build_query([
			'q' => 'UNITTEST'
		]));
		$res = $this->assertValidResponse($res);

		$this->assertIsArray($res);
		$this->assertCount(2, $res);
		$this->assertIsArray($res['data']);

		$c0 = $res['data'][0];
		$this->assertIsArray($c0);

	}

	public function test_single()
	{
		$res = $this->httpClient->get('/company/four_zero_four');
		$this->assertValidResponse($res, 404);

		// System
		$res = $this->httpClient->get('/company/019KAGVSC05RHV4QAS76VPV6J7');
		$this->assertValidResponse($res);

		$res = $this->httpClient->get('/company/' . $_ENV['api-company-g0']);
		$this->assertValidResponse($res);

		$res = $this->httpClient->get('/company/' . $_ENV['api-company-p0']);
		$this->assertValidResponse($res);

		// $res = $this->httpClient->get('/company/' . $_ENV['api-company-l0']);
		// $this->assertValidResponse($res);

		$res = $this->httpClient->get('/company/' . $_ENV['api-company-r0']);
		$this->assertValidResponse($res);

	}

	public function test_update()
	{
		$c0 = $this->_data_stash_get();

		$res = $this->_post('/company/' . $c0['id'],  [
			'name' => 'UNITTEST Company CREATE-UPDATE',
		]);
		$res = $this->assertValidResponse($res);

		// Update phone
		// $this->assertTrue(false);

		// Update several fields
		// $this->assertTrue(false);
	}

	public function test_delete()
	{
		$res = $this->httpClient->delete('/company/four_zero_four');
		$this->assertValidResponse($res, 405);

		// do stuff?
		$res = $this->httpClient->delete('/company/' . $_ENV['api-company-g0']);
		$this->assertValidResponse($res, 405);

		$res = $this->httpClient->delete('/company/' . $_ENV['api-company-p0']);
		$this->assertValidResponse($res, 405);

	}


	public function test_delete_as_root()
	{
		$this->auth($_ENV['api-service-0'], $_ENV['api-company-0'], $_ENV['api-license-0']);

		$res = $this->httpClient->delete('/company/four_zero_four');
		$this->assertValidResponse($res, 404);


		// Make New Company
		$res = $this->_post('/company', [
			'name' => 'UNITTEST Company DELETE',
			'code' => 'DELETE',
		]);
		$res = $this->assertValidResponse($res, 201);

		$this->assertIsArray($res);
		$this->assertCount(2, $res);
		$this->assertIsArray($res['data']);
		// var_dump($res);

		$c0 = $res['data'];
		$this->assertIsArray($c0);
		$this->assertNotEmpty($c0['id']);

		// Delete This Company
		$res = $this->httpClient->delete('/company/' . $c0['id']);
		$this->assertValidResponse($res, 405);
		// $this->assertEquals(405, $res->getStatusCode());
		// , 'Confirms we cannot delete Company');

	}
}
