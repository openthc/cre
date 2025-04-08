<?php
/**
 * Test crop Stuff
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\Crop;

class Alpha_Test extends \OpenTHC\CRE\Test\Base
{
	protected function setUp() : void
	{
		parent::setUp();
		$this->httpClient = $this->makeHTTPClient([
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_A'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A'],
		]);
	}

	public function test_create()
	{
		$sl0 = $this->find_random_inventory(); // of type Seed or Clone?
		$v = $this->find_random_variety();
		$s = $this->find_random_section();

		$res = $this->_post('/crop', [
			'source' => '', // Inventory of Clones, crops or Seeds
			'variety' => $v['id'], // A New Variety
			'section' => $s['id'], // Optional
			'qty' => 10,
		]);
		$res = $this->assertValidResponse($res, 201);
		$this->assertIsArray($res);
		$this->assertIsArray($res['data']);

		$Crop0 = $res['data'];

		return $Crop0;

	}


	public function test_search()
	{
		$res = $this->httpClient->get('/crop');
		$this->assertValidResponse($res, 200);


		$res = $this->httpClient->get('/crop?variety=');
		$this->assertValidResponse($res, 200);

		$res = $this->httpClient->get('/crop?section=');
		$this->assertValidResponse($res, 200);

	}

	public function test_single()
	{
		// 404
		$res = $this->httpClient->get('/crop/four_zero_four');
		$this->assertValidResponse($res, 404);

		// // NAME
		// $res = $this->httpClient->get('/crop/name/WeedTraQR');
		// $this->assertEquals(200, $res->getStatusCode());
		//
		// $res = $this->httpClient->get('/crop?' . http_build_query([
		// 	'name' => 'WeedTraQR'
		// ]));
		//
		// // Partial Name
		// $res = $this->httpClient->get('/crop/name/WeedTra');
		// $this->assertEquals(200, $res->getStatusCode());
		//
		// $res = $this->httpClient->get('/crop?' . http_build_query([
		// 	'name' => 'WeedTra'
		// ]));
		// $this->assertTrue(false);
	}

	/**
	 * @depends test_create
	 */
	public function test_update($Crop0)
	{
		$this->assertNotEmpty($Crop0);

		// The one we recently created
		$req_path = sprintf('/crop/%s', $Crop0['id']);
		$res = $this->httpClient->get($req_path);
		$res = $this->assertValidResponse($res);

		$this->_post($req_path, [
			'name' => 'UNITTEST crop CREATE-UPDATE',
		]);

	}

	/**
	 * @depends test_create
	 */
	function test_delete($Crop0)
	{
		$this->assertNotEmpty($Crop0);

		// 404
		$res = $this->httpClient->delete('/crop/four_zero_four');
		$this->assertValidResponse($res, 404);

		$req_path = sprintf('/crop/%s', $Crop0['id']);
		$res = $this->httpClient->delete($req_path);
		$this->assertValidResponse($res, 202, 'fdafdas');

		$res = $this->httpClient->delete($req_path);
		$this->assertValidResponse($res, 410);

		$res = $this->httpClient->delete($req_path);
		$this->assertValidResponse($res, 423);


	}

}


// public function testcrop()
// {
// 	/**
// 	 * Unauthenticated Tests
// 	 */
// 	$response = $this->httpClient->get('/crop');
// 	$this->assertEquals(403, $response->getStatusCode());
//
// 	$response = $this->httpClient->get('/crop/FourOhFour');
// 	$this->assertEquals(403, $response->getStatusCode());
//
// 	$response = $this->httpClient->get('/crop/1');
// 	$this->assertEquals(403, $response->getStatusCode());
//
// 	$response = $this->httpClient->get('/crop?' . http_build_query([
// 		'guid' => '1',
// 	]));
// 	$this->assertEquals(403, $response->getStatusCode());
//
// 	/**
// 	 * Authenticated Tests
// 	 */
// 	$response = $this->httpClient->get('/crop');
// 	$this->assertEquals(200, $response->getStatusCode());
//
// 	$response = $this->httpClient->get('/crop/FourOhFour');
// 	$this->assertEquals(404, $response->getStatusCode());
//
// 	$response = $this->httpClient->get('/crop/1');
// 	$this->assertEquals(200, $response->getStatusCode());
//
// 	$response = $this->httpClient->get('/crop?' . http_build_query([
// 		'guid' => '1'
// 	]));
// 	$this->assertEquals(200, $response->getStatusCode());
// }
