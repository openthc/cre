<?php
/**
 * Single Product
 */

namespace OpenTHC\CRE\Controller\Product;

class Single extends \OpenTHC\CRE\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		$sql = 'SELECT * FROM product WHERE license_id = :l AND id = :g';
		$arg = array(
			':l' => $_ENV['license_id'],
			':g' => $ARG['id']
		);

		$rec =$this->_container->DB->fetch_row($sql, $arg);
		if (empty($rec['id'])) {
			return $this->send404('Product not found [CPS#020]');
		}

		return $RES->withJSON(array(
			'status' => 'success',
			'result' => json_decode($rec['meta'], true),
		));

	}
}
