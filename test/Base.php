<?php
/**
 * Base Test Case
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test;

class Base extends \OpenTHC\Test\Base {

	protected $httpClient;

	protected $type_expect = 'application/json';

	function __construct($name = null, array $data = [], $dataName = '')
	{
		parent::__construct($name, $data, $dataName);

	}

	function makeHTTPClient($cfg=[])
	{
		$def = [
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_0'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_0'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_0'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_0'],
		];
		$cfg = array_merge($def, $cfg);
		$sid = $this->auth($cfg);

		$httpClient = $this->_api([
			'headers' => [
				'authorization' => sprintf('Bearer v2024/%s', $sid),
			],
		]);

		return $httpClient;
	}

	/**
	 *
	 */
	protected function _api(array $cfg=[])
	{
		// create our http client (Guzzle)
		$opt = array(
			'base_uri' => $_ENV['OPENTHC_TEST_ORIGIN'],
		);

		$cfg = array_merge($opt, $cfg);

		return $this->getGuzzleClient($cfg);
	}

	/**
	 *
	 */
	protected function make_bearer_token($cfg=[])
	{
		$client_pk = \OpenTHC\Config::get('openthc/cre/public');
		$client_sk = \OpenTHC\Config::get('openthc/cre/secret');
		$server_pk = $client_pk;

		$def = [
			'pk' => $client_pk,
			'ts' => time(),
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_0'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_0'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_0'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_0'],
		];

		$arg = array_merge($def, $cfg);

		$plain_data = json_encode($arg);
		$crypt_box = \OpenTHC\Sodium::encrypt($plain_data, $client_sk, $server_pk);
		$crypt_box = \OpenTHC\Sodium::b64encode($crypt_box);

		return sprintf('Bearer v2024/%s/%s', $client_pk, $crypt_box);
	}

	private function auth($cfg=[])
	{
		$tok = $this->make_bearer_token($cfg);

		$res = $this->httpClient->post('/auth/open', [
			'headers' => [
				'Authorization' => $tok,
			],
		]);

		$res = $this->assertValidResponse($res);

		return $res['data']['sid'];

	}

	/**
	 * @deprecated
	 */
	protected function _post($u, $a)
	{
		$res = $this->httpClient->post($u, [ 'form_params' => $a ]);
		return $res;
	}

	/**
	 * @deprecated
	 */
	protected function _post_json($u, $a)
	{
		$res = $this->httpClient->post($u, [ 'json' => $a ]);
		return $res;
	}

	protected function setUp() : void
	{
		$this->httpClient = $this->_api();
	}

	function find_random_crop($c=1)
	{
		$res = $this->httpClient->get('/crop');
		$res = $this->assertValidResponse($res);
		$this->assertIsArray($res['meta']);
		$this->assertGreaterThanOrEqual(1, count($res['data']));

		if ($c > 1) {
			shuffle($res['data']);
			$rnd_list = [];
			while (count($rnd_list) < $c) {
				$rnd_list[] = array_shift($res['data']);
			}
			return $rnd_list;
		} else {
			$i = \array_rand($res['data']);
			return $res['data'][$i];
		}

	}

	function find_random_inventory()
	{
		$res = $this->httpClient->get('/inventory');
		$res = $this->assertValidResponse($res);
		$this->assertIsArray($res['meta']);
		$this->assertGreaterThanOrEqual(1, count($res['data']));

		$i = \array_rand($res['data']);
		$r = $res['data'][$i];
		return $r;

	}

	function find_random_product()
	{
		$res = $this->httpClient->get('/product');
		$res = $this->assertValidResponse($res);
		$this->assertIsArray($res['meta']);
		$this->assertGreaterThanOrEqual(1, count($res['data']));

		$rnd_list = array();
		foreach ($res['data'] as $x) {
			// Filter?
			$rnd_list[] = $x;
		}

		$i = \array_rand($rnd_list);
		$r = $rnd_list[$i];
		return $r;

	}

	function find_random_section()
	{
		$res = $this->httpClient->get('/section');
		$res = $this->assertValidResponse($res);
		$this->assertIsArray($res['meta']);
		$this->assertGreaterThanOrEqual(1, count($res['data']));

		// $rnd_list = array();
		// foreach ($res['data'] as $x) {
		// 	// Filter?
		// 	$rnd_list[] = $x;
		// }

		$i = \array_rand($res['data']);
		$r = $res['data'][$i];
		return $r;

	}

	function find_random_variety()
	{
		$res = $this->httpClient->get('/variety');
		$res = $this->assertValidResponse($res);
		$this->assertIsArray($res['meta']);
		$this->assertGreaterThanOrEqual(1, count($res['data']));


		$rnd_list = array();
		foreach ($res['data'] as $x) {
			// Filter?
			$rnd_list[] = $x;
		}

		$i = \array_rand($rnd_list);
		$r = $rnd_list[$i];
		return $r;

	}

	/**
	 * $pc Plant Collect ID
	 * $p Plant ID
	 */
	function post_crop_collect($pc, $p, $qty, $type)
	{
		$url = sprintf('/crop/%s/collect', $p['id']);
		$arg = [
			'plant_collect_id' => $pc['id'],
			'type' => $type,
			'qty' => $qty,
			'uom' => 'g',
		];
		$res = $this->_post($url, $arg);
		$res = $this->assertValidResponse($res, 201);
		// // Should Have Collect Information?
		// $this->assertCount(2, $res);
		// $this->assertNotEmpty($res['data']['id']); //
		// $pcB = $res['data'];
		return $res;
	}

}
