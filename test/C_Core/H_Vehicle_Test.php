<?php
/**
 *
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\C_Core;

class H_Vehicle_Test extends \OpenTHC\CRE\Test\Base
{
	private $_url_path = '/vehicle';

	protected function setUp() : void
	{
		parent::setUp();
		$this->auth($_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'], $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'], $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A']);
	}

	public function test_create()
	{
		$name = sprintf('UNITTEST Vehicle CREATE %06x', $this->_pid);

		$res = $this->_post($this->_url_path, [
			'name' => $name,
			'make' => 'Toyota',
			'model' => 'Corolla',
			'color' => 'Grey',
			'vin' => '1234567890ABCDEF0',
			'vrn' => 'ABC123',
		]);

		$res = $this->assertValidResponse($res, 201);
		$this->assertIsArray($res['data']);
		// $this->assertIsArray($res['meta']);
		// $this->assertCount(1, $res['meta']);

		$obj = $res['data'];
		$this->assertCount(3, $obj);

		$this->_data_stash_put($obj);

	}


	public function test_search()
	{
		$res = $this->httpClient->get($this->_url_path);
		$res = $this->assertValidResponse($res);

		$res = $this->httpClient->get($this->_url_path . '?q=UNITTEST');
		$res = $this->assertValidResponse($res);


	}

	public function test_single_404()
	{

		$res = $this->httpClient->get($this->_url_path . '/four_zero_four');
		$this->assertValidResponse($res, 404);

	}

	public function test_single()
	{
		$obj = $this->_data_stash_get();
		$res = $this->httpClient->get(sprintf('%s/%s', $this->_url_path, $obj['id']));
		$res = $this->assertValidResponse($res, 200);
		$this->assertIsArray($res['data']);
		$this->assertCount(7, $res['data']);
	}


	public function test_update()
	{
		$obj = $this->_data_stash_get();

		$name = sprintf('UNITTEST Vehicle UPDATE %06x', $this->_pid);

		$res = $this->_post(sprintf('%s/%s', $this->_url_path, $obj['id']), [
			'name' => $name,
		]);

		$res = $this->assertValidResponse($res, 201);

		$this->assertIsArray($res['data']);

		$s0 = $res['data'];
		$this->assertNotEmpty($s0['id']);
		$this->assertEquals($name, $s0['name']);

		// fetch and validate
		$res = $this->httpClient->get(sprintf('%s/%s', $this->_url_path, $obj['id']));
		$res = $this->assertValidResponse($res);

		$this->assertIsArray($res['data']);
		$this->assertEquals($name, $res['data']['name']);


	}

	public function test_delete()
	{
		$res = $this->httpClient->delete($this->_url_path . '/four_zero_four');
		$this->assertValidResponse($res, 404);

		// Find Early One
		$obj = $this->_data_stash_get();

		// First call to Delete gives 202
		$res = $this->httpClient->delete($this->_url_path . '/' . $obj['id']);
		$this->assertValidResponse($res, 202);

		// Second Call should give 410
		$res = $this->httpClient->delete($this->_url_path . '/' . $obj['id']);
		$this->assertValidResponse($res, 410);

		$res = $this->httpClient->delete($this->_url_path . '/' . $obj['id']);
		$this->assertValidResponse($res, 423);

	}

}
