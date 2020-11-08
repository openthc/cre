<?php
/**
 * Test Section Create/Update/Delete
 */

namespace Test\Basic;

class Section extends \Test\Components\OpenTHC_Test_Case
{
	protected $_tmp_file = '/tmp/unit-test-section.json';

	protected function setUp() : void
	{
		parent::setUp();
		$this->auth($_ENV['api-service-a'], $_ENV['api-company-g0'], $_ENV['api-license-g0']);
	}

	public function test_public_read()
	{
		// Reset Auth
		$this->httpClient = $this->_api();

		$res = $this->httpClient->get('/section');
		$this->assertValidResponse($res, 403);

		$res = $this->httpClient->get('/section/four_zero_four');
		$this->assertValidResponse($res, 403);

		$res = $this->httpClient->get('/section/1');
		$this->assertValidResponse($res, 403);

		$res = $this->httpClient->get('/section?' . http_build_query([
			'q' => 'UNITTEST'
		]));
		$this->assertValidResponse($res, 403);

	}


	/**
	 * @todo Missing request body parameters, assuming base parameters:
	 * - name, (string)
	 * - type, (string)
	 */
	public function test_create_negative()
	{
		$URI = '/section';

		$arg = [
		];
		$res = $this->_post($URI, $arg);
		$res = $this->assertValidResponse($res, 400);

		$this->assertCount(2, $res);

		// $this->assertNotEmpty($res['id']);
		// $this->assertEquals('UNITTEST Section 031', $res['name']);
		//
		// // Can't create two objects with same ID
		// $arg = [
		// 	'name' => 'UNITTEST Section 042',
		// 	'type' => 'logical',
		// 	'id' => 'TSTCRE.OTSECTION1'
		// ];
		// $res = $this->_post($URI, $arg);
		// $this->assertValidResponse($res, 409);
		// // $this->asserEquals($res->getStatusCode(), 405); // Not 405
		//
		// $arg = [
		// 	'name' => 'UNITTEST Section 051',
		// 	'type' => 'physical',
		// ];
		// $res = $this->_post($URI, $arg);
		// $res = $this->assertValidResponse($res, 201);
		// $this->assertExists($res['id']);
		//
		// // Missing type
		// $arg = [
		// 	'name' => 'UNITTEST Section 061',
		// ];
		// $res = $this->_post($URI, $arg);
		// $res = $this->assertValidResponse($res, 405);
		//
		// // Missing type
		// $arg = [
		// 	'id' => 'TSTCRE.OTSECTION4',
		// 	'name' => 'UNITTEST Section 069',
		// ];
		// $res = $this->_post($URI, $arg);
		// $res = $this->assertValidResponse($res, 405);
		//
		// // Test strange names
		// $arg = [
		// 	'name' => 'UNITEST Section 🐛📒',
		// 	'type' => 'logical',
		// ];
		// $res = $this->_post($URI, $arg);
		// $res = $this->assertValidResponse($res, 405);
		//
		// $arg = [
		// 	'id' => 'TSTCRE.OTSECTION6',
		// 	'name' => '0x800000000001',
		// 	'type' => 'logical',
		// ];
		// $res = $this->_post($URI, $arg);
		// $res = $this->assertValidResponse($res);
		// $this->assertEquals('0x800000000008', $res['name']);
	}

	function test_create_positive()
	{
		$name = sprintf('UNITTEST Section CREATE %06x', $this->_pid);

		$res = $this->_post('/section', [
			'name' => $name,
		]);
		$res = $this->assertValidResponse($res, 201);

		$obj = $res['data'];
		$this->assertIsArray($obj);
		$this->assertCount(3, $obj);
		$this->assertEquals($name, $obj['name']);

		$this->_data_stash_put($obj);

	}

	public function test_search()
	{
		$res = $this->httpClient->get('/section?q=UNITTEST');
		$res = $this->assertValidResponse($res);

		$this->assertIsArray($res['meta']);
		$this->assertIsArray($res['data']);

		$obj = $res['data'][0];
		$this->assertIsArray($obj);
		$this->assertCount(3, $obj);

	}

	public function test_single()
	{
		$res = $this->httpClient->get('/section/four_zero_four');
		$this->assertValidResponse($res, 404);

		$obj = $this->_data_stash_get();
		$res = $this->httpClient->get('/section/' . $obj['id']);
		$res = $this->assertValidResponse($res);

		// More

	}

	public function test_update()
	{
		$name = sprintf('UNITTEST Section CREATE-UPDATE %06x', $this->_pid);

		$obj = $this->_data_stash_get();
		$res = $this->_post('/section/' . $obj['id'], array(
			'name' => $name,
		));
		$res = $this->assertValidResponse($res, 200);

		$this->assertIsArray($res['meta']);
		$this->assertIsString($res['meta']['detail']);

		$this->assertIsArray($res['data']);

		$obj = $res['data'];
		$this->assertIsArray($obj);
		// $this->assertCount(3, $obj);
		$this->assertEquals($name, $obj['name']);

	}

	public function test_delete()
	{
		$res = $this->httpClient->delete('/section/four_zero_four');
		$this->assertValidResponse($res, 404);

		// Find Early One
		$obj = $this->_data_stash_get();
		//var_dump($s0);

		// First call to Delete gives 202
		$res = $this->httpClient->delete('/section/' . $obj['id']);
		$this->assertValidResponse($res, 202);

		// Second Call should give 410
		$res = $this->httpClient->delete('/section/' . $obj['id']);
		$this->assertValidResponse($res, 410);

		$res = $this->httpClient->delete('/section/' . $obj['id']);
		$this->assertValidResponse($res, 423);

		unlink($this->_tmp_file);

	}

	/**
	 * Create Test Rooms for G type license
	 */
	function test_create_g()
	{
		$this->auth($_ENV['api-service-a'], $_ENV['api-company-g0'], $_ENV['api-license-g0']);

		$name = sprintf('UNITTEST Section-G CREATE %06x', $this->_pid);

		$res = $this->_post('/section', [
			'name' => $name,
		]);
		$res = $this->assertValidResponse($res, 201);

	}

	/**
	 * Create Test Rooms for P type license
	 */
	function test_create_p()
	{
		$this->auth($_ENV['api-service-a'], $_ENV['api-company-p0'], $_ENV['api-license-p0']);

		$name = sprintf('UNITTEST Section-P CREATE %06x', $this->_pid);

		$res = $this->_post('/section', [
			'name' => $name,
		]);
		$res = $this->assertValidResponse($res, 201);
	}

	/**
	 * Create Test Rooms for L type license
	 */
	function test_create_l()
	{
		$this->auth($_ENV['api-service-a'], $_ENV['api-company-l0'], $_ENV['api-license-l0']);

		$name = sprintf('UNITTEST Section-L CREATE %06x', $this->_pid);

		$res = $this->_post('/section', [
			'name' => $name,
		]);
		$res = $this->assertValidResponse($res, 201);
	}

	/**
	 * Create Test Rooms for R type license
	 */
	function test_create_r()
	{
		$this->auth($_ENV['api-service-a'], $_ENV['api-company-r0'], $_ENV['api-license-r0']);

		$name = sprintf('UNITTEST Section-R CREATE %06x', $this->_pid);

		$res = $this->_post('/section', [
			'name' => $name,
		]);
		$res = $this->assertValidResponse($res, 201);
	}

}
