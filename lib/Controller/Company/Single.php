<?php
/**
 * Single Company
 */

namespace App\Controller\Company;

class Single extends \App\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		$sql = 'SELECT * FROM company WHERE id = :g';
		$arg = array(':g' => $ARG['id']);
		$rec = $this->_container->DB->fetch_row($sql, $arg);

		if (empty($rec)) {
			return $this->send404('Company not found [CCS#017]');
		}

		// @todo Merge Meta to Main?
		$rec['meta'] = json_decode($rec['meta'], true);

		return $RES->withJSON([
			'meta' => [],
			'data' => $rec,
		]);

	}
}
