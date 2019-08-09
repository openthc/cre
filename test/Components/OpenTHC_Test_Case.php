<?php
/**
 * Test Case Base Class
 */

namespace Test\Components;

class OpenTHC_Test_Case_2 extends \PHPUnit\Framework\TestCase
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

		switch ($hrc) {
		case 500:
			$dump = '500 Error Response';
		}

		if (!empty($dump)) {
			echo "\n<<<$dump<<<\n{$this->raw}\n###\n";
		}

		$ret = \json_decode($this->raw, true);

		$this->assertEquals($code, $res->getStatusCode());
		$this->assertEquals('application/json', $res->getHeaderLine('content-type'));
		$this->assertIsArray($ret);
		// $this->assertCount(2, $ret);

		// $this->assertIsArray($x['data']);
		// $this->assertIsArray($x['meta']);

		return $ret;
	}

	function find_random_plant()
	{
		$res = $this->httpClient->get('/plant');
		$res = $this->assertValidResponse($res);
		$this->assertIsArray($res['result']);
		$this->assertGreaterThan(1, count($res['result']));


		$rnd_list = array();
		foreach ($res['result'] as $p) {
			$rnd_list[] = $p;
		}

		$i = \array_rand($rnd_list);
		$p = $rnd_list[$i];
		return $p;

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
		$x = file_get_contents($this->_tmp_file);
		$x = json_decode($x, true);
		return $x;
	}


	/**
	*/
	protected function _data_stash_put($d)
	{
		file_put_contents($this->_tmp_file, json_encode($d));
	}

}
