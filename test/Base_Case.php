<?php
/**
 * Base Test Case
 */

namespace Test;

class Base_Case extends \PHPUnit\Framework\TestCase
{
	protected $httpClient;
	protected $_pid = null;
	protected $type_expect = 'application/json';
	protected $_tmp_file = '/tmp/test-data-pass.json';

	function __construct($name = null, array $data = [], $dataName = '')
	{
		parent::__construct($name, $data, $dataName);
		$this->_pid = getmypid();

	}

	/**
	 *
	 */
	protected function _api()
	{
		// create our http client (Guzzle)
		$c = new \GuzzleHttp\Client(array(
			'base_uri' => $_ENV['api-uri'],
			'allow_redirects' => false,
			'debug' => $_ENV['debug-http'],
			'request.options' => array(
				'exceptions' => false,
			),
			'http_errors' => false,
			'cookies' => true,
		));

		return $c;
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


	function assertValidResponse($res, $code=200, $type_expect=null, $dump=null)
	{
		$this->raw = $res->getBody()->getContents();

		$hrc = $res->getStatusCode();

		if (empty($dump)) {
			if ($code != $hrc) {
				$dump = "HTTP $hrc != $code";
			}
		}

		if (!empty($dump)) {
			echo "\n<<< $dump <<< $hrc <<<\n{$this->raw}\n###\n";
		}

		$this->assertEquals($code, $res->getStatusCode());

		if (empty($type_expect)) {
			$type_expect = $this->type_expect;
		}
		$type_actual = $res->getHeaderLine('content-type');
		$type_actual = strtok($type_actual, ';');
		$this->assertEquals($type_expect, $type_actual);

		switch ($type_actual) {
			case 'application/json':
				$ret = json_decode($this->raw, true);
				// $ret['code'] = $res->getStatusCode();
				// $this->assertIsArray($ret);
				// // $this->assertArrayHasKey('data', $ret);
				// // $this->assertArrayHasKey('meta', $ret);
				// $this->assertArrayNotHasKey('status', $ret);
				// $this->assertArrayNotHasKey('result', $ret);
				return $ret;
			break;
		}

		return $this->raw;
	}


	/**
	*/
	protected function auth(string $p = null, string $c = null, string $l = null)
	{
		$res = $this->httpClient->post('/auth/open', $body = [
			'form_params' => [
				'service' => $p ?: $_ENV['api-service-a'],
				'company' => $c ?: $_ENV['api-company-a'],
				'license' => $l ?: $_ENV['api-license-a'],
			],
		]);

		$this->assertValidResponse($res);

	}

	/**
	 *
	 */
	function create_data_for_email()
	{
		$data = [];
		$data['address_target'] = getenv('OPENTHC_TEST_CONTACT_USERNAME');

		$data['subject'] = 'TEST_SUBJECT';
		$data['mail_subject'] = $data['subject'];
		$data['mail_subj'] = $data['subject'];

		$data['app_url'] = 'https://openthc.example.com/test/APP_URL'; // v2
		$data['openthc_app_url'] = 'https://openthc.example.com/#TEST_APP_URL'; // v3
		$data['openthc_sso_url'] = 'https://openthc.example.com/#TEST_SSO_URL';
		$data['auth_context_ticket'] = 'AUTH_CONTEXT_TICKET'; // v3

		$data['company_name'] = 'TEST_COMPANY_NAME';
		$data['contact_name'] = 'TEST_COMPANY_NAME';

		$data['origin_license'] = 'TEST_LICENSE_SOURCE';
		$data['origin_license_email'] = sprintf('test+license-source-%06d@openthc.example.com', $this->_pid);

		$data['target_license'] = 'TEST_LICENSE_TARGET';
		$data['target_license_email'] = sprintf('test+license-target-%06d@openthc.example.com', $this->_pid);

		$data['mail_link'] = 'https://openthc.example.com/#MAIL_LINK'; // @deprecated
		$data['transfer_link'] = 'https://openthc.example.com/#TRANSFER_LINK';

		$data['days'] = '420'; // who uses this one?
		$data['note'] = 'TEST_NOTE'; // ??
		$data['text'] = 'TEST_TEXT';

		return $data;
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

	function find_random_lot()
	{
		$res = $this->httpClient->get('/lot');
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
