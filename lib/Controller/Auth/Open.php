<?php
/**
 * Open a Session
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Controller\Auth;

class Open extends \OpenTHC\CRE\Controller\Base
{
	use \OpenTHC\CRE\Traits\OpenAuthBox;

	/**
	 *
	 */
	function __invoke($REQ, $RES, $ARG)
	{
		// Works or Throws
		try {
			$act = $this->open_auth_box_header();
		} catch (\Exception $e) {
			return $RES->withJSON([
				'data' => null,
				'meta' => [ 'note' => $e->getMessage() ]
			], 401);
		}

		$SES = $this->open_session($act);

		// $Contact = $_SERVER['HTTP_OPENTHC_CONTACT_ID'];
		// if (empty($Contact)) {
		// 	return $RES->withJson([
		// 		'data' => null,
		// 		'meta' => [ 'note' => 'Invalid Contact [CAO-018]' ],
		// 	], 400);
		// }
		// $Company = $_SERVER['HTTP_OPENTHC_COMPANY_ID'];
		// if (empty($Company)) {
		// 	return $RES->withJson([
		// 		'data' => null,
		// 		'meta' => [ 'note' => 'Invalid Company [CAO-025]' ],
		// 	], 400);
		// }
		// $License = $_SERVER['HTTP_OPENTHC_LICENSE_ID'];
		// if (empty($License)) {
		// 	return $RES->withJson([
		// 		'data' => null,
		// 		'meta' => [ 'note' => 'Invalid License [CAO-032]' ],
		// 	], 400);
		// }

		$tok = _random_hash();
		$this->_container->Redis->setEx($tok, $expireTTL=21600, json_encode($SES));

		return $RES->withJSON([
			'data' => [
				'sid' => $tok,
			],
			'meta' => [],
		], 200);

	}

	/**
	 *
	 */
	function open_session($act)
	{
		$dbc = $this->_container->DB;

		// Lookup Service
		$sql = 'SELECT id, company_id, name, code FROM auth_service WHERE id = :s0 AND stat = 200';
		$arg = [ ':s0' => $act->service ];
		$Service = $dbc->fetchRow($sql, $arg);
		if (empty($Service['id'])) {
			throw new \Exception('Invalid Service [CAO-098]', 401);
		}

		// Lookup Contact
		$sql = 'SELECT id FROM contact WHERE id = :c0';
		$arg = array(':c0' => $act->contact);
		$Contact = $dbc->fetchRow($sql, $arg);
		if (empty($Contact['id'])) {
			throw new \Exception('Invalid Contact [CAO-087]', 404);
		}

		// Lookup Company
		$sql = 'SELECT id FROM company WHERE id = :c0 AND stat = 200';
		$arg = [ ':c0' => $act->company ];
		$Company = $dbc->fetchRow($sql, $arg);
		if (empty($Company['id'])) {
			throw new \Exception('Invalid Company [CAO-108]', 401);
		}

		// Lookup License
		if ( ! empty($act->license)) {
			$sql = 'SELECT id, company_id, stat, name FROM license WHERE id = :l0';
			$arg = [
				// ':c0' => $Company['id'],
				':l0' => $act->license,
			];
			$License = $dbc->fetchRow($sql, $arg);
			if (empty($License['id'])) {
				throw new \Exception('Invalid License [CAO-118]', 401);
			}

			// Company and License Match?
			if ($Company['id'] != $License['company_id']) {
				throw new \Exception('Invalid License [CAO-125]', 401);
			}
		}

		return [
			'Service' => $Service,
			'Contact' => [ 'id' => $act->contact ], // $Contact,
			'Company' => $Company,
			'License' => $License,
		];

	}

}
