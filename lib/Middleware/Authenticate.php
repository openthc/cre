<?php
/**
 * Authenticate the Session
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Middleware;

class Authenticate extends \OpenTHC\Middleware\Base
{
	/**
	 *
	 */
	public function __invoke($REQ, $RES, $NMW)
	{
		$this->evalJWT();

		if (empty($_SESSION['service_id'])) {
			return $RES->withJSON([
				'data' => null,
				'meta' => [ 'detail' => 'Not Authorized [LMA-015]' ]
			], 403);
		}

		if (empty($_SESSION['company_id'])) {
			return $RES->withJSON([
				'data' => null,
				'meta' => [ 'detail' => 'Not Authorized [LMA-022]' ]
			], 403);
		}

		if (empty($_SESSION['license_id'])) {
			return $RES->withJSON([
				'data' => null,
				'meta' => [ 'detail' => 'Not Authorized [LMA-029]' ]
			], 403);
		}

		$RES = $NMW($REQ, $RES);

		return $RES;

	}

	/**
	 * Evaluate the JWT Parameters
	 */
	function evalJWT()
	{
		$jwt = null;

		if ( ! empty($_GET['jwt'])) {
			$jwt = $_GET['jwt'];
		}
		if ( ! empty($_SERVER['HTTP_OPENTHC_JWT'])) {
			$jwt = $_SERVER['HTTP_OPENTHC_JWT'];
		}
		if ( ! empty($_SERVER['HTTP_AUTHORIZATION'])) {
			if (preg_match('/^Bearer jwt:(.+)$/', $_SERVER['HTTP_AUTHORIZATION'], $m)) {
				$jwt = $m[1];
			}
		}

		// Check JWT
		if ( ! empty($jwt)) {

			$chk = \OpenTHC\JWT::decode($jwt);

			// Mostly Real Now
			$_SESSION['Contact'] = [
				'id' => $chk['sub'],
			];
			// Should have Matching Header
			// $_SERVER['HTTP_OPENTHC_CONTACT']

			$_SESSION['Company'] = [
				'id' => $chk['company'],
			];
			// Should have Matching Header
			// $_SERVER['HTTP_OPENTHC_COMPANY']

			$_SESSION['License'] = [
				'id' => $chk['license'],
			];
			// Should have Matching Header
			// $_SERVER['HTTP_OPENTHC_LICENSE']

			// Legacy Session Values in CRE
			$_SESSION['service_id'] = $jwt['iss'];
			$_SESSION['contact_id'] = $_SESSION['Contact']['id'];
			$_SESSION['company_id'] = $_SESSION['Company']['id'];
			$_SESSION['license_id'] = $_SESSION['License']['id'];

			$_ENV['service_id'] = $_SESSION['service_id'];
			$_ENV['contact_id'] = $_SESSION['Contact']['id'];
			$_ENV['company_id'] = $_SESSION['Company']['id'];
			$_ENV['license_id'] = $_SESSION['License']['id'];
			$_ENV['license_id'] = $_SESSION['License']['id'];

			// if (empty($_SESSION['Company']['id'])) {
			// 	return $RES->withJSON(['meta' => [ 'detail' => 'Invalid Company' ]], 400);
			// }
			// if (empty($_SESSION['Contact']['id'])) {
			// 	return $RES->withJSON(['meta' => [ 'detail' => 'Invalid Contact' ]], 400);
			// }
			// if (empty($_SESSION['License']['id'])) {
			// 	return $RES->withJSON(['meta' => [ 'detail' => 'Invalid License' ]], 400);
			// }

		}

	}
}
