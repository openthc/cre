<?php
/**
 * Accept B2B Sale
 */

namespace App\Controller\B2B;

class Accept extends \App\Controller\Base
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
					'detail' => 'B2B Sale Not Found',
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
					'detail' => 'Invalid B2B Sale State',
					'incoming' => $T_incoming['stat'],
					'outgoing' => $T_outgoing['stat'],
				],
			), 412);
		}


		// Verify Source Items
		$source_item = $dbc->fetchAll('SELECT * FROM b2b_outgoing_item WHERE b2b_outgoing_id = :t0 AND stat = 200', [
			':t0' => $T_outgoing['id']
		]);
		if (0 == count($source_item)) {
			// Fail
			return $RES->withJSON(array(
				'data' => null,
				'meta' => [
					'detail' => 'No Source Items',
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
					'detail' => 'No Target Items',
				],
			), 412);
		}

		// Seems Legit
		$dbc->query('BEGIN');

		$sql = 'UPDATE b2b_outgoing SET stat = 307 WHERE id = ?';
		$arg = array($T['id']);
		$dbc->query($sql, $arg);

		$sql = 'UPDATE b2b_incoming SET stat = 202 WHERE id = ?';
		$arg = array($T['id']);
		$dbc->query($sql, $arg);

		$T['stat'] = 307;
		$T['b2b_incoming_stat'] = 202;
		$T['b2b_outgoing_stat'] = 307;

		$Z = [];
		$Z['id'] = $_POST['section_id'];

		// Copy To b2b_incoming_item
		// Copy to lot for target license
		// Change Owner of the transferred Lots?
		// Or Assign New Landed Values?
		foreach ($b2b_outgoing_item_list as $b2b_outgoing_item) {

			$lot_source = $dbc->fetchRow('SELECT * FROM lot WHERE id = :l1', [ ':l1' => $b2b_outgoing_item['lot_id'] ]);

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

			$lot_target = [
				'id' => _ulid(),
				'license_id' => $_ENV['license_id'],
				'product_id' => $lot_source['product_id'], // These have to be immutable now
				'variety_id' => $lot_source['variety_id'], // These have to be immutable now
				'section_id' => $Z['id'],
				'qty' => $b2b_outgoing_item['qty'],
				'hash' => '-',
			];

			$b2b_incoming_item = [
				'id' => $b2b_outgoing_item['id'],
				'b2b_incoming_id' => $T['id'],
				'lot_id' => $lot_target['id'],
				'stat' => 200,
				'flag' => 0,
				'hash' => '-',
				'name' => $lot_source['name'],
				'qty' => $b2b_outgoing_item['qty'],
			];
			if (empty($b2b_incoming_item['name'])) {
				$b2b_incoming_item['name'] = '-unknown-';
			}

			$dbc->insert('lot', $lot_target);
			$dbc->insert('b2b_incoming_item', $b2b_incoming_item);

		}

		$dbc->query('COMMIT');

		$T1 = $dbc->fetchRow('SELECT * FROM b2b_incoming WHERE id = ?', $T_incoming['id']);
		$T1['item_list'] = $dbc->fetchAll('SELECT * FROM b2b_incoming_item WHERE b2b_incoming_id = ?', $T_incoming['id']);

		return $RES->withJSON(array(
			'data' => $T1,
			'meta' => [],
		), 201);

	}
}
