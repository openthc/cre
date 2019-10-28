<?php
/**
 * Accept a Transfer
 */

namespace App\Controller\Transfer;

class Accept extends \App\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		$dbc = $this->_container->DB;

		// Find in View
		$sql = 'SELECT * FROM b2b_incoming WHERE target_license_id = :l0 AND id = :t0';
		$arg = array(
			':l0' => $_ENV['license_id'],
			':t0' => $ARG['id']
		);
		$T = $dbc->fetchRow($sql, $arg);
		if (empty($T['id'])) {
			return $this->send404('Transfer Not Found [CTS#020]');
		}
		if (200 != $T['stat']) {
			return $RES->withJSON([
				'meta' => [ 'detail' => 'Invalid Transfer State [CTA#028]' ],
				'data' => $T,
			], 409);
		}

		// Copy Items
		$arg = [
			':t0' => $T['id'],
		];
		$b2b_outgoing_item_list = $dbc->fetchAll('SELECT * FROM b2b_outgoing_item WHERE b2b_outgoing_id = :t0', $arg);

		// $sql = 'SELECT count(id) FROM b2b_outgoing_item WHERE b2b_outgoing_id = ?';
		// $arg = array($T['id']);
		// $chk0 = $dbc->fetchOne($sql, $arg);
		// if (empty($chk0)) {
		// 	$dbc->query('DELETE FROM b2b_outgoing WHERE id = ?', [ $T['id'] ]);
		// 	$dbc->query('DELETE FROM b2b_incoming WHERE id = ?', [ $T['id'] ]);
		// 	return $this->sendError('Transfer has no items [CTU#125]');
		// }

		// Allowed
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
		$Z['id'] = $_POST['zone_id'];

		// Copy To b2b_incoming_item
		// Copy to lot for target license
		// Change Owner of the Transferred Lots?
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

			// Copy Strain Details
			// $st1 = [ 'id' => _ulid() ];
			// $sql = 'INSERT INTO strain () VALUES (SELECT :st1, :l0, stat, flag, hash, name, meta FROM strain WHERE ';
			// $arg = [
			// 	':l0' => $_ENV['license_id'],
			// 	':pt0' => $pr0['id'],
			// ];
			// $dbc->query($sql, $arg);

			$lot_target = [
				'id' => _ulid(),
				'license_id' => $_ENV['license_id'],
				'product_id' => $lot_source['product_id'], // These have to be immutable now
				'strain_id' => $lot_source['strain_id'], // These have to be immutable now
				'zone_id' => $Z['id'],
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

		return $RES->withJSON([
			'meta' => [ 'detail' => 'Nothing '],
			'data' => [
				'transfer' => $T,
				'transfer_item' => $b2b_outgoing_item_list,
			]
		], 200);

	}
}
