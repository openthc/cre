<?php
/**
 * Create Incoming Transfer
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\B2B;

class D_Create_Incoming_Test extends \OpenTHC\CRE\Test\Base
{
	protected $_url_path = '/b2b';

	function test_create_carrier_p_from_g()
	{
		$this->auth($_ENV['OPENTHC_TEST_CLIENT_SERVICE_B'], $_ENV['OPENTHC_TEST_CLIENT_COMPANY_B'], $_ENV['OPENTHC_TEST_CLIENT_LICENSE_B']);

		$res = $this->_post($this->_url_path, [
			'license_id_source' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A'],
			'depart' => date(\DateTime::RFC3339, time() + 3600),
			'arrive' => date(\DateTime::RFC3339, time() + 86400),
			'method' => 'pickup',
			'carrier' => [
				'id' => $_ENV['api-carrier-id'],
			]
		]);

		$this->assertValidResponse($res, 201);

	}

	function test_create_carrier_l_from_p()
	{
		$this->auth($_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'], $_ENV['OPENTHC_TEST_CLIENT_COMPANY_C'], $_ENV['OPENTHC_TEST_CLIENT_LICENSE_C']);

		$res = $this->_post($this->_url_path, [
			'license_id_source' => OPENTHC_TEST_CLIENT_LICENSE_B,
			'depart' => date(\DateTime::RFC3339, time() + 3600),
			'arrive' => date(\DateTime::RFC3339, time() + 86400),
			'method' => 'pickup',
			'carrier' => [
				'id' => $_ENV['api-carrier-id'],
			]
		]);

		$this->assertValidResponse($res, 201, __METHOD__);

	}

	function test_create_carrier_r_from_p()
	{
		$this->auth($_ENV['OPENTHC_TEST_CLIENT_SERVICE_C'], $_ENV['OPENTHC_TEST_CLIENT_COMPANY_D'], $_ENV['OPENTHC_TEST_CLIENT_LICENSE_D']);

		$res = $this->_post($this->_url_path, [
			'license_id_source' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_B'],
			'depart' => date(\DateTime::RFC3339, time() + 3600),
			'arrive' => date(\DateTime::RFC3339, time() + 86400),
			'method' => 'pickup',
			'carrier' => [
				'id' => $_ENV['api-carrier-id'],
			]
		]);

		$this->assertValidResponse($res, 201, __METHOD__);

	}

	// P files on behalf of G
	function test_create_pickup_p_from_g()
	{
		$this->auth($_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'], $_ENV['OPENTHC_TEST_CLIENT_COMPANY_B'], $_ENV['OPENTHC_TEST_CLIENT_LICENSE_B']);

		$res = $this->_post($this->_url_path, [
			'license_id_source' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A'],
			'depart' => date(\DateTime::RFC3339, time() + 3600),
			'arrive' => date(\DateTime::RFC3339, time() + 86400),
			'method' => 'pickup',
			'contact' => [
				'id' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_B'],
			]
		]);

		$this->assertValidResponse($res, 201, __METHOD__);

		// $this->_post('/transfer/' . $t0['id'] . '/item', [
		// 	'inventory_id' => '',
		// 	'qty' => 10,
		// ]);
		// $this->_post('/transfer/' . $t0['id'] . '/item', [
		// 	'inventory_id' => '',
		// 	'qty' => 10,
		// ]);
		// $this->_post('/transfer/' . $t0['id'] . '/item', [
		// 	'inventory_id' => '',
		// 	'qty' => 10,
		// ]);

		// $this->assertIsArray($res);
		// $this->assertCount(2, $res);
		// $this->assertIsArray($res['data']);
		//
		// $s0 = $res['data'];
		// $this->assertNotEmpty($s0['id']);
		// $this->assertEquals('UNITTEST Variety CREATE', $s0['name']);

	}


	function test_create_pickup_l_from_p()
	{
		$this->auth($_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'], $_ENV['OPENTHC_TEST_CLIENT_COMPANY_D'], $_ENV['OPENTHC_TEST_CLIENT_LICENSE_D']);

		$res = $this->_post($this->_url_path, [
			'license_id_source' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_B'],
			'depart' => date(\DateTime::RFC3339, time() + 3600),
			'arrive' => date(\DateTime::RFC3339, time() + 86400),
			'method' => 'pickup',
			'contact' => [
				'id' => $_ENV['api-contact-d'],
			]
		]);

		$this->assertValidResponse($res, 201, __METHOD__);
	}

	function test_create_pickup_r_from_p()
	{
		$this->auth($_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'], $_ENV['OPENTHC_TEST_CLIENT_COMPANY_B'], $_ENV['OPENTHC_TEST_CLIENT_LICENSE_B']);

		$res = $this->_post($this->_url_path, [
			'license_id_source' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_D'],
			'depart' => date(\DateTime::RFC3339, time() + 3600),
			'arrive' => date(\DateTime::RFC3339, time() + 86400),
			'method' => 'pickup',
			'contact' => [
				'id' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_B'],
			]
		]);

		$this->assertValidResponse($res, 201, __METHOD__);
	}

}
