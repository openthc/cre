<?php
/**
 * Test Lot Processes
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\G_Process;

class A_Alpha_Test extends \OpenTHC\CRE\Test\Base
{
	protected function setUp() : void
	{
		parent::setUp();
		$this->auth($_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'], $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'], $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A']);
	}

	public function test_convert_one_to_one()
	{
		$l0 = $this->find_random_inventory();
		$p0 = $this->find_random_product();

		$res = $this->_post('/inventory', [
			'source' => $l0['id'],
			'product_id' => $p0['id'],
			'qty' => 5,
		]);

		$this->assertValidResponse($res, 201);

	}

	public function test_convert_two_to_one()
	{
		$l0 = $this->find_random_inventory();
		$l1 = $this->find_random_inventory();

		$p0 = $this->find_random_product();

		$res = $this->_post('/inventory', [
			'source' => [
				[
					'id' => $l0['id'],
					'qty' => 10,
				],
				[
					'id' => $l1['id'],
					'qty' => 1,
				]
			],
			'product_id' => $p0['id'],
			'qty' => 10,
		]);

		$this->assertValidResponse($res, 201);

	}

}
