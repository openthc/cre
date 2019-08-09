<?php
/**
 * Create Outgoing Transfer
 */

namespace Test\B2B;

class Create_Outgoing extends \Test\Components\OpenTHC_Test_Case
{
	protected $_tmp_file = '/tmp/unit-test-transfer.json';

	function test_create_deliver_g_to_p()
	{
		$this->auth($_ENV['api-program-a'], $_ENV['api-company-g0'], $_ENV['api-license-g0']);

		$res = $this->_post('/transfer', [
			'target_license_id' => $_ENV['api-license-p0'],
			'depart' => date(\DateTime::RFC3339, time() + 3600),
			'arrive' => date(\DateTime::RFC3339, time() + 86400),
			'method' => 'deliver', // deliver, pick-up, carrier
			'contact' => [
				'id' => $_ENV['api-contact-g0'],
			]
		]);
		$this->assertValidResponse($res, 201, __METHOD__);

		// $this->_post('/transfer/' . $t0['id'] . '/item', [
		// 	'lot_id' => '',
		// 	'qty' => 10,
		// ]);
		// $this->_post('/transfer/' . $t0['id'] . '/item', [
		// 	'lot_id' => '',
		// 	'qty' => 10,
		// ]);
		// $this->_post('/transfer/' . $t0['id'] . '/item', [
		// 	'lot_id' => '',
		// 	'qty' => 10,
		// ]);

		// $this->assertIsArray($res);
		// $this->assertCount(2, $res);
		// $this->assertIsArray($res['data']);
		//
		// $s0 = $res['data'];
		// $this->assertNotEmpty($s0['id']);
		// $this->assertEquals('UNITTEST Strain CREATE', $s0['name']);

	}

	function test_create_deliver_p_to_r()
	{
		$this->auth($_ENV['api-program-a'], $_ENV['api-company-p0'], $_ENV['api-license-p0']);

		$res = $this->_post('/transfer', [
			'target_license_id' => $_ENV['api-license-r0'],
			'depart' => date(\DateTime::RFC3339, time() + 3600),
			'arrive' => date(\DateTime::RFC3339, time() + 86400),
			'method' => 'deliver', // deliver, pick-up, carrier
			'contact' => [
				'id' => $_ENV['api-contact-p0'],
			]
		]);

		$this->assertValidResponse($res, 201, __METHOD__);

	}

	// function test_create_deliver_c_to_d()

	// function test_create_pickup_a_to_b()
	// function test_create_pickup_b_to_c()
	// function test_create_pickup_c_to_d()
	// function test_create_pickup_d_to_e()

	// function test_create_carrier_a_to_b_via_h()
	// function test_create_carrier_b_to_c_via_i()

}
