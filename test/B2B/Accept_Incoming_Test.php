<?php
/**
 * Accept Incoming Transfer
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Test\B2B;

class Accept_Incoming_Test extends \OpenTHC\CRE\Test\Base
{
	protected $_url_path = '/b2b';

	function test_accept_g_to_p()
	{
		$this->auth($_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'], $_ENV['OPENTHC_TEST_CLIENT_COMPANY_B'], $_ENV['OPENTHC_TEST_CLIENT_LICENSE_B']);

		$res = $this->httpClient->get($this->_url_path . '/incoming');
		$res = $this->assertValidResponse($res);
		$this->assertIsArray($res['meta']);
		$this->assertGreaterThanOrEqual(1, count($res['data']));

		$t0 = $res['data'][0];

		// Patch?
		$res = $this->_post($this->_url_path . '/' . $t0['id'] . '/accept', []);
		$res = $this->assertValidResponse($res, 201);
		$this->assertIsArray($res['meta']);
		$this->assertIsArray($res['data']);
		$this->assertCount(12, $res['data']);

		$t1 = $res['data'];
		$this->assertEquals(307, $t1['stat']);

	}

	function test_accept_p_to_r()
	{
		$this->auth($_ENV['OPENTHC_TEST_CLIENT_SERVICE_A'], $_ENV['OPENTHC_TEST_CLIENT_COMPANY_D'], $_ENV['OPENTHC_TEST_CLIENT_LICENSE_D']);

		$Z = $this->find_random_section();

		$res = $this->httpClient->get($this->_url_path . '/incoming');
		$res = $this->assertValidResponse($res);
		$this->assertIsArray($res['meta']);
		$this->assertGreaterThanOrEqual(1, count($res['data']));

		$t0 = $res['data'][0];

		// Post Empty Array to Accept ALL, FULL
		$res = $this->_post($this->_url_path . '/' . $t0['id'] . '/accept', [
			'section_id' => $Z['id'],
		]);
		$res = $this->assertValidResponse($res, 201);

		// $this->assertIsArray($res['meta']);
		// $this->assertIsArray($res['data']);
		// $this->assertIsArray($res['data']['transfer']);
		// $this->assertIsArray($res['data']['transfer_item']);

		// $t1 = $res['data']['transfer'];

		// $this->assertEquals(307, $t1['b2b_outgoing_stat']);
		// $this->assertEquals(202, $t1['b2b_incoming_stat']);

	}

}
