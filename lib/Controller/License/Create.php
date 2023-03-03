<?php
/**
 * Create a Global License
 *
 * SPDX-License-Identifier: MIT
 */

namespace App\Controller\License;

class Create extends \App\Controller\Base
{
	/**
	 *
	 */
	function __invoke($REQ, $RES, $ARG)
	{
		$C = array();

		if (empty($_POST['company'])) {
			return $this->sendError('Missing Paramter "company" [CLC-017]', 400);
		}

		$sql = 'SELECT * FROM company WHERE id = :g';
		$arg = array(':g' => $_POST['company']);
		$rec = $this->_container->DB->fetchRow($sql, $arg);
		if (empty($rec['id'])) {
			return $this->sendError('Invalid Company [CLC-027]', 400);
		}
		$C = $rec;

		$obj = array(
			'company' => array(
				'id' => $C['id'],
				'name' => $C['name'],
			),
			'id' => $_POST['id'],
			'name' => $_POST['name'],
			'type' => $_POST['type'],
		);
		if (empty($obj['id'])) {
			$obj['id'] = _ulid();
		}

		$rec = array(
			'company_id' => $C['id'],
			'id' => $obj['id'],
			'name' => $obj['name'],
			'stat' => 200,
		);

		if (!empty($_POST['meta'])) {
			if (is_array($_POST['meta'])) {
				$obj = array_merge($obj, $_POST['meta']);
			}
		}

		$rec['meta'] = json_encode($obj);
		$rec['hash'] = sha1($rec['meta']);

		try {
			$this->_container->DB->insert('license', $rec);
			$this->logAudit('License/Create', $rec['id'], $_POST);
		} catch (\Exception $e) {
			$this->logAudit('License/Create/Failure', $rec['id'], json_encode($e->getMessage()));
			return $this->sendError($e->getMessage(), 500);
		}

		return $RES->withJSON(array(
			'meta' => [],
			'data' => $obj,
		), 201);

	}
}
