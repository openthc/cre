<?php
/**
 *
 */

namespace Test\Crop_Collect;

class Raw_Raw_Net_Lot extends \Test\Components\OpenTHC_Test_Case
{
	protected function setUp() : void
	{
		parent::setUp();
		$this->auth($_ENV['api-service-a'], $_ENV['api-company-g0'], $_ENV['api-license-g0']);
	}

	function test_too_much_net()
	{
	}
}
