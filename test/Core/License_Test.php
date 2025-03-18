<?php
/**
 *
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\C_Core;

class B_License_Test extends \OpenTHC\CRE\Test\Base
{
	public function test_public_read()
	{
		$httpClient = $this->_api();

		$res = $httpClient->get('/license');
		$this->assertValidResponse($res, 401);

		$res = $httpClient->get('/license/four_zero_four');
		$this->assertValidResponse($res, 401);

		$res = $httpClient->get('/license/1');
		$this->assertValidResponse($res, 401);

		$res = $httpClient->get('/license?' . http_build_query([
			'q' => 'UNITTEST'
		]));
		$this->assertValidResponse($res, 401);

	}

	/**
	 *
	 */
	function test_create_as_root()
	{
		$httpClient = $this->makeHTTPClient();
		$res = $httpClient->post('/license', [ 'form_params' => [
			'company' => [
				'id' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
			],
			'name' => 'UNITTEST License CREATE',
			'type' => 'Grower',
		]]);
		$res = $this->assertValidResponse($res, 201);
		$this->assertIsArray($res['data']);
		$this->assertCount(4, $res['data']);

		return $res['data'];
	}

	/**
	 *
	 */
	function test_search()
	{
		$httpClient = $this->makeHTTPClient();

		$res = $httpClient->get('/license');
		$res = $this->assertValidResponse($res, 200);
		$this->assertIsArray($res['data']);

		$res = $httpClient->get('/license?q=UNITTEST');
		$res = $this->assertValidResponse($res, 200);
		$this->assertIsArray($res['data']);

		// $this->assertGreaterThan(1, count($res['data']));

	}

	/**
	 * @depends test_create_as_root
	 */
	function test_single($License0)
	{
		$httpClient = $this->makeHTTPClient();

		$res = $httpClient->get('/license/four_zero_four');
		$this->assertValidResponse($res, 404);

		$res = $httpClient->get(sprintf('/license/%s', $_ENV['OPENTHC_TEST_CLIENT_LICENSE_0']));
		$res = $this->assertValidResponse($res);
		$this->assertIsArray($res['data']);
		$this->assertEquals('-system-', $res['data']['name']);

		$res = $httpClient->get(sprintf('/license/%s', $License0['id']));
		$res = $this->assertValidResponse($res, 200);
		$this->assertIsArray($res['data']);
		$this->assertGreaterThan(1, count($res['data']));
	}

	/**
	 * @depends test_create_as_root
	 */
	function test_update_as_root($License0)
	{
		$httpClient = $this->makeHTTPClient();

		$req_path = sprintf('/license/%s', $License0['id']);
		$res = $httpClient->post($req_path, [ 'form_params' => [
			'company_id' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
			'name' => 'UNITTEST License CREATE-UPDATE',
		]]);

		$res = $this->assertValidResponse($res);
		$this->assertIsArray($res['data']);

		//validate the name was changed
		$res = $httpClient->get($req_path);
		$res = $this->assertValidResponse($res, 200);
		$this->assertIsArray($res['data']);
		// $this->assertCount(4, $res['data']);
		$this->assertSame($res['data']['name'], 'UNITTEST License CREATE-UPDATE');

		return $res['data'];
	}

	/**
	 * @depends test_update_as_root
	 */
	function test_delete_as_root($License0)
	{
		$httpClient = $this->makeHTTPClient();

		$req_path = sprintf('/license/%s', $License0['id']);
		$res = $httpClient->delete($req_path);
		$this->assertValidResponse($res, 405);

	}

}
