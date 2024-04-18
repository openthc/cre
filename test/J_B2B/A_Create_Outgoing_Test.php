<?php
/**
 * Create Outgoing Transfer
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\J_B2B;

class A_Create_Outgoing_Test extends \OpenTHC\CRE\Test\Base
{
	protected $_url_path = '/b2b';
	protected $_tmp_file = '/tmp/unit-test-transfer.json';

	function test_create_deliver_g_to_p()
	{
		$this->auth(OPENTHC_TEST_CLIENT_SERVICE_A, OPENTHC_TEST_CLIENT_COMPANY_A, OPENTHC_TEST_CLIENT_LICENSE_A);

		$res = $this->_post($this->_url_path, [
			'license_id_target' => OPENTHC_TEST_CLIENT_LICENSE_B,
			'depart' => date(\DateTime::RFC3339, time() + 3600),
			'arrive' => date(\DateTime::RFC3339, time() + 86400),
			'method' => 'deliver', // deliver, pick-up, carrier
			'contact' => [
				'id' => OPENTHC_TEST_CLIENT_CONTACT_A,
			]
		]);
		$res = $this->assertValidResponse($res, 201);

		$T = $res['data'];
		$this->assertIsArray($T);
		$this->assertCount(5, $T);
		$this->assertNotEmpty($T['id']);

		$res = $this->httpClient->delete($this->_url_path . '/' . $T['id']);
		$res = $this->assertValidResponse($res, 202);

	}

	function test_create_deliver_p_to_r()
	{
		$this->auth(OPENTHC_TEST_CLIENT_SERVICE_A, OPENTHC_TEST_CLIENT_COMPANY_B, OPENTHC_TEST_CLIENT_LICENSE_B);

		$res = $this->_post($this->_url_path, [
			'license_id_target' => $_ENV['api-license-d'],
			'depart' => date(\DateTime::RFC3339, time() + 3600),
			'arrive' => date(\DateTime::RFC3339, time() + 86400),
			'method' => 'deliver', // deliver, pick-up, carrier
			'contact' => [
				'id' => OPENTHC_TEST_CLIENT_CONTACT_B,
			]
		]);

		$res = $this->assertValidResponse($res, 201);

		$T = $res['data'];
		$this->assertIsArray($T);
		$this->assertCount(5, $T);
		$this->assertNotEmpty($T['id']);

		$res = $this->httpClient->delete($this->_url_path . '/' . $T['id']);
		$res = $this->assertValidResponse($res, 202);

	}

	// function test_create_deliver_c_to_d()

	// function test_create_pickup_a_to_b()
	// function test_create_pickup_b_to_c()
	// function test_create_pickup_c_to_d()
	// function test_create_pickup_d_to_e()

	// function test_create_carrier_a_to_b_via_h()
	// function test_create_carrier_b_to_c_via_i()

}
