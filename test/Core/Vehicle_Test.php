<?php
/**
 *
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\Core;

class Vehicle_Test extends \OpenTHC\CRE\Test\Base
{
	private $_url_path = '/vehicle';

	public function test_create()
	{
		$httpClient = $this->makeHTTPClient([
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_A'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A'],
		]);
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

		$Vehicle0 = $res['data'];
		$this->assertCount(3, $Vehicle0);

		return $Vehicle0;

	}

	/**
	 * @depends test_create
	 */
	public function test_search($Vehicle0)
	{
		$httpClient = $this->makeHTTPClient([
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_A'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A'],
		]);

		$res = $httpClient->get('/vehicle');
		$res = $this->assertValidResponse($res);

		$res = $httpClient->get('/vehicle?q=UNITTEST');
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

		$res = $httpClient->get('/vehicle/four_zero_four');
		$this->assertValidResponse($res, 404);

	}

	/**
	 * @depends test_create
	 */
	public function test_single($Vehicle0)
	{
		$httpClient = $this->makeHTTPClient([
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_A'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A'],
		]);

		$req_path = sprintf('/vehicle/%s', $Vehicle0['id']);
		$res = $httpClient->get($req_path);
		$res = $this->assertValidResponse($res, 200);
		$this->assertIsArray($res['data']);
		$this->assertCount(7, $res['data']);
	}

	/**
	 * @depends test_create
	 */
	public function test_update($Vehicle0)
	{
		$httpClient = $this->makeHTTPClient([
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_A'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A'],
		]);

		$req_path = sprintf('/vehicle/%s', $Vehicle0['id']);
		$Vehicle0['name'] = sprintf('UNITTEST Vehicle UPDATE %06x', $this->_pid);
		$res = $this->_post($req_path, [ 'form_params' => [
			'name' => $Vehicle0['name'],
		]]);

		$res = $this->assertValidResponse($res, 201);
		$this->assertIsArray($res['data']);

		$Vehicle1 = $res['data'];
		$this->assertNotEmpty($Vehicle1['id']);
		$this->assertEquals($Vehicle0['name'], $Vehicle1['name']);

		// fetch and validate
		$res = $httpClient->get($req_path);
		$res = $this->assertValidResponse($res);
		$this->assertIsArray($res['data']);
		$Vehicle1 = $res['data'];
		$this->assertEquals($Vehicle0['name'], $Vehicle1['name']);

	}

	/**
	 * @depends test_create
	 */
	public function test_delete($Vehicle0)
	{
		$httpClient = $this->makeHTTPClient([
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_A'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A'],
		]);

		$res = $httpClient->delete('/vehicle/four_zero_four');
		$this->assertValidResponse($res, 404);

		$req_path = sprintf('/vehicle/%s', $Vehicle0['id']);

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
