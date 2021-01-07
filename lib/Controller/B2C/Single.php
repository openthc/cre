<?php
/**
 * Single B2C
 */

namespace App\Controller\B2C;

class Single extends \App\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		$sql = 'SELECT * FROM b2c_sale WHERE license_id = :l AND id = :g';
		$arg = array(
			':l' => $_ENV['license_id'],
			':g' => $ARG['id']
		);

		$rec =$this->_container->DB->fetch_row($sql, $arg);
		if (empty($rec['id'])) {
			return $this->send404('Sale not found [CSS#020]');
		}

		return $RES->withJSON(array(
			'meta' => [],
			'data' => json_decode($rec['meta'], true),
		));

	}
}
