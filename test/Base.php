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

	protected $_tmp_file = '/tmp/test-data-pass.json';

	function __construct($name = null, array $data = [], $dataName = '')
	{
		parent::__construct($name, $data, $dataName);

	}

	/**
	 *
	 */
	protected function _api(array $cfg=[])
	{
		// create our http client (Guzzle)
		$opt = array(
			'base_uri' => $_ENV['OPENTHC_TEST_ORIGIN'],
			'allow_redirects' => false,
			'debug' => $_ENV['debug-http'],
			'request.options' => array(
				'exceptions' => false,
			),
			'http_errors' => false,
			'cookies' => true,
		);

		$opt = array_merge($opt, $cfg);

		$c = new \GuzzleHttp\Client($opt);

		return $c;
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
			'service' => $_ENV['OPENTHC_TEST_SERVICE_ID'],
			'company' => $_ENV['OPENTHC_TEST_COMPANY_ID'],
			'contact' => $_ENV['OPENTHC_TEST_CONTACT_ID'],
			'license' => $_ENV['OPENTHC_TEST_LICENSE_ID'],
		];

		$arg = array_merge($def, $cfg);

		$plain_data = json_encode($arg);
		$crypt_box = \OpenTHC\Sodium::encrypt($plain_data, $client_sk, $server_pk);
		$crypt_box = \OpenTHC\Sodium::b64encode($crypt_box);

		return sprintf('Bearer v2024/%s/%s', $client_pk, $crypt_box);
	}

	protected function auth($ct, $cy, $li)
	{
		$tok = $this->make_bearer_token([
			'contact' => $ct, // $_ENV['OPENTHC_TEST_CLIENT_CONTACT_A'],
			'company' => $cy, // $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
			'license' => $li, // $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A'],
		]);

		$res = $this->httpClient->post('/auth/open', [
			'headers' => [
				'Authorization' => $tok,
			],
		]);

		$res = $this->assertValidResponse($res);

		return $res['data']['sid'];

	}

	/**
	 *
	 */
	protected function _post($u, $a)
	{
		$res = $this->httpClient->post($u, [ 'form_params' => $a ]);
		return $res;
	}

	/**
	 *
	 */
	protected function _post_json($u, $a)
	{
		$res = $this->httpClient->post($u, [ 'json' => $a ]);
		return $res;
	}

	/**
	 *
	 */
	protected function _data_stash_get()
	{
		if (is_file($this->_tmp_file)) {
			$x = file_get_contents($this->_tmp_file);
			$x = json_decode($x, true);
			return $x;
		}
	}

	/**
	 *
	 */
	protected function _data_stash_put($d)
	{
		file_put_contents($this->_tmp_file, json_encode($d));
	}

	protected function setUp() : void
	{
		$this->httpClient = $this->_api();
	}

	/**
	*/
	// protected function auth(string $p = null, string $c = null, string $l = null)
	// {
	// 	$res = $this->httpClient->post('/auth/open', $body = [
	// 		'form_params' => [
	// 			'service' => $p ?: $_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'],
	// 			'company' => $c ?: $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
	// 			'license' => $l ?: $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A'],
	// 		],
	// 	]);

	// 	$this->assertValidResponse($res);

	// }

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
