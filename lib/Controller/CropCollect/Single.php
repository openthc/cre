<?php
/**
 * Single CropCollect
 */

namespace OpenTHC\CRE\Controller\CropCollect;

class Single extends \OpenTHC\CRE\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		$dbc = $this->_container->DB;

		$sql = 'SELECT * FROM plant_collect WHERE license_id = :l AND id = :g';
		$arg = array(
			':l' => $_ENV['license_id'],
			':g' => $ARG['id']
		);

		$pc0 = $dbc->fetchRow($sql, $arg);
		if (empty($pc0['id'])) {
			return $this->send404('Crop Collect not found [CPS-027]');
		}

		// Collections
		$sql = 'SELECT * FROM plant_collect_plant WHERE plant_collect_id = :pc0 ORDER BY id';
		$arg = [ ':pc0' => $pc0['id'] ];
		$res = $dbc->fetchAll($sql, $arg);

		$pc0['collect_list'] = $res;

		return $RES->withJSON([
			'data' => $pc0,
			'meta' => [],
		]);

	}
}
