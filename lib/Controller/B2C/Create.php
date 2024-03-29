<?php
/**
 * Create B2C Sale owned by a License
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Controller\B2C;

class Create extends \OpenTHC\CRE\Controller\Base
{
	use \OpenTHC\Traits\JSONValidator;

	function __invoke($REQ, $RES, $ARG)
	{
		$oid = \Edoceo\Radix\ULID::create();

		$obj = array(
			'id' => $oid,
			'name' => $_POST['name'],
			'type' => $_POST['type'],
		);

		$obj = json_encode($obj);

		$this->_container->DB->query('BEGIN');
		$this->_container->DB->insert('b2c_sale', array(
			'license_id' => $_SESSION['License']['id'],
			'id' => $oid,
			'hash' => sha1($obj),
			'meta' => $obj,
		));

		$this->_container->DB->query('INSERT INTO log_audit (code,meta) values (?,?)', array('Sale/Create', $obj));
		$this->_container->DB->query('COMMIT');

		return $RES->withJSON(array(
			'data' => $obj,
			'meta' => [],
		));

	}
}
