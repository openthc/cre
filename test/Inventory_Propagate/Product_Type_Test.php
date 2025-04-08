<?php
/**
 * Make a bunch of different Product of different Types
 */

namespace OpenTHC\CRE\Test\Inventory_Propagate;

class Product_Type_Test extends \OpenTHC\CRE\Test\Base
{

	protected function setUp() : void
	{
		parent::setUp();
		$this->httpClient = $this->makeHTTPClient([
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_A'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A'],
		]);
	}

	/*
	 *
	 */
	function test_public_access()
	{
		$res = $this->httpClient->get('/product/type');
		$this->assertValidResponse($res, 200);

		$res = $this->httpClient->get('/product/type/four_zero_four');
		$this->assertValidResponse($res, 404);

		$res = $this->httpClient->get('/product/type/1');
		$this->assertValidResponse($res, 404);

		$res = $this->httpClient->get('/product/type?q=1');
		$res = $this->assertValidResponse($res, 200);

	}

	/*
	 *
	 */
	function test_search()
	{
		$res = $this->httpClient->get('/product/type');
		$res = $this->assertValidResponse($res, 200);
	}

	/*
	 *
	 */
	function test_create_seed()
	{
		$res = $this->_post('/product', array(
			'name' => sprintf('UNITTEST Product/Seed CREATE %s', $this->_pid),
			'type' => '019KAGVSC0C474J20SEWDM5XSJ',
			'uom' => 'ea',
		));
		$res = $this->assertValidResponse($res, 201);
	}

	/**
	 * [test_create_clone description]
	 * @return [type] [description]
	 */
	function test_create_clone()
	{
		$res = $this->_post('/product', array(
			'name' => sprintf('UNITTEST Product/Clone CREATE %s', $this->_pid),
			'type' => '019KAGVSC0J008XMJ25DCBK17P',
			'uom' => 'ea',
		));
		$res = $this->assertValidResponse($res, 201);
	}

	function test_create_plant()
	{
		$res = $this->_post('/product', array(
			'name' => sprintf('UNITTEST Product/Plant CREATE %s', $this->_pid),
			'type' => '019KAGVSC0KANK9BMYFS5BDFCB',
			'uom' => 'ea',
		));
		$res = $this->assertValidResponse($res, 201);
	}

	function test_create_tissue()
	{
		$res = $this->_post('/product', array(
			'name' => sprintf('UNITTEST Product/Tissue CREATE %s', $this->_pid),
			'type' => '019KAGVSC0AT9P3779ATHDK6MC',
			'uom' => 'g',
		));
		$res = $this->assertValidResponse($res, 201);
	}

	function test_create_raw_flower()
	{
		$res = $this->_post('/product', array(
			'name' => sprintf('UNITTEST Product/Flower/Raw CREATE %s', $this->_pid),
			'type' => '019KAGVSC01MVH9QAZ75KEPY4D',
			'uom' => 'g',
		));
		$res = $this->assertValidResponse($res, 201);
	}

	function test_create_raw_trim()
	{
		$res = $this->_post('/product', array(
			'name' => sprintf('UNITTEST Product/Trim/Raw CREATE %s', $this->_pid),
			'type' => '019KAGVSC05QXA1BCA13PNAK5J',
			'uom' => 'g',
		));
		$res = $this->assertValidResponse($res, 201);
	}

	function test_create_process_flower()
	{
		$res = $this->_post('/product', array(
			'name' => sprintf('UNITTEST Product/Flower/Lot CREATE %s', $this->_pid),
			'type' => '019KAGVSC0CYZQ68Q2184AE10A',
			'uom' => 'g',
		));
		$res = $this->assertValidResponse($res, 201);
	}

	function test_create_process_trim()
	{
		$res = $this->_post('/product', array(
			'name' => sprintf('UNITTEST Product/Trim/Lot CREATE %s', $this->_pid),
			'type' => '019KAGVSC0W8ESY93TK05TPFKN',
			'uom' => 'g',
		));
		$res = $this->assertValidResponse($res, 201);
	}

	function test_create_process_mix()
	{
		$res = $this->_post('/product', array(
			'name' => sprintf('UNITTEST Product/Mix/Lot CREATE %s', $this->_pid),
			'type' => '019KAGVSC0Q16ECBP02ET3RRMT',
			'uom' => 'g',
		));
		$res = $this->assertValidResponse($res, 201);
	}

	function test_create_process_extract_co2()
	{
		$res = $this->_post('/product', array(
			'name' => sprintf('UNITTEST Product/Extract/CO2 CREATE %s', $this->_pid),
			'type' => '019KAGVSC06Y7WBPCY2XNHP2FE',
			'uom' => 'g',
		));
		$res = $this->assertValidResponse($res, 201);
	}

	function test_create_process_extract_hash()
	{
		$res = $this->_post('/product', array(
			'name' => sprintf('UNITTEST Product/Extract/Hash CREATE %s', $this->_pid),
			'type' => '019KAGVSC0K109NHQ92CCKEJW5',
			'uom' => 'g',
		));
		$res = $this->assertValidResponse($res, 201);
	}

	function test_create_process_extract_kief()
	{
		$res = $this->_post('/product', array(
			'name' => sprintf('UNITTEST Product/Extract/Kief CREATE %s', $this->_pid),
			'type' => '019KAGVSC0VX8100VFN3YXKBYH',
			'uom' => 'g',
		));
		$res = $this->assertValidResponse($res, 201);
	}

	function test_create_package_flower()
	{
		$res = $this->_post('/product', array(
			'name' => sprintf('UNITTEST Product/Flower/Bag CREATE %s', $this->_pid),
			'type' => '019KAGVSC0WMF1XY879SECK50W',
			'uom' => 'g',
		));
		$res = $this->assertValidResponse($res, 201);

	}

	function test_create_package_edible_liquid()
	{
		$res = $this->_post('/product', array(
			'name' => sprintf('UNITTEST Product/Edible/Liquid CREATE %s', $this->_pid),
			'type' => '019KAGVSC03MRD8MDZJXGM5MXF',
			'uom' => 'g',
		));
		$res = $this->assertValidResponse($res, 201);
	}

	function test_create_package_edible_solid()
	{
		$res = $this->_post('/product', array(
			'name' => sprintf('UNITTEST Product/Edible/Solid CREATE %s', $this->_pid),
			'type' => '019KAGVSC0QXVE5AC12DCNM6RS',
			'uom' => 'g',
		));
		$res = $this->assertValidResponse($res, 201);
	}

	function test_create_package_capsule()
	{
		$res = $this->_post('/product', array(
			'name' => sprintf('UNITTEST Product/Capsule CREATE %s', $this->_pid),
			'type' => '019KAGVSC0ZHAEQCFNYMXDXWKV',
			'uom' => 'ea',
		));
		$res = $this->assertValidResponse($res, 201);
	}

	function test_create_package_tincture()
	{
		$res = $this->_post('/product', array(
			'name' => sprintf('UNITTEST Product/Tincture CREATE %s', $this->_pid),
			'type' => '019KAGVSC062S4TRBT5FBJW98V',
			'uom' => 'ea',
		));
		$res = $this->assertValidResponse($res, 201);
	}

	function test_create_package_transdermal()
	{
		$res = $this->_post('/product', array(
			'name' => sprintf('UNITTEST Product/Transdermal CREATE %s', $this->_pid),
			'type' => '019KAGVSC0EFWWVV8DE89JVNTY',
			'uom' => 'ea',
		));
		$res = $this->assertValidResponse($res, 201);
	}

	function test_create_package_suppository()
	{
		$res = $this->_post('/product', array(
			'name' => sprintf('UNITTEST Product/Suppository CREATE %s', $this->_pid),
			'type' => '019KAGVSC02549AK0RQWFAMNVB',
			'uom' => 'ea',
		));
		$res = $this->assertValidResponse($res, 201);
	}

}
