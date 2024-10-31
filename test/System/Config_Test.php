<?php
/**
 *
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\System;

class Config_Test extends \OpenTHC\CRE\Test\Base
{
	public function test_config()
	{
		$cfg = \OpenTHC\Config::get('database');
		$this->assertNotEmpty($cfg, 'Missing Database Config');
		$this->assertIsArray($cfg);
		$this->assertArrayHasKey('hostname', $cfg);
		$this->assertArrayHasKey('database', $cfg);
		$this->assertArrayHasKey('username', $cfg);
		$this->assertArrayHasKey('password', $cfg);

		$cfg = \OpenTHC\Config::get('redis');
		$this->assertNotEmpty($cfg, 'Missing Redis Config');
		$this->assertIsArray($cfg);

		$cfg = \OpenTHC\Config::get('openthc/cre');
		$this->assertNotEmpty($cfg);
		$this->assertIsArray($cfg);
		$this->assertArrayHasKey('origin', $cfg);
		$this->assertArrayHasKey('public', $cfg);
		$this->assertArrayHasKey('secret', $cfg);

		$cfg = \OpenTHC\Config::get('openthc/sso');
		$this->assertNotEmpty($cfg, 'Missing SSO Config');
		$this->assertIsArray($cfg);
		$this->assertArrayHasKey('origin', $cfg);
		$this->assertArrayHasKey('client-id', $cfg);
		$this->assertArrayHasKey('client-sk', $cfg);

	}
}
