<?php
/**
 * Single Section
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Controller\Section;

class Single extends \OpenTHC\CRE\Controller\Base
{
	/**
	 *
	 */
	function __invoke($REQ, $RES, $ARG)
	{
		$sql = 'SELECT * FROM section WHERE license_id = :l0 AND id = :pk';
		$arg = [
			':l0' => $_ENV['license_id'],
			':pk' => $ARG['id']
		];

		$rec =$this->_container->DB->fetch_row($sql, $arg);
		if (empty($rec['id'])) {
			return $this->send404('Section Not Found [CSS-027]');
		}

		return $RES->withJSON([
			'data' => json_decode($rec['meta'], true),
			'meta' => [],
		]);

	}
}
