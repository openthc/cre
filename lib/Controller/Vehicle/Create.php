<?php
/**
 * Create a Vehicle owned by a License
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Controller\Vehicle;

class Create extends \OpenTHC\CRE\Controller\Base
{
	use \OpenTHC\Traits\JSONValidator;

	/**
	 *
	 */
	function __invoke($REQ, $RES, $ARG)
	{
		$oid = \Edoceo\Radix\ULID::generate();

		// Vehicle Object
		$obj = array(
			'id' => $oid,
			'name' => $_POST['name'],
			'type' => $_POST['type'],
		);

		// Check Vehicle Record
		$sql = 'SELECT id FROM vehicle WHERE license_id = :l AND name = :n';
		$arg = array(
			':l' => $_SESSION['License']['id'],
			':n' => $_POST['name'],
		);
		$chk = $this->_container->DB->fetchRow($sql, $arg);
		if (!empty($chk)) {
			return $RES->withJSON([
				'meta' => [ 'note' => 'Vehicle Duplicate [CSC-030]' ],
				'data' => $chk,
			], 409);
		}

		// Vehicle Record
		$rec = array(
			'license_id' => $_SESSION['License']['id'],
			'id' => $oid,
			'hash' => null,
			'name' => $obj['name'],
			'meta' => json_encode($obj),
		);
		$rec['hash'] = sha1($rec['meta']);

		$this->_container->DB->query('BEGIN');
		$this->_container->DB->insert('vehicle', $rec);
		$this->logAudit('Vehicle/Create', $oid, $rec['meta']);
		$this->_container->DB->query('COMMIT');

		return $RES->withJSON([
			'meta' => [],
			'data' => $obj,
		], 201);

	}
}
