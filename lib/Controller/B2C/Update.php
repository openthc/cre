<?php
/**
 * Update B2C
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Controller\B2C;

class Update extends \OpenTHC\CRE\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{

		$sql = 'SELECT id, meta FROM b2c_sale WHERE license_id = :l AND id = :g';
		$arg = array(
			':l' => $_SESSION['License']['id'],
			':g' => $ARG['id'],
		);

		$chk = $this->_container->DB->fetch_row($sql, $arg);
		if (empty($chk)) {
			return $this->send404('B2C Sale not found [CSU-021]');
		}

		// Old Object
		$obj0 = json_decode($chk['meta'], true);

		// New Object
		$obj1 = array(
			'name' => $_POST['name'],
			'type' => $_POST['type'],
		);

		$meta = json_encode($obj1);

		$this->_container->DB->query('BEGIN');

		// Update the record
		$sql = 'UPDATE b2c_sale SET hash = :h, meta = :m WHERE id = :g';
		$arg = array(
			':g' => $ARG['id'],
			':h' => sha1($meta),
			':m' => $meta
		);
		$this->_container->DB->query($sql, $arg);

		// Log the change
		$this->_container->DB->insert('log_audit', array(
			'code' => 'B2C/Update',
			'meta' => json_encode(array(
				'old' => $obj0,
				'new' => $obj1
			))
		));

		$this->_container->DB->query('COMMIT');

		return $RES->withJSON(array(
			'meta' => [],
			'data' => $obj1
		));

	}

}
