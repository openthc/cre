<?php
/**
 *
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\Core;

class Company_Test extends \OpenTHC\CRE\Test\Base
{
	public function test_public_read()
	{
		// Reset Auth
		$tmp_client = $this->_api();

		$res = $tmp_client->get('/company');
		$this->assertValidResponse($res, 401);

		$res = $tmp_client->get('/company/four_zero_four');
		$this->assertValidResponse($res, 401);

		$res = $tmp_client->get('/company/1');
		$this->assertValidResponse($res, 401);

		$res = $tmp_client->get('/company?' . http_build_query([
			'q' => 'UNITTEST'
		]));
		$this->assertValidResponse($res, 401);

	}

	/**
	 *
	 */
	public function test_create_as_root()
	{
		$httpClient = $this->makeHTTPClient();
		$res = $httpClient->post('/company', [ 'form_params' => [
				'name' => 'UNITTEST Company CREATE',
				'code' => 'CO123456',
			]
		]);
		$res = $this->assertValidResponse($res, 201);
		$this->assertIsArray($res['data']);

		$c0 = $res['data'];
		$this->assertIsArray($c0);
		$this->assertNotEmpty($c0['id']);

		return $c0;

	}

	/**
	 * @depends test_create_as_root
	 */
	public function test_search($Company0)
	{
		$res = $this->httpClient->get('/company');
		$res = $this->assertValidResponse($res, 401);

		// Readonly Access to new Company?
		$req_path = sprintf('/company/%s', $Company0['id']);
		$res = $this->httpClient->get($req_path);
		$res = $this->assertValidResponse($res, 401);

		// As Root
		$httpClient = $this->makeHTTPClient();
		$req_path = '/company?' . http_build_query([
			'name' => 'system'
		]);
		$res = $httpClient->get($req_path);
		$this->assertValidResponse($res);

		$req_path = '/company?' . http_build_query([
			'q' => 'UNITTEST'
		]);
		$res = $httpClient->get($req_path);
		$res = $this->assertValidResponse($res);

		$this->assertIsArray($res);
		$this->assertCount(2, $res);
		$this->assertIsArray($res['data']);

		$Company1 = $res['data'][0];
		$this->assertIsArray($Company1);
	}

	public function test_single()
	{
		$res = $this->httpClient->get('/company/four_zero_four');
		$this->assertValidResponse($res, 401);

		$httpClient = $this->makeHTTPClient([
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_A'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A'],
		]);

		$res = $httpClient->get('/company/four_zero_four');
		$this->assertValidResponse($res, 404);

		// System
		$req_path = sprintf('/company/%s', $_ENV['OPENTHC_TEST_CLIENT_COMPANY_0']);
		$res = $httpClient->get($req_path);
		$res = $this->assertValidResponse($res);
		$this->assertEquals('-system-', $res['data']['name']);

		$req_path = sprintf('/company/%s', $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A']);
		$res = $httpClient->get($req_path);
		$res = $this->assertValidResponse($res);

		$req_path = sprintf('/company/%s', $_ENV['OPENTHC_TEST_CLIENT_COMPANY_B']);
		$res = $httpClient->get($req_path);
		$res = $this->assertValidResponse($res);

		$req_path = sprintf('/company/%s', $_ENV['OPENTHC_TEST_CLIENT_COMPANY_C']);
		$res = $httpClient->get($req_path);
		$res = $this->assertValidResponse($res);

		$req_path = sprintf('/company/%s', $_ENV['OPENTHC_TEST_CLIENT_COMPANY_D']);
		$res = $httpClient->get($req_path);
		$res = $this->assertValidResponse($res);

	}

	/**
	 * @depends test_create_as_root
	 */
	public function test_update($c0)
	{
		$httpClient = $this->makeHTTPClient([
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_A'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A'],
		]);

		$req_path = sprintf('/company/%s', $c0['id']);
		$req_opts = [ 'form_params' => [
				'name' => 'UNITTEST Company CREATE-UPDATE',
			],
		];
		$res = $httpClient->post($req_path, $req_opts);
		$res = $this->assertValidResponse($res);

		//assert that the new name has updated appended to it
		$res = $httpClient->get($req_path);
		//FIXME: the returned response does not match the API specs, please look into this
		$res = $this->assertValidResponse($res);
		$this->assertIsArray($res['data']);
		$this->assertSame($res['data']['name'], 'UNITTEST Company CREATE-UPDATE');

		// Update phone
		// $this->assertTrue(false);

		// Update several fields
		// $this->assertTrue(false);

		return $c0;
	}

	/**
	 * @depends test_update
	 */
	public function test_delete($c0)
	{
		$httpClient = $this->makeHTTPClient([
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_A'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A'],
		]);

		$res = $httpClient->delete('/company/four_zero_four');
		$this->assertValidResponse($res, 404);

		$req_path = sprintf('/company/%s', $c0['id']);
		$res = $httpClient->delete($req_path);
		$this->assertValidResponse($res, 405);

		// $res = $httpClient->delete($req_path);
		// $this->assertValidResponse($res, 405);

		return $c0;
	}

	/**
	 * @depends test_delete
	 */
	public function test_delete_as_root($c0)
	{
		// Root
		$httpClient = $this->makeHTTPClient();
		$res = $httpClient->delete('/company/four_zero_four');
		$this->assertValidResponse($res, 404);

		$req_path = sprintf('/company/%s', $c0['id']);
		$res = $httpClient->delete($req_path);
		$this->assertValidResponse($res, 405);

		// // Make New Company
		// $res = $this->_post('/company', [
		// 	'name' => 'UNITTEST Company DELETE',
		// 	'type' => 'Grower',
		// 	'code' => 'DELETE',
		// ]);
		// $res = $this->assertValidResponse($res, 201);

		// $c0 = $res['data'];
		// $this->assertNotEmpty($c0['id']);

		// // Delete This Company
		// $res = $this->httpClient->delete('/company/' . $c0['id']);
		// $this->assertValidResponse($res, 405);
		// // $this->assertEquals(405, $res->getStatusCode());
		// // , 'Confirms we cannot delete Company');

	}
}

// OpenTHC\CRE\Test\System\Config_Test
// OpenTHC\CRE\Test\Unit\Middleware_Check_Authorization_Test
