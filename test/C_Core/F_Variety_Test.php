<?php
/**
 *
 */

namespace Test\C_Core;

class F_Variety_Test extends \Test\Base_Case
{
	protected $_tmp_file = '/tmp/unit-test-variety.json';

	protected function setUp() : void
	{
		parent::setUp();
		$this->auth($_ENV['api-service-a'], $_ENV['api-company-g0'], $_ENV['api-license-g0']);
	}

	public function test_public_read()
	{
		// Reset Auth
		$this->httpClient = $this->_api();

		$res = $this->httpClient->get('/variety');
		$this->assertValidResponse($res, 403);

		$res = $this->httpClient->get('/variety/four_zero_four');
		$this->assertValidResponse($res, 403);

		$res = $this->httpClient->get('/variety/1');
		$this->assertValidResponse($res, 403);

		$res = $this->httpClient->get('/variety?' . http_build_query([
			'q' => 'UNITTEST'
		]));
		$this->assertValidResponse($res, 403);

	}

	public function test_create()
	{
		$name = sprintf('UNITTEST Variety CREATE %06x', $this->_pid);

		$res = $this->_post('/variety', [
			'name' => $name,
		]);
		$res = $this->assertValidResponse($res, 201);

		$this->assertIsArray($res['data']);

		$s0 = $res['data'];
		$this->assertNotEmpty($s0['id']);
		$this->assertEquals($name, $s0['name']);

		$this->_data_stash_put($s0);

	}

	public function test_search()
	{
		$res = $this->httpClient->get('/variety?q=UNITTEST');
		$res = $this->assertValidResponse($res);
	}

	public function test_single()
	{
		$res = $this->httpClient->get('/variety/four_zero_four');
		$res = $this->assertValidResponse($res, 404);

		// Find Early One
		$obj = $this->_data_stash_get();

		$res = $this->httpClient->get('/variety/' . $obj['id']);
		$res = $this->assertValidResponse($res);

		$this->assertIsArray($res['data']);

	}

	// public function test_update()
	// {
	// 	$this->assertTrue(false);
	// }

	public function test_delete()
	{
		$res = $this->httpClient->delete('/variety/four_zero_four');
		$this->assertValidResponse($res, 404);

		// Find Early One
		$obj = $this->_data_stash_get();
		//var_dump($obj);

		// First call to Delete gives 202
		$res = $this->httpClient->delete('/variety/' . $obj['id']);
		$this->assertValidResponse($res, 202);

		// Second Call should give 410
		$res = $this->httpClient->delete('/variety/' . $obj['id']);
		$this->assertValidResponse($res, 410);

		$res = $this->httpClient->delete('/variety/' . $obj['id']);
		$this->assertValidResponse($res, 423);

		unlink($this->_tmp_file);
	}

}
