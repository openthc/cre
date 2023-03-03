<?php
/**
 * OAuth Testing
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\B_Auth;

class B_oAuth2_Test extends \OpenTHC\CRE\Test\Base_Case
{
	function test_oauth_open()
	{
		// POST /auth/oauth
		$res = $this->httpClient->get('/auth/oauth');
		$this->assertValidResponse($res, 405);

		// Is Redirect to Auth Server
		$res = $this->httpClient->post('/auth/oauth');
		$this->assertValidResponse($res, 307, 'text/plain');
		$this->assertNotEmpty($res->getHeaderLine('location'));

		// POST to REAL oAuth2 EndPoint
		$res = $this->httpClient->post('/auth/oauth');
		$this->assertValidResponse($res, 307, 'text/plain');

		$loc = $res->getHeaderLine('location');
		$this->assertNotEmpty($loc);

		$res = $this->httpClient->get($loc);
		$this->assertEquals(405, $res->getStatusCode());

		$res = $this->httpClient->post($loc);
		$this->assertEquals(401, $res->getStatusCode());
		// $this->assertValidResponse($res, 401);

		$res = $this->httpClient->post($loc, [
			'client_id' => 'UNITTEST',
		]);
		$this->assertEquals(401, $res->getStatusCode());
		// $this->assertValidResponse($res, 401);

		$res = $this->httpClient->post($loc, [
			'client_id' => 'UNITTEST',
			'client_secret' => 'UNITTEST-SECRET',
		]);
		$this->assertEquals(401, $res->getStatusCode());
		// $this->assertValidResponse($res, 401);

	}

// 	function x_testToken_Request()
// 	{
// 		$q = http_build_query(array(
// 			'grant_type' => implode(',', array(
// 				'cultivation',
// 				'processing',
// 				'retail',
// 				'offline',
// 			))
// 		));
// 		$req = new Request('GET', 'oauth/token-request?' . $q);
//
// 		$res = $this->httpClient->send($req);
// 		$this->assertEquals(200, $res->getStatusCode());
// 		$this->assertEquals('application/json', $res->getHeader('Content-Type')[0]);
//
// 		// {"access_token":"03807cb390319329bdf6c777d4dfae9c0d3b3c35","expires_in":3600,"token_type":"bearer","scope":null}
// 		// Decipher JSON
//
// 	}

	function x_testToken_Authorize()
	{


	}

	function x_testToken_Access()
	{

	}

	public function x_test_OAuth()
	{
		$api = $this->_api();
		$res = $api->post('/auth/open', array(
			'client_id' => $_ENV['api-oauth-client-id'],
			'redirect_uri' => 'OPENTHC_TEST_REDIRECT_URI',
			'response_type' => 'code',
			//'scope' => 'photos',
			//'state' => '1234zyx'
		));
		$res = $this->assertValidResponse($res);

		// Code and State

		$res = $api->post('/token', array(
			'client_id' => 'OPENTHC_TEST_CLIENT_ID',
			'client_secret' => 'OPENTHC_TEST_CLIENT_SECRET',
			'redirect_uri' => 'OPENTHC_TEST_REDIRECT_URI',
			'grant_type' => 'authorization_code',
			'code' => '',
		));
		$res = $this->assertValidResponse($res);

		// Access Token, Expires In

		$head = array(
			sprintf('Authorization: Bearer %s', $res['token']),
		);
		$req = new \GuzzleHttp\Client\Request('GET', '/auth/ping', $head);

		$res = $res->send($req);
		$this->assertEquals('application/json;charset=utf-8', $res->getHeaderLine('Content-Type'));
		$this->assertEquals(200, $res->getStatusCode());

	}

}
