<?php
/**
 * Create Outgoing Transfer
 */

namespace Test\B2B;

class Update_Outgoing extends \Test\Components\OpenTHC_Test_Case
{
	protected $_url_path = '/b2b';
	protected $_tmp_file = '/tmp/unit-test-transfer.json';

	function test_update_deliver_g_to_p()
	{
		$this->auth($_ENV['api-service-a'], $_ENV['api-company-g0'], $_ENV['api-license-g0']);

		// Now Create a G_TO_P Transfer
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
		$T0 = $res['data'];
		$this->assertIsArray($T0);
		$this->assertCount(5, $T0);
		$this->assertNotEmpty($T0['id']);
		// var_dump($T0);

		$l = $this->find_random_lot();
		$res = $this->_post($this->_url_path . '/' . $T0['id'], [
			'lot_id' => $l['id'],
			'qty' => 10,
		]);
		$res = $this->assertValidResponse($res, 201);


		// Examine Pending Transfer
		$res = $this->httpClient->get($this->_url_path . '/' . $T0['id']);
		$res = $this->assertValidResponse($res, 200);
		$T1 = $res['data'];
		// var_dump($T1);
		$this->assertIsArray($T1);
		$this->assertCount(12, $T1);
		$this->assertNotEmpty($T1['id']);
		$this->assertIsArray($T1['line_item_list']);
		$this->assertCount(1, $T1['line_item_list']);


		// Commit Transfer
		$res = $this->_post($this->_url_path . '/' . $T1['id'], [ 'status' => 'commit' ]);
		$res = $this->assertValidResponse($res, 202);
		$T2 = $res['data'];
		// var_dump($T2);
		$this->assertIsArray($T2);
		$this->assertCount(12, $T2);
		$this->assertNotEmpty($T2['id']);
		$this->assertEquals(307, $T2['stat']);
		$this->assertEquals(307, $T2['transfer_outgoing_stat']);
		// $this->assertIsArray($T2['line_item_list']);
		// $this->assertCount(1, $T2['line_item_list']);

		// $T1 = $this->httpClient->put($this->_url_path . '/outgoing/' . $T0['id'], [
		// 	'json' => [ 'status' => 'commit' ],
		// ]);

		//  $obj = $this->_data_stash_get();
		// $this->assertIsArray($obj);


		// Update
		// $res = $this->_post($this->_url_path, [
		// 	'license_id_target' => $_ENV['api-license-p0'],
		// 	'depart' => date(\DateTime::RFC3339, time() + 3600),
		// 	'arrive' => date(\DateTime::RFC3339, time() + 86400),
		// 	'method' => 'deliver', // deliver, pick-up, carrier
		// 	'contact' => [
		// 		'id' => $_ENV['api-contact-g0'],
		// 	]
		// ]);

		// $obj = $res['data'];

		// $res = $this->_post($this->_url_path . '/' . $obj['id']);

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

	function test_update_deliver_p_to_r()
	{
		$this->auth($_ENV['api-service-a'], $_ENV['api-company-p0'], $_ENV['api-license-p0']);

		// Now Create a P_TO_R Transfer
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
		$T0 = $res['data'];
		$this->assertIsArray($T0);
		$this->assertCount(5, $T0);
		$this->assertNotEmpty($T0['id']);
		// var_dump($T0);

		$l = $this->find_random_lot();
		$res = $this->_post($this->_url_path . '/' . $T0['id'], [
			'lot_id' => $l['id'],
			'qty' => 10,
		]);
		$res = $this->assertValidResponse($res, 201);

		// Examine Pending Transfer
		$res = $this->httpClient->get($this->_url_path . '/' . $T0['id']);
		$res = $this->assertValidResponse($res, 200);
		$T1 = $res['data'];
		// var_dump($T1);
		$this->assertIsArray($T1);
		$this->assertCount(12, $T1);
		$this->assertNotEmpty($T1['id']);
		$this->assertIsArray($T1['line_item_list']);
		$this->assertCount(1, $T1['line_item_list']);


		// Commit Transfer
		// $res = $this->_post($this->_url_path . '/' . $T1['id'] . '/commit');
		$res = $this->_post($this->_url_path . '/' . $T1['id'], [ 'status' => 'commit' ]);
		$res = $this->assertValidResponse($res, 202);
		$T2 = $res['data'];
		// var_dump($T2);
		$this->assertIsArray($T2);
		$this->assertCount(12, $T2);
		$this->assertNotEmpty($T2['id']);
		$this->assertEquals(307, $T2['stat']);
		$this->assertEquals(307, $T2['transfer_outgoing_stat']);
		// $this->assertIsArray($T2['line_item_list']);
		// $this->assertCount(1, $T2['line_item_list']);

		// Update
		// $res = $this->_post($this->_url_path, [
		// 	'license_id_target' => $_ENV['api-license-p0'],
		// 	'depart' => date(\DateTime::RFC3339, time() + 3600),
		// 	'arrive' => date(\DateTime::RFC3339, time() + 86400),
		// 	'method' => 'deliver', // deliver, pick-up, carrier
		// 	'contact' => [
		// 		'id' => $_ENV['api-contact-g0'],
		// 	]
		// ]);

	}

}
