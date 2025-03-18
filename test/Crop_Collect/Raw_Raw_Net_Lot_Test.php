<?php
/**
 *
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\Crop_Collect;

class Raw_Raw_Net_Lot_Test extends \OpenTHC\CRE\Test\Base
{
	protected function setUp() : void
	{
		parent::setUp();
		$this->auth($_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'], $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'], $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A']);
	}

	function test_too_much_net()
	{
	}
}
