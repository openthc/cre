<?php
/**
 * Create Outgoing Transfer
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\J_B2B;

class D_Create_Incoming_Test extends \OpenTHC\CRE\Test\Base_Case
{
	protected $_url_path = '/b2b';
	protected $_tmp_file = '/tmp/unit-test-transfer.json';

	function test_create_carrier_p_from_g()
	{
		$this->auth($_ENV['api-service-a'], $_ENV['api-company-b'], $_ENV['api-license-b']);

		$res = $this->_post($this->_url_path, [
			'license_id_source' => $_ENV['api-license-a'],
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
		$this->auth($_ENV['api-service-a'], $_ENV['api-company-c'], $_ENV['api-license-c']);

		$res = $this->_post($this->_url_path, [
			'license_id_source' => $_ENV['api-license-b'],
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
		$this->auth($_ENV['api-service-c'], $_ENV['api-company-d'], $_ENV['api-license-d']);

		$res = $this->_post($this->_url_path, [
			'license_id_source' => $_ENV['api-license-b'],
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
		$this->auth($_ENV['api-service-a'], $_ENV['api-company-b'], $_ENV['api-license-b']);

		$res = $this->_post($this->_url_path, [
			'license_id_source' => $_ENV['api-license-a'],
			'depart' => date(\DateTime::RFC3339, time() + 3600),
			'arrive' => date(\DateTime::RFC3339, time() + 86400),
			'method' => 'pickup',
			'contact' => [
				'id' => $_ENV['api-contact-b'],
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
		// $this->assertEquals('UNITTEST Variety CREATE', $s0['name']);

	}


	function test_create_pickup_l_from_p()
	{
		$this->auth($_ENV['api-service-a'], $_ENV['api-company-d'], $_ENV['api-license-d']);

		$res = $this->_post($this->_url_path, [
			'license_id_source' => $_ENV['api-license-b'],
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
		$this->auth($_ENV['api-service-a'], $_ENV['api-company-b'], $_ENV['api-license-b']);

		$res = $this->_post($this->_url_path, [
			'license_id_source' => $_ENV['api-license-d'],
			'depart' => date(\DateTime::RFC3339, time() + 3600),
			'arrive' => date(\DateTime::RFC3339, time() + 86400),
			'method' => 'pickup',
			'contact' => [
				'id' => $_ENV['api-contact-b'],
			]
		]);

		$this->assertValidResponse($res, 201, __METHOD__);
	}

}
