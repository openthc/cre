<?php
/**
 * Test Lot Processes
 */

namespace Test\Lot_Process;

class Alpha extends \Test\Components\OpenTHC_Test_Case
{
	protected function setUp() : void
	{
		parent::setUp();
		$this->auth($_ENV['api-program-a'], $_ENV['api-company-g0'], $_ENV['api-license-g0']);
	}

	public function test_convert_one_to_one()
	{
		$l0 = $this->find_random_lot();
		$p0 = $this->find_random_product();

		$res = $this->_post('/lot', [
			'source' => $l0['id'],
			'product_id' => $p0['id'],
			'qty' => 5,
		]);

		$this->assertValidResponse($res, 201);

	}

	public function test_convert_two_to_one()
	{
		$l0 = $this->find_random_lot();
		$l1 = $this->find_random_lot();

		$p0 = $this->find_random_product();

		$res = $this->_post('/lot', [
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
