<?php
/**
 */

namespace Test\A_System;

class A_System_Test extends \Test\Components\OpenTHC_Test_Case
{
	public function test_system()
	{
		// Paths?
		$dir = sprintf('%s/var/', APP_ROOT);
		$this->assertTrue(is_dir($dir));

		// Runtime Dependencies?
	}
}
