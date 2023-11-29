<?php
/**
 * B2B Search
 */

namespace OpenTHC\CRE\Controller\B2B;

class Search extends \OpenTHC\CRE\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
	}

	/**
	 *
	 */
	function incoming($REQ, $RES, $ARG)
	{
		$dbc = $this->_container->DB;

		$sql = 'SELECT * FROM b2b_incoming WHERE license_id_target = :l AND stat = 200';
		$arg = array(
			':l' => $_SESSION['License']['id'],
		);

		$res = $dbc->fetchAll($sql, $arg);

		return $RES->withJSON([
			'meta' => [],
			'data' => $res,
		]);

	}

	/**
	 *
	 */
	function outgoing($REQ, $RES, $ARG)
	{
		$dbc = $this->_container->DB;

		$sql = 'SELECT * FROM b2b_outgoing WHERE license_id_source = :l';
		$arg = array(
			':l' => $_SESSION['License']['id'],
		);

		$res = $dbc->fetchAll($sql, $arg);

		return $RES->withJSON([
			'meta' => [],
			'data' => $res,
		]);

	}
}
