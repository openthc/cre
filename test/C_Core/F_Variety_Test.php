<?php
/**
 *
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\C_Core;

class F_Variety_Test extends \OpenTHC\CRE\Test\Base
{
	protected $_tmp_file = '/tmp/unit-test-variety.json';

	protected function setUp() : void
	{
		parent::setUp();
		$this->auth($_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'], $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'], $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A']);
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

		// Create Variety
		$res = $this->_post('/variety', [
			'name' => $name,
			'type' => 'Hybrid',
		]);
		$res = $this->assertValidResponse($res, 201);

		$this->assertIsArray($res['data']);

		$s0 = $res['data'];
		$this->assertNotEmpty($s0['id']);
		$this->assertEquals($name, $s0['name']);

		// Create Duplicate Variety
		$res = $this->_post('/variety', [
			'name' => $name,
			'type' => 'Hybrid',
		]);
		$res = $this->assertValidResponse($res, 409);

		// Create Duplicate Variety under different license
		// Reset Auth
		$this->httpClient = $this->_api();
		$this->auth($_ENV['OPENTHC_TEST_CLIENT_SERVICE_B'], $_ENV['OPENTHC_TEST_CLIENT_COMPANY_B'], $_ENV['OPENTHC_TEST_CLIENT_LICENSE_B']);

		$res = $this->_post('/variety', [
			'name' => $name,
			'type' => 'Sativa',
		]);
		$res = $this->assertValidResponse($res, 201);

		$this->_data_stash_put($s0);

	}

	/**
	 * @depends test_create
	 */
	public function test_search($x)
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

	public function test_update()
	{
		$name = sprintf('UNITTEST Variety UPDATE %06x', $this->_pid);
		// Find Early One
		$obj = $this->_data_stash_get();
		$url = sprintf('/variety/%s', $obj['id']);

		$res = $this->_post($url, [
			'name' => $name,
			'type' => 'Hemp'
		]);

		$res = $this->assertValidResponse($res, 201);

		$this->assertIsArray($res['data']);

		$s0 = $res['data'];
		$this->assertNotEmpty($s0['id']);
		$this->assertEquals($name, $s0['name']);

		// fetch and validate
		$res = $this->httpClient->get($url);
		$res = $this->assertValidResponse($res);

		$this->assertIsArray($res['data']);
		$this->assertEquals($name, $res['data']['name']);
	}

	public function test_delete()
	{
		$res = $this->httpClient->delete('/variety/four_zero_four');
		$this->assertValidResponse($res, 404);

		// Find Early One
		$obj = $this->_data_stash_get();

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
