<?php
/**
 * Search License Data
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Controller\License;

class Search extends \OpenTHC\CRE\Controller\Base
{
	/**
	 *
	 */
	function __invoke($REQ, $RES, $ARG)
	{
		$sql_where = array();
		$sql_param = array();

		if (!empty($_SESSION['Company']['id'])) {
			$sql_where[] = 'company_id = :c0';
			$sql_param[':c0'] = $_SESSION['Company']['id'];
		}

		if (count($sql_where)) {
			$sql_where = 'WHERE ' . implode(' AND ', $sql_where);
		} else {
			$sql_where = null;
		}


		$sql_query = <<<SQL
		SELECT id, hash, name, meta
		FROM license
		$sql_where
		ORDER BY id
		SQL;

		$ret = array();
		$res = $this->_container->DB->fetchAll($sql_query, $sql_param);

		foreach ($res as $rec) {

			$obj = json_decode($rec['meta'], true) ?: array();
			unset($rec['meta']);
			$obj = array_merge($obj, $rec);

			$ret[] = $obj;
		}

		return $RES->withJSON(array(
			'meta' => [],
			'data' => $ret,
		));

	}
}
