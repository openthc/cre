<?php
/**
 * B2B Single
 */

namespace OpenTHC\CRE\Controller\B2B;

class Single extends \OpenTHC\CRE\Controller\Base
{
	/**
	 *
	 */
	function __invoke($REQ, $RES, $ARG)
	{
		$dbc = $this->_container->DB;

		$sql = 'SELECT * FROM b2b_outgoing WHERE license_id_source = :l AND id = :g';
		$arg = array(
			':l' => $_SESSION['License']['id'],
			':g' => $ARG['id']
		);
		$T = $dbc->fetchRow($sql, $arg);
		if (empty($T['id'])) {
			return $this->send404('B2B Not Found [CTS-020]');
		}

		// Items
		$sql = 'SELECT * FROM b2b_outgoing_item WHERE b2b_outgoing_id = ?';
		$arg = array($T['id']);
		$T['line_item_list'] = $dbc->fetchAll($sql, $arg);

		return $RES->withJSON([
			'meta' => [],
			'data' => $T,
		]);

	}
}
