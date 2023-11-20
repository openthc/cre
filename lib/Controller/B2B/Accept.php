<?php
/**
 * Accept B2B Sale
 */

namespace OpenTHC\CRE\Controller\B2B;

class Accept extends \OpenTHC\CRE\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		$dbc = $this->_container->DB;

		// Incoming Record
		$sql = 'SELECT * FROM b2b_incoming WHERE license_id_target = :l0 AND id = :t0';
		$arg = array(
			':l0' => $_ENV['license_id'],
			':t0' => $ARG['id']
		);
		$T_incoming = $dbc->fetchRow($sql, $arg);

		// Outgoing Record
		$sql = 'SELECT * FROM b2b_outgoing WHERE license_id_target = :l0 AND id = :t0';
		$arg = array(
			':l0' => $_ENV['license_id'],
			':t0' => $ARG['id'],
		);
		$T_outgoing = $dbc->fetchRow($sql, $arg);

		// Got them Both?
		if (empty($T_incoming['id']) || empty($T_outgoing['id'])) {
			return $RES->withJSON(array(
				'data' => null,
				'meta' => [
					'note' => 'B2B Sale Not Found',
					'incoming' => $T_incoming['id'],
					'outgoing' => $T_outgoing['id'],
				],
			), 404);
		}

		// Both Active?
		if ((200 != $T_incoming['stat']) && (200 != $T_outgoing['stat'])) {
			return $RES->withJSON(array(
				'data' => null,
				'meta' => [
					'note' => 'Invalid B2B Sale State',
					'incoming' => $T_incoming['stat'],
					'outgoing' => $T_outgoing['stat'],
				],
			), 412);
		}

		// Valid other Attributes
		$key_list = [
			'license_id_source',
			'license_id_target',
			'stat',
			'flag',
		];
		// foreach ($key_list as $k) {
		// }
		// if ((200 != $T_incoming['stat']) && (200 != $T_outgoing['stat'])) {
		// 	return $RES->withJSON(array(
		// 		'meta' => [
		// 			'note' => 'Invalid Transfer State',
		// 			'incoming' => $T_incoming['stat'],
		// 			'outgoing' => $T_outgoing['stat'],
		// 		],
		// 		'data' => null
		// 	), 412);
		// }

		if (empty($_POST['section_id'])) {
			$sql = 'SELECT id FROM section WHERE license_id = :l0 ORDER BY id LIMIT 1';
			$arg = [
				':l0' => $_ENV['license_id'],
			];
			$_POST['section_id'] = $dbc->fetchOne($sql, $arg);
		}

		// I'm the Receiver?
		// if (licnese == license)

		// Verify Source Items
		$source_item = $dbc->fetchAll('SELECT * FROM b2b_outgoing_item WHERE b2b_outgoing_id = :t0 AND stat = 200', [
			':t0' => $T_outgoing['id']
		]);
		if (0 == count($source_item)) {
			// Fail
			return $RES->withJSON(array(
				'data' => null,
				'meta' => [
					'note' => 'No Source Items [CBA-086]',
				],
			), 412);
		}

		// Verify Target Items
		$target_item = $dbc->fetchAll('SELECT * FROM b2b_incoming_item WHERE b2b_incoming_id = :t0 AND stat = 200', [
			':t0' => $T_incoming['id']
		]);
		if (0 == count($target_item)) {
			// Fail
			return $RES->withJSON(array(
				'data' => $target_item,
				'meta' => [
					'note' => 'No Target Items [CBA-100]',
				],
			), 412);
		}

		// Seems Legit
		$dbc->query('BEGIN');

		// If qty_rx == qty_tx then stat = 307
		// update b2b_incoming_item  SET stat = 307, qty = $Q0
		// update b2b_outgoing_item SET stat = 307

		// if qty_rx != qty_tx then stat = 307
		// update b2b_incoming_item SET stat = 302, inventory_id = new Lot(), qty = $Q1
		// update b2b_outgoing_item SET stat = 307

		// Mark All Items as Fully Received
		foreach ($source_item as $src) {

			$lo0 = $dbc->fetchRow('SELECT * FROM inventory WHERE id = ?', $src['inventory_id']);
			$pr0 = $dbc->fetchRow('SELECT * FROM product WHERE id = ?', $lo0['product_id']);
			$vt0 = $dbc->fetchRow('SELECT * FROM variety WHERE id = ?', $lo0['variety_id']);
			// $sn0 = $dbc->fetchRow('SELECT * FROM section WHERE license_id = :l0 AND id = :s0', [ ':s0' => $_POST['section_id'] ]);

			// Copy Product Details
			// $pr1 = [ 'id' => _ulid() ];
			// $sql = 'INSERT INTO product (id, license_id, product_type_id, stat, flag, hash, name) VALUES (SELECT :pr1, :l0, product_type_id, stat, flag, hash, name FROM product WHERE id = :pr0)';
			// $arg = [
			// 	':l0' => $_ENV['license_id'],
			// 	':pr1' => $pr1['id'],
			// ];
			// $dbc->query($sql, $arg);

			// Copy Variety Details
			// $st1 = [ 'id' => _ulid() ];
			// $sql = 'INSERT INTO variety () VALUES (SELECT :st1, :l0, stat, flag, hash, name, meta FROM variety WHERE ';
			// $arg = [
			// 	':l0' => $_ENV['license_id'],
			// 	':pt0' => $pr0['id'],
			// ];
			// $dbc->query($sql, $arg);

			$lot1 = [];
			$lot1['id'] = _ulid();
			$lot1['license_id'] = $T_incoming['license_id_target'];
			$lot1['product_id'] = $pr0['id'];
			$lot1['variety_id'] = $vt0['id'];
			$lot1['section_id'] = $_POST['section_id'];
			$lot1['hash'] = '-';
			$lot1['qty'] = $src['qty'];
			$dbc->insert('inventory', $lot1);

			$sql = 'UPDATE b2b_incoming_item SET inventory_id = :l1, qty = :q0, stat = 307 WHERE id = :s0';
			$arg = [
				':s0' => $src['id'],
				':l1' => $lot1['id'],
				':q0' => $lot1['qty'],
			];
			$dbc->query($sql, $arg);

		}

		// Update Transfer Status
		$sql = 'UPDATE b2b_incoming SET stat = 307 WHERE id = ?';
		$arg = array($T_incoming['id']);
		$dbc->query($sql, $arg);

		$sql = 'UPDATE b2b_outgoing SET stat = 307 WHERE id = ?';
		$arg = array($T_outgoing['id']);
		$dbc->query($sql, $arg);

		$dbc->query('COMMIT');

		$T1 = $dbc->fetchRow('SELECT * FROM b2b_incoming WHERE id = ?', $T_incoming['id']);
		$T1['item_list'] = $dbc->fetchAll('SELECT * FROM b2b_incoming_item WHERE b2b_incoming_id = ?', $T_incoming['id']);

		return $RES->withJSON(array(
			'data' => $T1,
			'meta' => [],
		), 201);

	}
}
