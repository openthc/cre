<?php
/**
 *
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\C_Core;

class G_Product_Test extends \OpenTHC\CRE\Test\Base
{
	private $_url_path = '/product';

	public function test_create()
	{
		$httpClient = $this->makeHTTPClient([
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_A'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A'],
		]);

		$name = sprintf('UNITTEST Product CREATE %06x', $this->_pid);

		$res = $httpClient->post('/product', [ 'form_params' => [
			'name' => $name,
			'type' => '019KAGVSC0C474J20SEWDM5XSJ',
			'uom' => 'g',
		]]);

		$chk = $this->assertValidResponse($res, 201);
		// $this->assertNotEmpty($res->getHeaderLine('location'));
		// $this->assertMatchesRegularExpression('/\/config\/product\/\w{26}/', $res->getHeaderLine('location'));

		$res = $chk; // Now use the cleaed one
		$this->assertIsArray($res['meta']);
		$this->assertCount(1, $res['meta']);

		$this->assertIsArray($res['data']);

		$Product0 = $res['data'];
		$this->assertCount(3, $Product0);

		return $Product0;

	}

	/**
	 * @depends test_create
	 */
	public function test_search($Product0)
	{
		$httpClient = $this->makeHTTPClient([
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_A'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A'],
		]);

		$res = $httpClient->get('/product');
		$res = $this->assertValidResponse($res);

		$res = $httpClient->get('/product?q=UNITTEST');
		$res = $this->assertValidResponse($res);


	}

	public function test_single_404()
	{
		$httpClient = $this->makeHTTPClient([
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_A'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A'],
		]);

		$res = $httpClient->get('/product/four_zero_four');
		$this->assertValidResponse($res, 404);

	}

	/**
	 * @depends test_create
	 */
	public function test_single($Product0)
	{
		$httpClient = $this->makeHTTPClient([
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_A'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A'],
		]);

		$req_path = sprintf('/product/%s', $Product0['id']);
		$res = $httpClient->get($req_path);
		$res = $this->assertValidResponse($res);
		var_dump($res);

		$this->assertIsArray($res['data']);
	}

	/**
	 * @depends test_create
	 */
	public function test_update($Product0)
	{
		$httpClient = $this->makeHTTPClient([
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_A'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A'],
		]);

		$req_path = sprintf('/product/%s', $Product0['id']);

		$Product0['name'] = sprintf('UNITTEST Product UPDATE %06x', $this->_pid);

		$res = $httpClient->post($req_path, [ 'form_params' => [
			'name' => $name,
			'type'=>'019KAGVSC0C474J20SEWDM5XSJ'
		]]);

		$res = $this->assertValidResponse($res, 201);
		$this->assertIsArray($res['data']);
		$Product1 = $res['data'];
		$this->assertNotEmpty($Product1['id']);
		$this->assertEquals($Product0['name'], $Product1['name']);

		// fetch and validate
		$res = $httpClient->get($req_path);
		$res = $this->assertValidResponse($res);
		$this->assertIsArray($res['data']);
		$Product1 = $res['data'];
		$this->assertNotEmpty($Product1['id']);
		$this->assertEquals($Product0['name'], $Product1['name']);

	}

	/**
	 * @depends test_create
	 */
	public function test_delete($Product0)
	{
		$httpClient = $this->makeHTTPClient([
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_A'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A'],
		]);

		$res = $httpClient->delete('/product/four_zero_four');
		$this->assertValidResponse($res, 404);

		$req_path = sprintf('/product/%s', $Product0['id']);

		// First call to Delete gives 202
		$res = $httpClient->delete($req_path);
		$this->assertValidResponse($res, 202);

		// Second Call should give 410
		$res = $httpClient->delete($req_path);
		$this->assertValidResponse($res, 410);

		$res = $httpClient->delete($req_path);
		$this->assertValidResponse($res, 423);

	}

}
