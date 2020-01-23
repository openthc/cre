<?php
/**
 *
 */

namespace Test\Basic;

class Contact extends \Test\Components\OpenTHC_Test_Case
{
	protected $_tmp_file = '/tmp/unit-test-contact.json';

	protected function setUp() : void
	{
		parent::setUp();
		$this->auth($_ENV['api-program-a'], $_ENV['api-company-g0'], $_ENV['api-license-g0']);
	}

	public function test_public_read()
	{
		// Reset Auth
		$this->httpClient = $this->_api();

		$res = $this->httpClient->get('/config/contact');
		$this->assertValidResponse($res, 403);

		$res = $this->httpClient->get('/config/contact/four_zero_four');
		$this->assertValidResponse($res, 403);

		$res = $this->httpClient->get('/config/contact/1');
		$this->assertValidResponse($res, 403);

		$res = $this->httpClient->get('/config/contact?' . http_build_query([
			'q' => 'UNITTEST'
		]));
		$this->assertValidResponse($res, 403);

	}

	public function test_create()
	{
		$res = $this->_post('/config/contact', [
			'company' => $_ENV['api-company-g0'],
			'name' => 'UNITTEST Contact CREATE',
		]);
		$res = $this->assertValidResponse($res, 201);

		$this->assertIsArray($res);
		$this->assertIsArray($res['data']);

		$res = $res['data'];
		$this->_data_stash_put($res);

	}

	public function test_search()
	{
		$res = $this->httpClient->get('/config/contact?' . http_build_query([
			'q' => 'UNITTEST',
		]));
		$res = $this->assertValidResponse($res);

		// Name
		// $res = $this->httpClient->get('/config/contact/name');
		// $this->assertEquals(200, $res->getStatusCode());
		//
		// $res = $this->httpClient->get('/config/contact/name/WeedTraQR');
		// $this->assertEquals(200, $res->getStatusCode());
		// // SQLSTATE[42P01]: Undefined table: 7 ERROR:  relation 'contact' does not exist
		// // LINE 1: SELECT * FROM contact WHERE guid = $1
		// // echo($res->getBody()->getContents());die;

		// $res = $this->httpClient->get('/config/contact?' . http_build_query([
		// 	'name' => 'WeedTraQR',
		// ]));
		// $this->assertTrue(false);

		// Partial Name
		// $res = $this->httpClient->get('/config/contact/name/OpenT');
		// $this->assertEquals(200, $res->getStatusCode());

		// $res = $this->httpClient->get('/config/contact?' . http_build_query([
		// 	'name' => 'OpenT',
		// 	]));
		// $this->assertTrue(false);

	}


	public function test_single()
	{
		$res = $this->httpClient->get('/config/contact/four_zero_four');
		$this->assertValidResponse($res, 404);

		$res = $this->httpClient->get('/config/contact/019KAGVX9MQRRV9H0G9N3Q9FMC');
		$this->assertValidResponse($res);

	}

	public function test_update()
	{
		$obj = $this->_data_stash_get();
		// var_dump($obj);
		$res = $this->_post('/config/contact/' . $obj['id'], [
			'name' => 'UNITTEST Contact CREATE-UPDATE'
		]);
		$res = $this->assertValidResponse($res);

		// // Update guid
		// $this->assertTrue(false);
		//
		// // Update name
		// $this->assertTrue(false);
		//
		// // Update phone
		// $this->assertTrue(false);
		//
		// // Update several fields
		// $this->assertTrue(false);
	}

	// public function testEmployeeModify()
	// {
	// 	$this->assertTrue(false);
	// }
	//
	// public function testEmployeeRemove() {
	// 	$this->assertTrue(false);
	// }
	//
	// public function testEmployeeAdd() {
	// 	$this->assertTrue(false);
	// }

	// public function testUserModify() {
	// 	$this->assertTrue(false);
	// }
	//
	// public function testUserRemove() {
	// 	$this->assertTrue(false);
	// }
	//
	// public function testUserAdd() {
	// 	$this->assertTrue(false);
	// }

	public function test_delete()
	{
		$res = $this->httpClient->delete('/config/contact/four_zero_four');
		$this->assertValidResponse($res, 404);

		$c0 = $this->_data_stash_get();
		var_dump($c0);

		// Two Times to Delete?
		$res = $this->httpClient->delete('/config/contact/' . $c0['id']);
		$this->assertValidResponse($res, 423);

		$res = $this->httpClient->delete('/config/contact/' . $c0['id']);
		$this->assertValidResponse($res, 410);


	}

}
