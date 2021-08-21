<?php
/**
 * Create Outgoing Transfer
 */

namespace Test\J_B2B;

class A_Create_Outgoing_Test extends \Test\Base_Case
{
	protected $_url_path = '/b2b';
	protected $_tmp_file = '/tmp/unit-test-transfer.json';

	function test_create_deliver_g_to_p()
	{
		$this->auth($_ENV['api-service-a'], $_ENV['api-company-g0'], $_ENV['api-license-g0']);

		$res = $this->_post($this->_url_path, [
			'license_id_target' => $_ENV['api-license-p0'],
			'depart' => date(\DateTime::RFC3339, time() + 3600),
			'arrive' => date(\DateTime::RFC3339, time() + 86400),
			'method' => 'deliver', // deliver, pick-up, carrier
			'contact' => [
				'id' => $_ENV['api-contact-g0'],
			]
		]);
		$res = $this->assertValidResponse($res, 201);

		$T = $res['data'];
		$this->assertIsArray($T);
		$this->assertCount(5, $T);
		$this->assertNotEmpty($T['id']);
		// var_dump($T);

		$res = $this->httpClient->delete($this->_url_path . '/' . $T['id']);
		$res = $this->assertValidResponse($res, 202);

	}

	function test_create_deliver_p_to_r()
	{
		$this->auth($_ENV['api-service-a'], $_ENV['api-company-p0'], $_ENV['api-license-p0']);

		$res = $this->_post($this->_url_path, [
			'license_id_target' => $_ENV['api-license-r0'],
			'depart' => date(\DateTime::RFC3339, time() + 3600),
			'arrive' => date(\DateTime::RFC3339, time() + 86400),
			'method' => 'deliver', // deliver, pick-up, carrier
			'contact' => [
				'id' => $_ENV['api-contact-p0'],
			]
		]);

		$res = $this->assertValidResponse($res, 201);

		$T = $res['data'];
		$this->assertIsArray($T);
		$this->assertCount(5, $T);
		$this->assertNotEmpty($T['id']);
		// var_dump($T);

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
