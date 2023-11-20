<?php
/**
 * Open a Session
 */

namespace OpenTHC\CRE\Controller\Auth;

class Open extends \OpenTHC\CRE\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		$RES = $this->_open_session($RES);
		if (200 != $RES->getStatusCode()) {
			return $RES;
		}

		// if (empty($_POST['license-key'])) {
		// 	return $RES->withJSON([ 'meta' => ['note' => 'Missing "license-key" missing [CAO-022]' ]], 400);
		// }

		// if (empty($_SESSION['contact_id'])) {
		// 	return $RES->withJSON([ 'meta' => ['note' => 'Unknown Contact [CAO-035]' ]], 403);
		// }

		//if (empty($_POST['secret-mac'])) {
		//	return $RES->withJSON([ 'meta' => ['note' => 'Missing "secret-mac" missing [CAO-029]' ]], 400);
		//}

		// if (preg_match('/^v3[0-9a-f]{194}$/', $tok)) {
		// 	// v3 oa2 maghic type
		// } elseif (preg_match('/^v1[0-9a-f]{64}$/', $tok)) {
		// 	// v0
		// } elseif (preg_match('/^v2:01\w{24}:01\w{24}:[0-9a-f]{64}/', $tok)) {
		// 	// v1
		// 	$p0['public']
		// 	$l0['public'];
		// 	$ah0 = '';
		//
		// 	$ah2 = _v2_method();
		// 	if ($ah0 == $ah2) {
		// 		// Good
		// 	}
		//
		// 	// $ah1 = _v1_method();
		// 	// if ($ah0 == $ah1) {
		// 	// 	// Good + Warn
		// 	// }
		//
		// } else {
		// 	// Bullshit pattern
		// }

		return $RES->withJSON([
			'data' => session_id(),
			'meta' => [],
		]);

	}

	/**
	 *
	 */
	function _open_session($RES)
	{
		if (empty($_POST['service'])) {
			return $RES->withJSON([
				'data' => null,
				'meta' => [ 'note' => 'Parameter "service" missing [CAO-074]' ]
			], 400);
		}

		if (empty($_POST['company'])) {
			return $RES->withJSON([
				'data' => null,
				'meta' => [ 'note' => 'Parameter "company" missing [CAO-080]' ]
			], 400);
		}

		if (empty($_POST['license'])) {
			return $RES->withJSON([
				'data' => null,
				'meta' => [ 'note' => 'Parameter "license" missing [CAO-086]' ]
			], 400);
		}

		$dbc = $this->_container->DB;

		// Lookup Service
		$sql = 'SELECT id, company_id FROM auth_service WHERE id = :c';
		$arg = array(':c' => $_POST['service']);
		$service_id = $dbc->fetchOne($sql, $arg);
		if (empty($service_id)) {
			return $RES->withJSON([
				'data' => null,
				'meta' => [ 'note' => 'Invalid "service" [CAO-098]' ]
			], 403);
		}

		// Lookup Company
		$sql = 'SELECT id FROM company WHERE id = :c';
		$arg = array(':c' => $_POST['company']);
		$company_id = $dbc->fetchOne($sql, $arg);
		if (empty($company_id)) {
			return $RES->withJSON([
				'data' => null,
				'meta' => [ 'note' => 'Invalid "company" [CAO-108]' ]
			], 403);
		}

		// Lookup License
		$sql = 'SELECT id,company_id,stat,name FROM license WHERE id = :l';
		$arg = array(':l' => $_POST['license']);
		$L = $dbc->fetchRow($sql, $arg);
		if (empty($L['id'])) {
			return $RES->withJSON([
				'data' => null,
				'meta' => [ 'note' => 'Invalid "license" [CAO-118]' ]
			], 403);
		}

		// Company and License Match?
		if ($company_id != $L['company_id']) {
			return $RES->withJSON([
				'data' => null,
				'meta' => [ 'note' => 'Invalid "license" [CAO-125]' ]
			], 403);
		}

		session_start();

		$_SESSION['id'] = session_id();
		$_SESSION['service_id'] = $service_id;
		$_SESSION['company_id'] = $company_id;
		$_SESSION['license_id'] = $L['id'];

		return $RES;
	}

}
