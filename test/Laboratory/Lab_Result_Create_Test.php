<?php
/**
 *
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\Laboratory;

class Lab_Result_Create_Test extends \OpenTHC\CRE\Test\Base
{
	protected function setUp() : void
	{
		parent::setUp();

	}

	function test_create_sample()
	{
		$this->httpClient = $this->makeHTTPClient([
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_A'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_A'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_A'],
		]);

		$res = $this->httpClient->get('/inventory');
		$res = $this->assertValidResponse($res);
		$this->assertIsArray($res['meta']);
		$this->assertGreaterThan(1, count($res['data']));
		$i = \array_rand($res['data']);
		$l0 = $res['data'][$i];


		// Now Send as a Sample to a Laboratory through b2b_transfer
		$res = $this->_post('/b2b', [
			'license_id_target' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_D'],
			'depart' => date(\DateTime::RFC3339, time() + 3600),
			'arrive' => date(\DateTime::RFC3339, time() + 86400),
			'method' => 'deliver', // deliver, pick-up, carrier
			'contact' => [
				'id' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_A'],
			]
		]);

		$res = $this->assertValidResponse($res, 201);
		$T0 = $res['data'];

		$l = $this->find_random_inventory();

		$url = sprintf('/b2b/%s', $T0['id']);
		$res = $this->_post($url, [
			'inventory_id' => $l['id'],
			'qty' => 10,
		]);
		$res = $this->assertValidResponse($res, 201);

		// Examine Pending Transfer
		$res = $this->httpClient->get(sprintf('/b2b/%s', $T0['id']));
		$res = $this->assertValidResponse($res, 200);
		$T1 = $res['data'];
		$this->assertIsArray($T1);
		$this->assertCount(12, $T1);
		$this->assertNotEmpty($T1['id']);
		$this->assertIsArray($T1['line_item_list']);
		$this->assertCount(1, $T1['line_item_list']);

		// Commit Transfer
		$res = $this->_post(sprintf('/b2b/%s', $T0['id']), [ 'status' => 'commit' ]);
		$res = $this->assertValidResponse($res, 202);
		$T2 = $res['data'];
		$this->assertIsArray($T2);
		$this->assertCount(12, $T2);
		$this->assertNotEmpty($T2['id']);
		$this->assertEquals(307, $T2['stat']);
		$this->assertEquals(307, $T2['transfer_outgoing_stat']);

	}


	function test_create_result()
	{
		$this->httpClient = $this->makeHTTPClient([
			'service' => $_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'],
			'contact' => $_ENV['OPENTHC_TEST_CLIENT_CONTACT_D'],
			'company' => $_ENV['OPENTHC_TEST_CLIENT_COMPANY_D'],
			'license' => $_ENV['OPENTHC_TEST_CLIENT_LICENSE_D'],
		]);


		$res = $this->httpClient->get('/b2b/incoming');
		$res = $this->assertValidResponse($res);

		$this->assertIsArray($res['meta']);
		$this->assertGreaterThan(1, count($res['data']));

		// $res = $this->httpClient->get('/inventory');
		// $res = $this->assertValidResponse($res);
		// $this->assertIsArray($res['meta']);
		// $this->assertGreaterThan(1, count($res['data']));
		// $i = \array_rand($res['data']);
		// $l0 = $res['data'][$i];

	}

}
