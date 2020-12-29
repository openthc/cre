<?php
/**
 * Single Product
 */

namespace App\Controller\Product;

class Type extends \App\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		$sql = 'SELECT * FROM product_type ORDER BY id';
		$res = $this->_container->DB->fetchAll($sql);

		return $RES->withJSON([
			'meta' => [
				'page' => [
					'size' => count($res),
					'count' => 1,
				]
			],
			'data' => $res,
		]);

	}
}
