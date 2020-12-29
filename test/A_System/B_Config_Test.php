<?php
/**
 */

namespace Test\A_System;

class B_Config_Test extends \Test\Components\OpenTHC_Test_Case
{
	public function test_config()
	{
		$cfg = \OpenTHC\Config::get('openthc/sso');
		$this->assertNotEmpty($cfg, 'Missing SSO Config');
		$this->assertIsArray($cfg);
		$this->assertArrayHasKey('hostname', $cfg);
		$this->assertArrayHasKey('url', $cfg);

	}
}
