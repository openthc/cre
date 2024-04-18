<?php
/**
 * Session Testing
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\B_Auth;

class C_Session_Test extends \OpenTHC\CRE\Test\Base
{
	protected $_tmp_file = '/tmp/cre-test-auth-session.dat';

	function test_session_create()
	{
		$res = $this->_post('/auth/open', [
			'service' => OPENTHC_TEST_CLIENT_SERVICE_A,
			'company' => OPENTHC_TEST_CLIENT_COMPANY_A,
			'license' => OPENTHC_TEST_CLIENT_LICENSE_A
		]);

		// Check for Cookie
		$sch = $res->getHeaderLine('set-cookie');
		$res = $this->assertValidResponse($res);

		$this->assertIsArray($res);
		$this->assertCount(2, $res);
		$this->assertMatchesRegularExpression('/\w{26,256}/', $res['data']);
		$this->assertMatchesRegularExpression('/^openthc\-cre=.+path.+secure.+httponly.+samesite/i', $sch);

		// Ping It
		$res = $this->httpClient->get('/auth/ping');
		$res = $this->assertValidResponse($res);

		$this->_data_stash_put($res['data']);

	}

	function test_session_lifetime()
	{
		// Add this Cookie?
		$prev = $this->_data_stash_get();
		$this->assertNotEmpty($prev['sid']);

		$jar = new \GuzzleHttp\Cookie\CookieJar();
		$cke = [
			'Domain' => $this->httpClient->getConfig('base_uri')->getHost(),
			'Name' => 'openthc',
			'Value' => $prev['sid'],
			'Secure' => true,
			'HttpOnly' => true,
		];
		$c = new \GuzzleHttp\Cookie\SetCookie($cke);
		$jar->setCookie($c);

		// Ping It
		$res = $this->httpClient->get('/auth/ping', [
			'cookies' => $jar,
		]);
		$res = $this->assertValidResponse($res);

		// Shut
		$res = $this->httpClient->post('/auth/shut', [
			'cookies' => $jar,
		]);
		// Check Cookies
		$res_head = $res->getHeaders();
		$sch = $res->getHeaderLine('set-cookie');
		$this->assertMatchesRegularExpression('/^openthc\-cre=deleted.+path.+secure.+httponly.+samesite/i', $sch);

		$res = $this->assertValidResponse($res);
		// $this->assertEmpty($res);

		// Ping It, should fail
		$res = $this->httpClient->get('/auth/ping', [
			'cookies' => $jar,
		]);
		$res = $this->assertValidResponse($res);
		$this->assertEmpty($res['data']['sid']);

	}

	function test_session_delete()
	{
		$res = $this->_post('/auth/open', [
			'service' => OPENTHC_TEST_CLIENT_SERVICE_A,
			'company' => OPENTHC_TEST_CLIENT_COMPANY_A,
			'license' => OPENTHC_TEST_CLIENT_LICENSE_A
		]);

		// Check for Cookie
		$sch = $res->getHeaderLine('set-cookie');
		$res = $this->assertValidResponse($res);

		$this->assertIsArray($res);
		$this->assertCount(2, $res);
		$this->assertMatchesRegularExpression('/\w{26,256}/', $res['data']);
		$this->assertMatchesRegularExpression('/^openthc\-cre=.+path.+secure.+httponly.+samesite/i', $sch);

		// Shut My Session
		$res = $this->httpClient->get('/auth/shut');
		$res = $this->assertValidResponse($res, 405);

		$res = $this->_post('/auth/shut', []);
		$res = $this->assertValidResponse($res, 200);
		// $this->assertEmpty($res);

		// Show it's dead
		$res = $this->httpClient->get('/auth/ping');
		$res = $this->assertValidResponse($res, 200);
		$this->assertEmpty($res['data']['sid']);

	}

}
