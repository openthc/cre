<?php
/**
 * Single Variety
 */

namespace OpenTHC\CRE\Controller\Variety;

class Single extends \OpenTHC\CRE\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		$sql = 'SELECT * FROM variety WHERE license_id = :l AND id = :g';
		$arg = array(
			':l' => $_SESSION['License']['id'],
			':g' => $ARG['id']
		);

		$rec =$this->_container->DB->fetch_row($sql, $arg);
		if (empty($rec['id'])) {
			return $this->send404('Variety Not Found [CSS-020]');
		}

		return $RES->withJSON([
			'meta' => [],
			'data' => $rec,
		]);

	}
}
