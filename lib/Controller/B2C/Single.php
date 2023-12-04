<?php
/**
 * Single B2C
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Controller\B2C;

class Single extends \OpenTHC\CRE\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		$sql = 'SELECT * FROM b2c_sale WHERE license_id = :l AND id = :g';
		$arg = array(
			':l' => $_SESSION['License']['id'],
			':g' => $ARG['id']
		);

		$rec =$this->_container->DB->fetch_row($sql, $arg);
		if (empty($rec['id'])) {
			return $this->send404('Sale not found [CSS-020]');
		}

		return $RES->withJSON(array(
			'meta' => [],
			'data' => json_decode($rec['meta'], true),
		));

	}
}
