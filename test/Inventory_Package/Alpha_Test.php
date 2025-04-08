<?php
/**
 * Test Packaging Functions
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\Inventory_Package;

class Alpha_Test extends \OpenTHC\CRE\Test\Base
{
	/**
	 * Unauthenticated Tests
	 */
	public function test_access()
	{
		$res = $this->httpClient->get('/inventory');
		$this->assertValidResponse($res, 403);

		$res = $this->httpClient->get('/inventory/four_zero_four');
		$this->assertValidResponse($res, 403);

		$res = $this->httpClient->get('/inventory/1');
		$this->assertValidResponse($res, 403);

		$res = $this->httpClient->get('/inventory?' . http_build_query([
			'guid' => '1',
		]));
		$this->assertValidResponse($res, 403);

		$res = $this->_post('/inventory/four_zero_four', [
			'qty' => 1,
		]);
		$this->assertValidResponse($res, 405);

	}

	/**
	 * Authenticated Tests
	 */
	public function test_access_auth()
	{
		$this->httpClient = $this->makeHTTPClient([
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_A'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A'],
		]);

		$res = $this->httpClient->get('/inventory');
		$this->assertValidResponse($res);

		$res = $this->httpClient->get('/inventory/four_zero_four');
		$this->assertValidResponse($res, 404);

	}

	public function test_create()
	{
		$this->httpClient = $this->makeHTTPClient([
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_A'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A'],
		]);

		$l0 = $this->find_random_inventory();
		$p0 = $this->find_random_product();

		$res = $this->_post('/inventory', [
			'source' => $l0['id'],
			'product_id' => $p0['id'],
			'qty' => 500,
		]);
		$res = $this->assertValidResponse($res, 201);

		$this->assertIsArray($res);
		$this->assertCount(2, $res);
		$this->assertIsArray($res['data']);

		$l1 = $res['data'];

	}

	function test_convert()
	{
		$this->httpClient = $this->makeHTTPClient([
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_A'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A'],
		]);

		$l0 = $this->find_random_inventory();
		$p0 = $this->find_random_product();

		$res = $this->_post('/inventory', [
			'source' => $l0['id'],
			'product_id' => $p0['id'],
			'qty' => 10,
		]);
		$res = $this->assertValidResponse($res, 201);

		$this->assertIsArray($res);
		$this->assertCount(2, $res);
		$this->assertIsArray($res['data']);

		$l1 = $res['data'];

	}

	public function test_delete()
	{
		$res = $this->httpClient->delete('/inventory/four_zero_four');
		$this->assertValidResponse($res, 403);

		$this->httpClient = $this->makeHTTPClient([
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_A'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A'],
		]);

		$res = $this->httpClient->delete('/inventory/four_zero_four');
		$this->assertValidResponse($res, 404);

		$l0 = $this->find_random_inventory();

	}

	public function test_search()
	{
		$this->httpClient = $this->makeHTTPClient([
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_A'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A'],
		]);

		$res = $this->httpClient->get('/inventory');
		$this->assertValidResponse($res);

	}

	public function test_single_404()
	{
		$this->httpClient = $this->makeHTTPClient([
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_A'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A'],
		]);

		$res = $this->httpClient->get('/inventory/four_zero_four');
		$this->assertValidResponse($res, 404);
	}

	public function test_update()
	{
		$this->httpClient = $this->makeHTTPClient([
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_A'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A'],
		]);

		$l0 = $this->find_random_inventory();
		$l0['qty'] = floatval($l0['qty']);
		$l0['qty'] = $l0['qty'] * 2;

		$res = $this->httpClient->patch('/inventory/' . $l0['id'], [ 'json' => $l0 ]);
		$res = $this->assertValidResponse($res, 200);

		$this->assertIsArray($res['data']);
		$this->assertCount(13, $res['data']);

		$l1 = $res['data'];
		$this->assertNotEmpty($l1['hash']);
		$this->assertEquals($l0['id'], $l1['id']);
		$this->assertEquals($l0['qty'], $l1['qty']);
		$this->assertNotEquals($l0['hash'], $l1['hash']);

	}

}
