<?php
/**
 * Create Outgoing Transfer
 */

namespace Test\B2B;

class Create_Incoming extends \Test\Components\OpenTHC_Test_Case
{
	protected $_tmp_file = '/tmp/unit-test-transfer.json';
	protected $_url = '/transfer/incoming';

	function test_fake()
	{
		$this->assertTrue(true);
	}

	function x_test_create_carrier_p_from_g()
	{
		$this->auth($_ENV['api-program-a'], $_ENV['api-company-p0'], $_ENV['api-license-p0']);

		$res = $this->_post($this->_url, [
			'source_license_id' => $_ENV['api-license-g0'],
			'depart' => date(\DateTime::RFC3339, time() + 3600),
			'arrive' => date(\DateTime::RFC3339, time() + 86400),
			'method' => 'pickup', // pick-up, carrier
			'carrier' => [
				'id' => $_ENV['api-carrier-id'],
			]
		]);

		$this->assertValidResponse($res, 201, __METHOD__);

	}

	function x_test_create_carrier_l_from_p()
	{
		$this->auth($_ENV['api-program-a'], $_ENV['api-company-l0'], $_ENV['api-license-l0']);

		$res = $this->_post($this->_url, [
			'source_license_id' => $_ENV['api-license-p0'],
			'depart' => date(\DateTime::RFC3339, time() + 3600),
			'arrive' => date(\DateTime::RFC3339, time() + 86400),
			'method' => 'pickup', // pick-up, carrier
			'carrier' => [
				'id' => $_ENV['api-carrier-id'],
			]
		]);

		$this->assertValidResponse($res, 201, __METHOD__);

	}

	function x_test_create_carrier_r_from_p()
	{
		$this->auth($_ENV['api-program-c'], $_ENV['api-company-r0'], $_ENV['api-license-r0']);

		$res = $this->_post($this->_url, [
			'source_license_id' => $_ENV['api-license-p0'],
			'depart' => date(\DateTime::RFC3339, time() + 3600),
			'arrive' => date(\DateTime::RFC3339, time() + 86400),
			'method' => 'pickup', // pick-up, carrier
			'carrier' => [
				'id' => $_ENV['api-carrier-id'],
			]
		]);

		$this->assertValidResponse($res, 201, __METHOD__);

	}

	// P files on behalf of G
	function x_test_create_pickup_p_from_g()
	{
		$this->auth($_ENV['api-program-a'], $_ENV['api-company-p0'], $_ENV['api-license-p0']);

		$res = $this->_post($this->_url, [
			'source_license_id' => $_ENV['api-license-g0'],
			'depart' => date(\DateTime::RFC3339, time() + 3600),
			'arrive' => date(\DateTime::RFC3339, time() + 86400),
			'method' => 'pickup', // pick-up, carrier
			'contact' => [
				'id' => $_ENV['api-contact-p0'],
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


	function x_test_create_pickup_l_from_p()
	{
		$this->auth($_ENV['api-program-a'], $_ENV['api-company-l0'], $_ENV['api-license-l0']);

		$res = $this->_post($this->_url, [
			'source_license_id' => $_ENV['api-license-p0'],
			'depart' => date(\DateTime::RFC3339, time() + 3600),
			'arrive' => date(\DateTime::RFC3339, time() + 86400),
			'method' => 'pickup', // pick-up, carrier
			'contact' => [
				'id' => $_ENV['api-contact-l0'],
			]
		]);

		$this->assertValidResponse($res, 201, __METHOD__);
	}

	function x_test_create_pickup_r_from_p()
	{
		$this->auth($_ENV['api-program-a'], $_ENV['api-company-p0'], $_ENV['api-license-p0']);

		$res = $this->_post($this->_url, [
			'source_license_id' => $_ENV['api-license-r0'],
			'depart' => date(\DateTime::RFC3339, time() + 3600),
			'arrive' => date(\DateTime::RFC3339, time() + 86400),
			'method' => 'pickup', // pick-up, carrier
			'contact' => [
				'id' => $_ENV['api-contact-p0'],
			]
		]);

		$this->assertValidResponse($res, 201, __METHOD__);
	}

}
