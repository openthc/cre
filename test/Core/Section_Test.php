<?php
/**
 * Test Section Create/Update/Delete
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\Core;

class Section_Test extends \OpenTHC\CRE\Test\Base
{
	public function test_public_read()
	{
		// Reset Auth
		$this->httpClient = $this->_api();

		$res = $this->httpClient->get('/section');
		$this->assertValidResponse($res, 401);

		$res = $this->httpClient->get('/section/four_zero_four');
		$this->assertValidResponse($res, 401);

		$res = $this->httpClient->get('/section/1');
		$this->assertValidResponse($res, 401);

		$res = $this->httpClient->get('/section?' . http_build_query([
			'q' => 'UNITTEST'
		]));
		$this->assertValidResponse($res, 401);

	}


	/**
	 * @todo Missing request body parameters, assuming base parameters:
	 * - name, (string)
	 * - type, (string)
	 */
	public function test_create_fail()
	{
		$httpClient = $this->makeHTTPClient([
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_A'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A'],
		]);

		$arg = [
			'id' => _ulid()
		];
		$res = $httpClient->post('/section', [ 'form_params' => $arg ]);
		$res = $this->assertValidResponse($res, 400);

		$this->assertCount(2, $res);

		// $this->assertNotEmpty($res['id']);
		// $this->assertEquals('UNITTEST Section 031', $res['name']);
		//
		// // Can't create two objects with same ID
		// $arg = [
		// 	'name' => 'UNITTEST Section 042',
		// 	'type' => 'logical',
		// 	'id' => 'TSTCRE.OTSECTION1'
		// ];
		// $res = $this->_post($URI, $arg);
		// $this->assertValidResponse($res, 409);
		// // $this->asserEquals($res->getStatusCode(), 405); // Not 405
		//
		// $arg = [
		// 	'name' => 'UNITTEST Section 051',
		// 	'type' => 'physical',
		// ];
		// $res = $this->_post($URI, $arg);
		// $res = $this->assertValidResponse($res, 201);
		// $this->assertExists($res['id']);
		//
		// // Missing type
		// $arg = [
		// 	'name' => 'UNITTEST Section 061',
		// ];
		// $res = $this->_post($URI, $arg);
		// $res = $this->assertValidResponse($res, 405);
		//
		// // Missing type
		// $arg = [
		// 	'id' => 'TSTCRE.OTSECTION4',
		// 	'name' => 'UNITTEST Section 069',
		// ];
		// $res = $this->_post($URI, $arg);
		// $res = $this->assertValidResponse($res, 405);
		//
		// // Test strange names
		// $arg = [
		// 	'name' => 'UNITEST Section 🐛📒',
		// 	'type' => 'logical',
		// ];
		// $res = $this->_post($URI, $arg);
		// $res = $this->assertValidResponse($res, 405);
		//
		// $arg = [
		// 	'id' => 'TSTCRE.OTSECTION6',
		// 	'name' => '0x800000000001',
		// 	'type' => 'logical',
		// ];
		// $res = $this->_post($URI, $arg);
		// $res = $this->assertValidResponse($res);
		// $this->assertEquals('0x800000000008', $res['name']);
	}

	function test_create()
	{
		$httpClient = $this->makeHTTPClient([
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_A'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A'],
		]);

		$name = sprintf('UNITTEST Section CREATE %06x', $this->_pid);

		$res = $httpClient->post('/section', [ 'form_params' => [
			'name' => $name,
			'type' => 'inventory',
		]]);
		$res = $this->assertValidResponse($res, 201);

		$obj = $res['data'];
		$this->assertIsArray($obj);
		$this->assertCount(3, $obj);
		$this->assertEquals($name, $obj['name']);

		return $obj;

	}

	public function test_search()
	{
		$httpClient = $this->makeHTTPClient([
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_A'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A'],
		]);
		$res = $httpClient->get('/section?q=UNITTEST');
		$res = $this->assertValidResponse($res);

		$this->assertIsArray($res['meta']);
		$this->assertIsArray($res['data']);

		$obj = $res['data'][0];
		$this->assertIsArray($obj);
		$this->assertCount(3, $obj);

	}

	/**
	 * @depends test_create
	 */
	public function test_single($License0)
	{
		$httpClient = $this->makeHTTPClient([
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_A'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A'],
		]);

		$res = $httpClient->get('/section/four_zero_four');
		$this->assertValidResponse($res, 404);

		$req_path = sprintf('/section/%s', $License0['id']);
		$res = $httpClient->get($req_path);
		$res = $this->assertValidResponse($res);

		// More

	}

	/**
	 * @depends test_create
	 */
	public function test_update($License0)
	{
		$this->httpClient = $this->makeHTTPClient([
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_A'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A'],
		]);

		$License0['name'] = sprintf('UNITTEST Section CREATE-UPDATE %06x', $this->_pid);

		$req_path = sprintf('/section/%s', $License0['id']);
		$res = $this->_post($req_path, array(
			'name' => $License0['name'],
		));
		$res = $this->assertValidResponse($res, 200);

		$this->assertIsArray($res['meta']);
		$this->assertIsString($res['meta']['note']);
		$this->assertIsArray($res['data']);

		$License1 = $res['data'];
		$this->assertIsArray($License1);
		// $this->assertCount(3, $obj);
		$this->assertEquals($License0['name'], $License1['name']);

	}

	/**
	 * @depends test_create
	 */
	public function test_delete($License0)
	{
		$httpClient = $this->makeHTTPClient([
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_A'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A'],
		]);

		$res = $httpClient->delete('/section/four_zero_four');
		$this->assertValidResponse($res, 404);

		// First call to Delete gives 202
		$req_path = sprintf('/section/%s', $License0['id']);
		$res = $httpClient->delete($req_path);
		$this->assertValidResponse($res, 202);

		// Second Call should give 410
		$res = $httpClient->delete($req_path);
		$this->assertValidResponse($res, 410);

		$res = $httpClient->delete($req_path);
		$this->assertValidResponse($res, 423);

	}

	/**
	 * Create Test Rooms for G type license
	 */
	function test_create_g()
	{
		$this->httpClient = $this->makeHTTPClient([
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_A'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A'],
		]);

		$name = sprintf('UNITTEST Section-G CREATE %06x', $this->_pid);

		$res = $this->_post('/section', [
			'name' => $name,
		]);
		$res = $this->assertValidResponse($res, 201);

	}

	/**
	 * Create Test Rooms for P type license
	 */
	function test_create_p()
	{
		$this->httpClient = $this->makeHTTPClient([
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_B'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_B'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_B'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_B'],
		]);

		$name = sprintf('UNITTEST Section-P CREATE %06x', $this->_pid);

		$res = $this->_post('/section', [
			'name' => $name,
		]);
		$res = $this->assertValidResponse($res, 201);
	}

	/**
	 * Create Test Rooms for L type license
	 */
	function test_create_l()
	{
		$this->httpClient = $this->makeHTTPClient([
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_C'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_C'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_C'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_C'],
		]);

		$name = sprintf('UNITTEST Section-L CREATE %06x', $this->_pid);

		$res = $this->_post('/section', [
			'name' => $name,
		]);
		$res = $this->assertValidResponse($res, 201);
	}

	/**
	 * Create Test Rooms for R type license
	 */
	function test_create_r()
	{
		$this->httpClient = $this->makeHTTPClient([
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'], // Using someone elses service
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_D'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_D'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_D'],
		]);

		$name = sprintf('UNITTEST Section-R CREATE %06x', $this->_pid);

		$res = $this->_post('/section', [
			'name' => $name,
		]);
		$res = $this->assertValidResponse($res, 201);
	}

}
