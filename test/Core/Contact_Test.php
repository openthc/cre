<?php
/**
 *
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\C_Core;

class C_Contact_Test extends \OpenTHC\CRE\Test\Base
{
	public function test_public_read()
	{
		// Reset Auth
		$this->httpClient = $this->_api();

		$res = $this->httpClient->get('/contact');
		$this->assertValidResponse($res, 401);

		$res = $this->httpClient->get('/contact/four_zero_four');
		$this->assertValidResponse($res, 401);

		$res = $this->httpClient->get('/contact/1');
		$this->assertValidResponse($res, 401);

		$res = $this->httpClient->get('/contact?' . http_build_query([
			'q' => 'UNITTEST'
		]));
		$this->assertValidResponse($res, 401);

	}

	public function test_create()
	{
		$httpClient = $this->makeHTTPClient();

		$res = $httpClient->post('/contact', [ 'form_params' => [
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
			'name' => 'UNITTEST Contact CREATE',
		]]);
		$res = $this->assertValidResponse($res, 201);

		$this->assertIsArray($res);
		$this->assertIsArray($res['data']);

		return $res['data'];

	}

	/**
	 * @depends test_create
	 */
	public function test_search()
	{
		$httpClient = $this->makeHTTPClient();

		$res = $httpClient->get('/contact?' . http_build_query([
			'q' => 'UNITTEST',
		]));
		$res = $this->assertValidResponse($res);

		// $creClient = \OpenTHC\CRE::factory($cfg);
		// $creClient->contact()->search('FOO');

		// Name
		// $res = $this->httpClient->get('/contact/name');
		// $this->assertEquals(200, $res->getStatusCode());
		//
		// $res = $this->httpClient->get('/contact/name/WeedTraQR');
		// $this->assertEquals(200, $res->getStatusCode());
		// // SQLSTATE[42P01]: Undefined table: 7 ERROR:  relation 'contact' does not exist
		// // LINE 1: SELECT * FROM contact WHERE guid = $1
		// // echo($res->getBody()->getContents());die;

		// $res = $this->httpClient->get('/contact?' . http_build_query([
		// 	'name' => 'WeedTraQR',
		// ]));
		// $this->assertTrue(false);

		// Partial Name
		// $res = $this->httpClient->get('/contact/name/OpenT');
		// $this->assertEquals(200, $res->getStatusCode());

		// $res = $this->httpClient->get('/contact?' . http_build_query([
		// 	'name' => 'OpenT',
		// 	]));
		// $this->assertTrue(false);

	}

	/**
	 * @depends test_create
	 */
	public function test_single($Contact0)
	{
		$httpClient = $this->makeHTTPClient();

		$res = $httpClient->get('/contact/four_zero_four');
		$this->assertValidResponse($res, 404);

		$res = $httpClient->get(sprintf('/contact/%s', $_ENV['OPENTHC_TEST_CLIENT_CONTACT_0']));
		$this->assertValidResponse($res);

		$res = $httpClient->get(sprintf('/contact/%s', $Contact0['id']));
		$res = $this->assertValidResponse($res);
		$this->assertIsArray($res['data']);
	}

	/**
	 * @depends test_create
	 */
	public function test_update($Contact0)
	{
		$httpClient = $this->makeHTTPClient();

		$res = $httpClient->post('/contact/' . $Contact0['id'], [ 'form_params' => [
			'name' => 'UNITTEST Contact CREATE-UPDATE'
		]]);
		$res = $this->assertValidResponse($res);

		$res = $httpClient->get(sprintf('/contact/%s', $Contact0['id']));
		$res = $this->assertValidResponse($res);
		$this->assertIsArray($res['data']);
		$this->assertSame($res['data']['name'], 'UNITTEST Contact CREATE-UPDATE');

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

	/**
	 * @depends test_create
	 */
	public function test_delete($Contact0)
	{
		$res = $this->httpClient->delete('/contact/four_zero_four');
		$this->assertValidResponse($res, 401);

		// Two Times to Delete?
		$req_path = sprintf('/contact/%s', $Contact0['id']);
		$res = $this->httpClient->delete($req_path);
		$this->assertValidResponse($res, 401);
		// $this->assertValidResponse($res, 423);

		// $res = $this->httpClient->delete($req_path);
		// $this->assertValidResponse($res, 410);


	}

	/**
	 * @depends test_create
	 */
	public function test_delete_as_root($Contact0)
	{
		$httpClient = $this->makeHTTPClient();
		$res = $httpClient->delete('/contact/four_zero_four');
		$this->assertValidResponse($res, 404);

		// Two Times to Delete?
		$req_path = sprintf('/contact/%s', $Contact0['id']);
		$res = $httpClient->delete($req_path);
		$this->assertValidResponse($res, 423);

		$res = $httpClient->delete($req_path);
		$this->assertValidResponse($res, 410);

	}

	public function test_create_contact_with_email_and_phone()
	{
		$httpClient = $this->makeHTTPClient();
		// $id = bin2hex(random_bytes(12));
		// $email = sprintf('%s@test.openthc.example.com', _ulid());
		// $phone = '2345678910';
		$Contact0 = [
			'id' => _ulid(),
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
			'name' => 'UNITTEST Contact CREATE',
			// 'email' => $email,
			// 'phone' => $phone
		];
		$res = $httpClient->post('/contact', [ 'form_params' => $Contact0 ]);
		$res = $this->assertValidResponse($res, 201);
		$this->assertIsArray($res['data']);

		// test that the email and phone is returned
		$res = $httpClient->get(sprintf('/contact/%s', $res['data']['id']));
		$res = $this->assertValidResponse($res);
		$this->assertIsArray($res['data']);

		$this->assertSame($Contact0['id'], $res['data']['id']);

		// TODO:
		// $this->assertSame($res['data']['email'], $email);
		// $this->assertSame($res['data']['phone'], $phone);

	}

}
