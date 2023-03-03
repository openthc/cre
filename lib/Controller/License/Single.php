<?php
/**
 * Single License
 *
 * SPDX-License-Identifier: MIT
 */

namespace App\Controller\License;

class Single extends \App\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		$sql = 'SELECT * FROM license WHERE id = :g';
		$arg = array(':g' => $ARG['id']);
		$rec =$this->_container->DB->fetch_row($sql, $arg);

		if (empty($rec)) {
			return $this->send404('License not found [CLS-017]');
		}

		$obj = json_decode($rec['meta'], true);
		if (empty($obj)) {
			$obj = array();
		}
		unset($rec['meta']);
		$obj = array_merge($obj, $rec);

		return $RES->withJSON(array(
			'meta' => array(),
			'data' => $obj,
		));

	}
}
