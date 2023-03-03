<?php
/**
 *
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\C_Core;

class G_Product_Test extends \OpenTHC\CRE\Test\Base_Case
{
	private $_url_path = '/product';

	protected function setUp() : void
	{
		parent::setUp();
		$this->auth($_ENV['api-service-a'], $_ENV['api-company-a'], $_ENV['api-license-a']);
	}

	public function test_create()
	{
		$name = sprintf('UNITTEST Product CREATE %06x', $this->_pid);

		$res = $this->_post($this->_url_path, [
			'name' => $name,
			'type' => '019KAGVSC0C474J20SEWDM5XSJ',
		]);

		$chk = $this->assertValidResponse($res, 201);
		// $this->assertNotEmpty($res->getHeaderLine('location'));
		// $this->assertMatchesRegularExpression('/\/config\/product\/\w{26}/', $res->getHeaderLine('location'));

		$res = $chk; // Now use the cleaed one
		$this->assertIsArray($res['meta']);
		$this->assertCount(1, $res['meta']);

		$this->assertIsArray($res['data']);

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

	public function test_update()
	{
		$res = $this->httpClient->get($this->_url_path . '/four_zero_four');
		$this->assertValidResponse($res, 404);

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
