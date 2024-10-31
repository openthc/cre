<?php
/**
 *
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\System;

class System_Test extends \OpenTHC\CRE\Test\Base
{
	public function test_system()
	{
		// Paths?
		$dir = sprintf('%s/var/', APP_ROOT);
		$this->assertTrue(is_dir($dir), 'Missing /var');

		// Runtime Dependencies?
	}
}
