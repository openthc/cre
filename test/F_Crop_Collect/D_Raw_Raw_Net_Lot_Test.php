<?php
/**
 *
 */

namespace Test\F_Crop_Collect;

class D_Raw_Raw_Net_Lot_Test extends \Test\Base_Case
{
	protected function setUp() : void
	{
		parent::setUp();
		$this->auth($_ENV['api-service-a'], $_ENV['api-company-a'], $_ENV['api-license-a']);
	}

	function test_too_much_net()
	{
	}
}
