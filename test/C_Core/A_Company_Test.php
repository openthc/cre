<?php
/**
 *
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\C_Core;

class A_Company_Test extends \OpenTHC\CRE\Test\Base
{
	protected function setUp() : void
	{
		parent::setUp();

		// $this->sid = $this->auth();

		$tok = $this->make_bearer_token([
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_A'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A'],
		]);

		$res = $this->httpClient->post('/auth/open', [
			'headers' => [
				'Authorization' => $tok,
			],
		]);
		$res = $this->assertValidResponse($res);

		$this->sid = $res['data']['sid'];
	}

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

	public function test_create_as_root()
	{
		// $this->auth($_ENV['OPENTHC_TEST_CLIENT_SERVICE_0'], $_ENV['OPENTHC_TEST_CLIENT_COMPANY_0'], $_ENV['OPENTHC_TEST_CLIENT_LICENSE_0']);
		// $tmp_client = $this->_api();
		$tok = $this->make_bearer_token([
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_0'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_0'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_0'],
		]);
		$res = $this->httpClient->post('/auth/open', [
			'headers' => [
				'Authorization' => $tok,
			],
		]);
		$res = $this->assertValidResponse($res);
		$sid = $res['data']['sid'];

		$res = $this->httpClient->post('/company', [
			'headers' => [
				'authorization' => sprintf('Bearer v2024/%s', $sid),
			],
			'form_params' => [
				'name' => 'UNITTEST Company CREATE',
				'code' => 'CO123456',
			]
		]);
		$res = $this->assertValidResponse($res, 201);
		$this->assertIsArray($res['data']);

		$c0 = $res['data'];
		$this->assertIsArray($c0);
		$this->assertNotEmpty($c0['id']);

		$this->_data_stash_put($c0);

	}

	public function test_search()
	{
		$res = $this->httpClient->get('/company');
		$res = $this->assertValidResponse($res);


		$res = $this->httpClient->get('/company?' . http_build_query([
			'name' => 'system'
		]));
		$this->assertValidResponse($res);

		$res = $this->httpClient->get('/company?' . http_build_query([
			'q' => 'UNITTEST'
		]));
		$res = $this->assertValidResponse($res);

		$this->assertIsArray($res);
		$this->assertCount(2, $res);
		$this->assertIsArray($res['data']);

		$c0 = $res['data'][0];
		$this->assertIsArray($c0);

		$c0 = $this->_data_stash_get();
		$res = $this->httpClient->get('/company/' . $c0['id']);
		$res = $this->assertValidResponse($res);
		$this->assertIsArray($res['data']);
	}

	public function test_single()
	{
		$res = $this->httpClient->get('/company/four_zero_four');
		$this->assertValidResponse($res, 404);

		// System
		$res = $this->httpClient->get(sprintf('/company/%s', $_ENV['OPENTHC_TEST_CLIENT_COMPANY_0']));
		$res = $this->assertValidResponse($res);
		$this->assertEquals('-system-', $res['data']['name']);

		$res = $this->httpClient->get('/company/' . $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A']);
		$this->assertValidResponse($res);

		$res = $this->httpClient->get('/company/' . $_ENV['OPENTHC_TEST_CLIENT_COMPANY_B']);
		$this->assertValidResponse($res);

		$res = $this->httpClient->get('/company/' . $_ENV['OPENTHC_TEST_CLIENT_COMPANY_C']);
		$this->assertValidResponse($res);

		$res = $this->httpClient->get('/company/' . $_ENV['api-company-d']);
		$this->assertValidResponse($res);

	}

	public function test_update()
	{
		$c0 = $this->_data_stash_get();

		$res = $this->_post('/company/' . $c0['id'],  [
			'name' => 'UNITTEST Company CREATE-UPDATE',
		]);
		$res = $this->assertValidResponse($res);

		//assert that the new name has updated appended to it
		$res = $this->httpClient->get('/company/' . $c0['id']);
		//FIXME: the returned response does not match the API specs, please look into this
		$res = $this->assertValidResponse($res);
		$this->assertIsArray($res['data']);
		$this->assertSame($res['data']['name'], 'UNITTEST Company CREATE-UPDATE');

		// Update phone
		// $this->assertTrue(false);

		// Update several fields
		// $this->assertTrue(false);
	}

	public function test_delete()
	{
		$res = $this->httpClient->delete('/company/four_zero_four');
		$this->assertValidResponse($res, 403);

		// do stuff?
		$res = $this->httpClient->delete('/company/' . $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A']);
		$this->assertValidResponse($res, 403);

		$res = $this->httpClient->delete('/company/' . $_ENV['OPENTHC_TEST_CLIENT_COMPANY_B']);
		$this->assertValidResponse($res, 403);

	}


	public function test_delete_as_root()
	{
		$this->auth($_ENV['OPENTHC_TEST_CLIENT_SERVICE_0'], $_ENV['OPENTHC_TEST_CLIENT_COMPANY_0'], $_ENV['OPENTHC_TEST_CLIENT_LICENSE_0']);

		$res = $this->httpClient->delete('/company/four_zero_four');
		$this->assertValidResponse($res, 404);


		// Make New Company
		$res = $this->_post('/company', [
			'name' => 'UNITTEST Company DELETE',
			'type' => 'Grower',
			'code' => 'DELETE',
		]);
		$res = $this->assertValidResponse($res, 201);

		$c0 = $res['data'];
		$this->assertNotEmpty($c0['id']);

		// Delete This Company
		$res = $this->httpClient->delete('/company/' . $c0['id']);
		$this->assertValidResponse($res, 405);
		// $this->assertEquals(405, $res->getStatusCode());
		// , 'Confirms we cannot delete Company');

	}
}
