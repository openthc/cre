<?php
/**
 *
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\C_Core;

class F_Variety_Test extends \OpenTHC\CRE\Test\Base
{
	public function test_public_read()
	{
		// Reset Auth
		$this->httpClient = $this->_api();

		$res = $this->httpClient->get('/variety');
		$this->assertValidResponse($res, 401);

		$res = $this->httpClient->get('/variety/four_zero_four');
		$this->assertValidResponse($res, 401);

		$res = $this->httpClient->get('/variety/1');
		$this->assertValidResponse($res, 401);

		$res = $this->httpClient->get('/variety?' . http_build_query([
			'q' => 'UNITTEST'
		]));
		$this->assertValidResponse($res, 401);

	}

	public function test_create()
	{
		$this->httpClient = $this->makeHTTPClient([
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_A'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A'],
		]);

		$name = sprintf('UNITTEST Variety CREATE %06x', $this->_pid);

		// Create Variety
		$res = $this->_post('/variety', [
			'name' => $name,
			'type' => 'Hybrid',
		]);
		$res = $this->assertValidResponse($res, 201);

		$this->assertIsArray($res['data']);

		$Variety0 = $res['data'];
		$this->assertNotEmpty($Variety0['id']);
		$this->assertEquals($name, $Variety0['name']);

		// Create Duplicate Variety
		$res = $this->_post('/variety', [
			'name' => $name,
			'type' => 'Hybrid',
		]);
		$res = $this->assertValidResponse($res, 409);

		// Create Duplicate Variety under different license
		// Reset Auth
		$this->httpClient = $this->makeHTTPClient([
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_B'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_B'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_B'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_B'],
		]);

		$res = $this->_post('/variety', [
			'name' => $name,
			'type' => 'Sativa',
		]);
		$res = $this->assertValidResponse($res, 201);

		return $Variety0;
	}

	/**
	 * @depends test_create
	 */
	public function test_search($Variety0)
	{
		$httpClient = $this->makeHTTPClient([
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_A'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A'],
		]);

		$res = $httpClient->get('/variety?q=UNITTEST');
		$res = $this->assertValidResponse($res);
	}

	/**
	 * @depends test_create
	 */
	public function test_single($Variety0)
	{
		$httpClient = $this->makeHTTPClient([
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_A'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A'],
		]);

		$res = $httpClient->get('/variety/four_zero_four');
		$res = $this->assertValidResponse($res, 404);

		// Find Early One
		$req_path = sprintf('/variety/%s', $Variety0['id']);
		$res = $httpClient->get($req_path);
		$res = $this->assertValidResponse($res);

		$this->assertIsArray($res['data']);

	}

	/**
	 * @depends test_create
	 */
	public function test_update($Variety0)
	{
		$httpClient = $this->makeHTTPClient([
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_A'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A'],
		]);

		$Variety0['name'] = sprintf('UNITTEST Variety UPDATE %06x', $this->_pid);
		$req_path = sprintf('/variety/%s', $Variety0['id']);

		$res = $httpClient->post($req_path, [ 'form_params' => [
			'name' => $Variety0['name'],
			'type' => 'Hemp'
		]]);

		// Check Response
		$res = $this->assertValidResponse($res, 200);
		$this->assertIsArray($res['data']);
		$Variety1 = $res['data'];
		$this->assertNotEmpty($Variety1['id']);
		$this->assertEquals($Variety0['name'], $Variety1['name']);

		// Fetch & Check Again
		$res = $httpClient->get($req_path);
		$res = $this->assertValidResponse($res);
		$this->assertIsArray($res['data']);
		$Variety1 = $res['data'];
		$this->assertEquals($Variety0['name'], $Variety1['name']);
	}

	/**
	 * @depends test_create
	 */
	public function test_delete($Variety0)
	{
		$httpClient = $this->makeHTTPClient([
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_A'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A'],
		]);

		$res = $httpClient->delete('/variety/four_zero_four');
		$this->assertValidResponse($res, 404);

		// First call to Delete gives 202
		$req_path = sprintf('/variety/%s', $Variety0['id']);
		$res = $httpClient->delete($req_path);
		$this->assertValidResponse($res, 202);

		// Second Call should give 410
		$res = $httpClient->delete($req_path);
		$this->assertValidResponse($res, 410);

		$res = $httpClient->delete($req_path);
		$this->assertValidResponse($res, 423);

	}

}
