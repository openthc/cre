<?php
/**
 *
 */

namespace Test\Plant_Collect;

class Over_Weight_Dry extends \Test\Components\OpenTHC_Test_Case
{
	protected function setUp() : void
	{
		parent::setUp();
		$this->auth($_ENV['api-program-a'], $_ENV['api-company-g0'], $_ENV['api-license-g0']);
	}

	function test_too_much_dry()
	{
		$obj = $this->find_random_plant();
		$url = sprintf('/plant/%s/collect', $obj['id']);
		$arg = [
			'type' => 'wet',
			'qty' => 12.34,
			'uom' => 'g',
		];
		$res = $this->_post($url, $arg);
		$this->assertValidResponse($res, 201);
	}
}
