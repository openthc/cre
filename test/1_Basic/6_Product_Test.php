<?php
/**
 *
 */

namespace Test\Product;

class Product extends \Test\Components\OpenTHC_Test_Case
{
	private $_url_path = '/config/product';

	protected function setUp() : void
	{
		parent::setUp();
		$this->auth($_ENV['api-program-a'], $_ENV['api-company-g0'], $_ENV['api-license-g0']);
	}

	public function test_create()
	{
		$name = sprintf('UNITTEST Product CREATE %06x', $this->_pid);

		$res = $this->_post($this->_url_path, [
			'name' => $name,
		]);

		$chk = $this->assertValidResponse($res, 201);
		// $this->assertNotEmpty($res->getHeaderLine('location'));
		// $this->assertRegExp('/\/config\/product\/\w{26}/', $res->getHeaderLine('location'));

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

	public function test_single_404() {

		$res = $this->httpClient->get($this->_url_path . '/four_zero_four');
		$this->assertValidResponse($res, 404);

		$res = $this->httpClient->get($this->_url_path . '/1');
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
		//var_dump($obj);

		// First call to Delete gives 202
		$res = $this->httpClient->delete($this->_url_path . '/' . $obj['id']);
		$this->assertValidResponse($res, 202);

		// Second Call should give 410
		$res = $this->httpClient->delete($this->_url_path . '/' . $obj['id']);
		$this->assertValidResponse($res, 410);

		$res = $this->httpClient->delete($this->_url_path . '/' . $obj['id']);
		$this->assertValidResponse($res, 423);

		unlink($this->_tmp_file);


	}

}
