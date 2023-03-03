<?php
/**
 * Test crop Stuff
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\E_Crop;

class A_Alpha_Test extends \OpenTHC\CRE\Test\Base_Case
{
	protected function setUp() : void
	{
		parent::setUp();
		$this->auth($_ENV['api-service-a'], $_ENV['api-company-a'], $_ENV['api-license-a']);
	}

	public function test_create()
	{
		$sl0 = $this->find_random_lot(); // of type Seed or Clone?
		$v = $this->find_random_variety();
		$s = $this->find_random_section();

		$res = $this->_post('/crop', [
			'source' => '', // A Lot of Clones, crops or Seeds
			'variety' => $v['id'], // A New Variety
			'section' => $s['id'], // Optional
			'qty' => 10,
		]);
		$res = $this->assertValidResponse($res, 201);
		$this->assertIsArray($res);
		$this->assertIsArray($res['data']);

		$obj = $res['data'];
		$this->_data_stash_put($obj);

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

	public function test_update()
	{
		// The one we recently created
		$obj = $this->_data_stash_get();
		$res = $this->httpClient->get('/crop/' . $obj['id']);
		$res = $this->assertValidResponse($res);

		$this->_post('/crop/' . $obj['id'], [
			'name' => 'UNITTEST crop CREATE-UPDATE',
		]);

	}

	function test_delete()
	{
		// 404
		$res = $this->httpClient->delete('/crop/four_zero_four');
		$this->assertValidResponse($res, 404);

		// $obj = $this->_data_stash_get();
		//
		// $res = $this->httpClient->delete('/crop/' . $obj['id']);
		// $this->assertValidResponse($res, 202, 'fdafdas');
		//
		// $res = $this->httpClient->delete('/crop/' . $obj['id']);
		// $this->assertValidResponse($res, 410);
		//
		// $res = $this->httpClient->delete('/crop/' . $obj['id']);
		// $this->assertValidResponse($res, 423);


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
// 	$this->auth('user', 'pass');
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
