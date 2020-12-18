<?php
/**
 * Session Testing
 */

namespace Test\Auth;

class Session extends \Test\Components\OpenTHC_Test_Case
{
	protected $_tmp_file = '/tmp/cre-test-auth-session.dat';

	function test_session_create()
	{
		$res = $this->_post('/auth/open', [
			'service' => $_ENV['api-service-a'],
			'company' => $_ENV['api-company-g0'],
			'license' => $_ENV['api-license-g0']
		]);

		// Check for Cookie
		$sch = $res->getHeaderLine('set-cookie');
		$res = $this->assertValidResponse($res);

		$this->assertIsArray($res);
		$this->assertCount(2, $res);
		$this->assertRegExp('/\w{26,256}/', $res['data']);
		$this->assertRegExp('/^openthc.+path.+secure.+httponly.+samesite/', $sch);

		// Ping It
		$res = $this->httpClient->get('/auth/ping');
		$res = $this->assertValidResponse($res);

		$this->_data_stash_put($res['data']);

	}

	function test_session_lifetime()
	{
		// Add this Cookie?
		$prev = $this->_data_stash_get();
		// var_dump($prev);

		$jar = new \GuzzleHttp\Cookie\CookieJar();
		$sid = $prev['sid'];

		if (!empty($sid)) {
			$c = new \GuzzleHttp\Cookie\SetCookie(array(
				'Domain' => $this->httpClient->getConfig('base_uri')->getHost(),
				'Name' => 'openthc',
				'Value' => $sid,
				'Secure' => true,
				'HttpOnly' => true,
			));
			$jar->setCookie($c);
		}

		// // Ping It
		$res = $this->httpClient->get('/auth/ping', [
			'cookies' => $jar,
		]);
		// $sch = $res->getHeaderLine('set-cookie');
		// var_dump($sch);
		$res = $this->assertValidResponse($res);
		// var_dump($res);

		// Wipe my Session (works like GC, different than "shut")
		$res = $this->_post('/auth/shut', []);
		$head = $res->getHeaders();
		// var_dump($head);
		$res = $this->assertValidResponse($res);
		// $this->assertEmpty($res);

		// Ping It, should fail
		$res = $this->httpClient->get('/auth/ping', [
			'cookies' => $jar,
		]);
		// $res = $this->assertValidResponse($res, 403);
		// $this->assertEmpty($res['data']);
		// $this->assertEquals('Invalid Session', $res['meta']['detail']);

	}

	function test_session_delete()
	{
		$res = $this->_post('/auth/open', [
			'service' => $_ENV['api-service-a'],
			'company' => $_ENV['api-company-g0'],
			'license' => $_ENV['api-license-g0']
		]);

		// Check for Cookie
		$sch = $res->getHeaderLine('set-cookie');
		$res = $this->assertValidResponse($res);

		$this->assertIsArray($res);
		$this->assertCount(2, $res);
		$this->assertRegExp('/\w{26,256}/', $res['data']);
		$this->assertRegExp('/path.+domain.+secure/', $sch);

		// Shut My Session
		$res = $this->httpClient->get('/auth/shut');
		$res = $this->assertValidResponse($res, 405);

		$res = $this->_post('/auth/shut', []);
		$res = $this->assertValidResponse($res, 200);
		// $this->assertEmpty($res);

		// Show it's dead
		$res = $this->httpClient->get('/auth/ping');
		$res = $this->assertValidResponse($res, 403);
		$this->assertEmpty($res['data']);
		$this->assertEquals('Invalid Session', $res['meta']['detail']);

	}

}
