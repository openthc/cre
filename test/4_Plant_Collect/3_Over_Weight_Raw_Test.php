<?php
/**
 *
 */

namespace Test\Plant_Collect;

class Over_Weight_Raw extends \Test\Components\OpenTHC_Test_Case
{
	protected function setUp() : void
	{
		parent::setUp();
		$this->auth($_ENV['api-program-a'], $_ENV['api-company-g0'], $_ENV['api-license-g0']);
	}

	function test_too_much_net()
	{
		$p0 = $this->find_random_plant();

		$url = sprintf('/plant/%s/collect', $p0['id']);
		$arg = [
			'type' => 'wet',
			'qty' => 12.34,
			'uom' => 'g',
		];
		$res = $this->_post($url, $arg);
		//
		$res = $this->assertValidResponse($res, 201);
		// Should Have Collect Information?
		$this->assertCount(2, $res);
		$this->assertNotEmpty($res['data']['id']);

		// Add Dry
		$url = sprintf('/plant/%s/collect', $p0['id']);
		$arg = [
			'type' => 'dry',
			'qty' => 12.34,
			'uom' => 'g',
		];
		$res = $this->_post($url, $arg);
		$res = $this->assertValidResponse($res, 201);
		$this->assertCount(2, $res);
		$this->assertNotEmpty($res['data']['id']);

		// Add Net
		$url = sprintf('/plant/%s/collect', $p0['id']);
		$arg = [
			'type' => 'net',
			'qty' => 12.35,
			'uom' => 'g',
		];
		$res = $this->_post($url, $arg);
		$this->assertValidResponse($res, 406);
		$this->assertCount(2, $res);
		$this->assertEmpty($res['data']);
		$this->assertNotEmpty($res['meta']['detail']);

	}
}
