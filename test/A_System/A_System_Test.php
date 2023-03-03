<?php
/**
 *
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\A_System;

class A_System_Test extends \OpenTHC\CRE\Test\Base_Case
{
	public function test_system()
	{
		// Paths?
		$dir = sprintf('%s/var/', APP_ROOT);
		$this->assertTrue(is_dir($dir));

		// Runtime Dependencies?
	}
}
