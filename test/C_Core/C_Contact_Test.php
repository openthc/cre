<?php
/**
 *
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\C_Core;

class C_Contact_Test extends \OpenTHC\CRE\Test\Base
{
	protected $_tmp_file = '/tmp/unit-test-contact.json';

	protected function setUp() : void
	{
		parent::setUp();
		$this->auth(OPENTHC_TEST_CLIENT_SERVICE_A, OPENTHC_TEST_CLIENT_COMPANY_A, OPENTHC_TEST_CLIENT_LICENSE_A);
	}

	public function test_public_read()
	{
		// Reset Auth
		$this->httpClient = $this->_api();

		$res = $this->httpClient->get('/contact');
		$this->assertValidResponse($res, 403);

		$res = $this->httpClient->get('/contact/four_zero_four');
		$this->assertValidResponse($res, 403);

		$res = $this->httpClient->get('/contact/1');
		$this->assertValidResponse($res, 403);

		$res = $this->httpClient->get('/contact?' . http_build_query([
			'q' => 'UNITTEST'
		]));
		$this->assertValidResponse($res, 403);

	}

	public function test_create()
	{
		$res = $this->_post('/contact', [
			'company' => OPENTHC_TEST_CLIENT_COMPANY_A,
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
		$res = $this->httpClient->get('/contact?' . http_build_query([
			'q' => 'UNITTEST',
		]));
		$res = $this->assertValidResponse($res);

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


	public function test_single()
	{
		$res = $this->httpClient->get('/contact/four_zero_four');
		$this->assertValidResponse($res, 404);

		$res = $this->httpClient->get(sprintf('/contact/%s', OPENTHC_TEST_CLIENT_CONTACT_0));
		$this->assertValidResponse($res);

		$obj = $this->_data_stash_get();
		$res = $this->httpClient->get(sprintf('/contact/%s', $obj['id']));
		$res = $this->assertValidResponse($res);
		$this->assertIsArray($res['data']);
	}

	public function test_update()
	{
		$obj = $this->_data_stash_get();
		$res = $this->_post('/contact/' . $obj['id'], [
			'name' => 'UNITTEST Contact CREATE-UPDATE'
		]);
		$res = $this->assertValidResponse($res);

		$res = $this->httpClient->get(sprintf('/contact/%s', $obj['id']));
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

	public function test_delete()
	{
		$res = $this->httpClient->delete('/contact/four_zero_four');
		$this->assertValidResponse($res, 404);

		$c0 = $this->_data_stash_get();

		// Two Times to Delete?
		$res = $this->httpClient->delete('/contact/' . $c0['id']);
		$this->assertValidResponse($res, 423);

		$res = $this->httpClient->delete('/contact/' . $c0['id']);
		$this->assertValidResponse($res, 410);


	}

	public function test_create_contact_with_email_and_phone()
	{
		// $id = bin2hex(random_bytes(12));
		$email = sprintf('%s@test.openthc.example.com', _ulid());
		$phone = '2345678910';
		$res = $this->_post('/contact', [
			// 'id' => $id,
			'company' => OPENTHC_TEST_CLIENT_COMPANY_A,
			'name' => 'UNITTEST Contact CREATE',
			'email' => $email,
			'phone' => $phone
		]);
		$res = $this->assertValidResponse($res, 201);
		$this->assertIsArray($res['data']);

		// $res = $res['data'];
		// $this->_data_stash_put($res);

		// test that the email and phone is returned
		$res = $this->httpClient->get(sprintf('/contact/%s', $res['data']['id']));
		// print_r($res->getBody()->getContents());
		$res = $this->assertValidResponse($res);
		$this->assertIsArray($res['data']);

		// print_r($res);
		// TODO:
		$this->assertSame($res['data']['email'], $email);
		$this->assertSame($res['data']['phone'], $phone);

	}

}
