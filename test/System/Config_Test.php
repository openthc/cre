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
		$cfg = \OpenTHC\Config::get('database/cre');
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

	/**
	 * Find the env constants.
	 * grep -Por 'OPENTHC_TEST\w+' test/ |cut -d':' -f2 |sort | uniq -c
	 */
	function test_env()
	{
		// A => G for Grower
		//   => J for Grower + Processor
		// B => P for Processor
		// C => L for Laboratory
		// D => R for Retail

		$key_list = [
			'OPENTHC_TEST_CLIENT_COMPANY_0',
			'OPENTHC_TEST_CLIENT_COMPANY_A',
			'OPENTHC_TEST_CLIENT_COMPANY_B',
			'OPENTHC_TEST_CLIENT_COMPANY_C',
			'OPENTHC_TEST_CLIENT_COMPANY_D',
			'OPENTHC_TEST_CLIENT_CONTACT_0',
			'OPENTHC_TEST_CLIENT_CONTACT_A',
			'OPENTHC_TEST_CLIENT_CONTACT_B',
			'OPENTHC_TEST_CLIENT_CONTACT_C',
			'OPENTHC_TEST_CLIENT_CONTACT_D',
			'OPENTHC_TEST_CLIENT_LICENSE_0',
			'OPENTHC_TEST_CLIENT_LICENSE_A',
			'OPENTHC_TEST_CLIENT_LICENSE_A_SECRET',
			'OPENTHC_TEST_CLIENT_LICENSE_B',
			'OPENTHC_TEST_CLIENT_LICENSE_B_SECRET',
			'OPENTHC_TEST_CLIENT_LICENSE_C',
			'OPENTHC_TEST_CLIENT_LICENSE_C_SECRET',
			'OPENTHC_TEST_CLIENT_LICENSE_D',
			'OPENTHC_TEST_CLIENT_LICENSE_D_SECRET',
			'OPENTHC_TEST_CLIENT_SERVICE_0',
			'OPENTHC_TEST_CLIENT_SERVICE_A',
			'OPENTHC_TEST_CLIENT_SERVICE_B',
			'OPENTHC_TEST_CLIENT_SERVICE_C',
			'OPENTHC_TEST_CLIENT_SERVICE_D',
			'OPENTHC_TEST_HTTP_DEBUG',
			'OPENTHC_TEST_ORIGIN',
		];

		foreach ($key_list as $k) {

			// $a = getenv($k);
			// $b = isset($_ENV[$k]);

			// echo "getenv=$k==$a\n";
			// echo "_ENV[$k] ==$b\n";

			$this->assertArrayHasKey($k, $_ENV);
			// $this->assertNotEmpty($_ENV[$k]);
		}
	}

}
