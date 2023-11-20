<?php
/**
 * Single Company
 */

namespace OpenTHC\CRE\Controller\Company;

class Single extends \OpenTHC\CRE\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		$sql = 'SELECT * FROM company WHERE id = :g';
		$arg = array(':g' => $ARG['id']);
		$rec = $this->_container->DB->fetch_row($sql, $arg);

		if (empty($rec)) {
			return $this->send404('Company not found [CCS-017]');
		}

		// @todo Merge Meta to Main?
		$rec['meta'] = json_decode($rec['meta'], true);

		return $RES->withJSON([
			'meta' => [],
			'data' => $rec,
		]);

	}
}
