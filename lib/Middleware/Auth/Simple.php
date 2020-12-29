<?php
/**
 * Simple Authentication
 */

namespace App\Middleware\Auth;

class Simple extends \OpenTHC\Middleware\Base
{
	public function __invoke($REQ, $RES, $NMW)
	{
		$dbc = $this->_container->DB;

		if (empty($_POST['service'])) {
			return $RES->withJSON([
				'meta' => [ 'detail' => 'Parameter "service" missing [MAS-016]' ]
			], 400);
		}

		if (empty($_POST['company'])) {
			return $RES->withJSON([
				'meta' => [ 'detail' => 'Parameter "company" missing [MAS-022]' ]
			], 400);
		}

		if (empty($_POST['license'])) {
			return $RES->withJSON([
				'meta' => [ 'detail' => 'Parameter "license" missing [MAS-028]' ]
			], 400);
		}


		// Lookup Service
		$sql = 'SELECT id, company_id FROM auth_service WHERE id = :c';
		$arg = array(':c' => $_POST['service']);
		$service_id = $dbc->fetchOne($sql, $arg);
		if (empty($service_id)) {
			return $RES->withJSON([
				'meta' => [ 'detail' => 'Invalid "service" [MAS-039]' ]
			], 403);
		}


		// Lookup Company
		$sql = 'SELECT id FROM company WHERE id = :c';
		$arg = array(':c' => $_POST['company']);
		$company_id = $dbc->fetchOne($sql, $arg);
		if (empty($company_id)) {
			return $RES->withJSON([
				'meta' => [ 'detail' => 'Invalid "company" [MAS-050]' ]
			], 403);
		}


		// Lookup License
		$sql = 'SELECT id,company_id,stat,name FROM license WHERE id = :l';
		$arg = array(':l' => $_POST['license']);
		$L = $dbc->fetchRow($sql, $arg);
		if (empty($L['id'])) {
			return $RES->withJSON([
				'meta' => [ 'detail' => 'Invalid "license" [MAS-061]' ]
			], 403);
		}

		// Company and License Match?
		if ($company_id != $L['company_id']) {
			return $RES->withJSON([
				'meta' => [ 'detail' => 'Invalid "license" [MAS-068]' ]
			], 403);
		}

		$_SESSION['service_id'] = $service_id;
		$_SESSION['company_id'] = $company_id;
		$_SESSION['license_id'] = $L['id'];

		$RES = $NMW($REQ, $RES);

		return $RES;

	}
}
