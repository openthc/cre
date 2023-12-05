<?php
/**
 * Single Vehicle
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Controller\Vehicle;

class Single extends \OpenTHC\CRE\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		$sql = 'SELECT * FROM vehicle WHERE license_id = :l AND id = :g';
		$arg = array(
			':l' => $_SESSION['License']['id'],
			':g' => $ARG['id']
		);

		$rec =$this->_container->DB->fetch_row($sql, $arg);
		if (empty($rec['id'])) {
			return $this->send404('Vehicle Not Found [CSS-020]');
		}

		return $RES->withJSON([
			'meta' => [],
			'data' => $rec,
		]);

	}
}
