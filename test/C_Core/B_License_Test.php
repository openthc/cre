<?php
/**
 *
 */

namespace Test\C_Core;

class B_License_Test extends \Test\Base_Case
{
	protected $_tmp_file = '/tmp/unit-test-license.json';

	protected function setUp() : void
	{
		parent::setUp();
		$this->auth($_ENV['api-service-a'], $_ENV['api-company-g0'], $_ENV['api-license-g0']);
	}


	public function test_public_read()
	{
		// Reset Auth
		$this->httpClient = $this->_api();

		$res = $this->httpClient->get('/license');
		$this->assertValidResponse($res, 403);

		$res = $this->httpClient->get('/license/four_zero_four');
		$this->assertValidResponse($res, 403);

		$res = $this->httpClient->get('/license/1');
		$this->assertValidResponse($res, 403);

		$res = $this->httpClient->get('/license?' . http_build_query([
			'q' => 'UNITTEST'
		]));
		$this->assertValidResponse($res, 403);

	}


	function test_create_as_root()
	{
		// Root Connection
		$this->auth($_ENV['api-service-0'], $_ENV['api-company-0'], $_ENV['api-license-0']);

		$res = $this->_post('/license', [
			'company' => $_ENV['api-company-g0'],
			'name' => 'UNITTEST License CREATE',
		]);
		$res = $this->assertValidResponse($res, 201);
		$this->assertIsArray($res['data']);
		$this->assertCount(4, $res['data']);

		$this->_data_stash_put($res['data']);
	}


	function test_search()
	{
		$res = $this->httpClient->get('/license');
		$res = $this->assertValidResponse($res, 200);
		$this->assertIsArray($res['data']);

		$res = $this->httpClient->get('/license?q=UNITTEST');
		$res = $this->assertValidResponse($res, 200);
		$this->assertIsArray($res['data']);

		$this->assertGreaterThan(1, count($res['data']));

	}


	function test_single()
	{
		$res = $this->httpClient->get('/license/four_zero_four');
		$this->assertValidResponse($res, 404);

		$res = $this->httpClient->get(sprintf('/license/%s', $_ENV['api-license-0']));
		$res = $this->assertValidResponse($res);
		$this->assertIsArray($res['data']);
		$this->assertEquals('-system-', $res['data']['name']);

		$obj = $this->_data_stash_get();

	}


	function test_update_as_root()
	{
		$this->auth($_ENV['api-service-0'], $_ENV['api-company-0'], $_ENV['api-license-0']);

		$obj = $this->_data_stash_get();

		$res = $this->_post('/license/' . $obj['id'], [
			'company' => $_ENV['api-company-g0'],
			'name' => 'UNITTEST License CREATE-UPDATE',
		]);

		$res = $this->assertValidResponse($res);
		$this->assertIsArray($res['data']);

	}


	function test_delete_as_root()
	{
		$this->auth($_ENV['api-service-0'], $_ENV['api-company-0'], $_ENV['api-license-0']);

		$obj = $this->_data_stash_get();

		$res = $this->httpClient->delete('/license/' . $obj['id']);
		$this->assertValidResponse($res, 405);

	}

}
