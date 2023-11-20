<?php
/**
 * Single Crop
 */

namespace OpenTHC\CRE\Controller\Crop;

class Single extends \OpenTHC\CRE\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		$sql = 'SELECT * FROM plant WHERE license_id = :l AND id = :g';
		$arg = array(
			':l' => $_ENV['license_id'],
			':g' => $ARG['id']
		);

		$rec =$this->_container->DB->fetch_row($sql, $arg);
		if (empty($rec['id'])) {
			return $this->send404('Crop not found [CPS#027]');
		}

		return $RES->withJSON([
			'meta' => [],
			'data' => json_decode($rec['meta'], true),
		]);

	}
}
