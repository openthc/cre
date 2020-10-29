<?php
/**
 *
*/

namespace Test\Components;

class OpenTHC_Test_Case extends \PHPUnit\Framework\TestCase
{
	protected $httpClient; // API Guzzle Client

	protected $_pid;
	protected $_tmp_file = '/tmp/test-data-pass.json';

	public function __construct($name = null, array $data = [], $dataName = '')
	{
		parent::__construct($name, $data, $dataName);
		$this->_pid = getmypid();
	}

	protected function setUp() : void
	{
		$this->httpClient = $this->_api();
	}


	/**
	 * Intends to become an assert wrapper for a bunch of common response checks
	 * @param $res, Response Object
	 * @return void
	 */
	function assertValidResponse($res, $code=200, $dump=null)
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
		$type = $res->getHeaderLine('content-type');
		$type = strtok($type, ';');
		$this->assertEquals('application/json', $type);

		$ret = \json_decode($this->raw, true);

		$this->assertIsArray($ret);
		// $this->assertArrayHasKey('data', $ret);
		// $this->assertArrayHasKey('meta', $ret);

		$this->assertArrayNotHasKey('status', $ret);
		$this->assertArrayNotHasKey('result', $ret);

		return $ret;
	}

	function find_random_lot()
	{
		$res = $this->httpClient->get('/lot');
		$res = $this->assertValidResponse($res);
		$this->assertIsArray($res['meta']);
		$this->assertGreaterThanOrEqual(1, count($res['data']));
		// var_dump($res);

		$i = \array_rand($res['data']);
		$r = $res['data'][$i];
		return $r;

	}


	function find_random_plant($c=1)
	{
		$res = $this->httpClient->get('/plant');
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

	function find_random_product()
	{
		$res = $this->httpClient->get('/config/product');
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

	function find_random_strain()
	{
		$res = $this->httpClient->get('/config/strain');
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
		$res = $this->httpClient->get('/config/section');
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

	/**
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
	*/
	protected function auth(string $p = null, string $c = null, string $l = null)
	{
		$res = $this->httpClient->post('/auth/open', $body = [
			'form_params' => [
				'program' => $p ?: $_ENV['api-program-a'],
				'company' => $c ?: $_ENV['api-company-a'],
				'license' => $l ?: $_ENV['api-license-a'],
			],
		]);

		$this->assertValidResponse($res);

	}


	/**
	*/
	protected function _post($u, $a)
	{
		$res = $this->httpClient->post($u, [ 'form_params' => $a ]);
		return $res;
	}


	/**
	*/
	protected function _post_json($u, $a)
	{
		$res = $this->httpClient->post($u, [ 'json' => $a ]);
		return $res;
	}


	/**
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
	*/
	protected function _data_stash_put($d)
	{
		file_put_contents($this->_tmp_file, json_encode($d));
	}

}
