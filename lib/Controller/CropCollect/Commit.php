<?php
/**
 * Commit CropCollect
 */

namespace OpenTHC\CRE\Controller\CropCollect;

class Commit extends \OpenTHC\CRE\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		// Make sure necessary keys are set
		$key_list = [ 'variety_id', 'qty' ];
		foreach ($key_list as $k) {
			if (!isset($_POST[$k])) {
				return $RES->withJSON([
					'data' => null,
					'meta' => [ 'detail' => 'Commit Requires Variety [PCC-018]' ],
				], 400);
			}
		}

		$net = floatval($_POST['qty']);
		$net = max($net, 0);

		if (empty($_POST['variety_id'])) {
			return $RES->withJSON([
				'data' => null,
				'meta' => [ 'detail' => 'Commit Requires Variety [PCC-029]' ],
			], 400);
		}

		$dbc = $this->_container->DB;

		// Find Default Product
		if (empty($_POST['product_id'])) {
			$sql = 'SELECT id FROM product WHERE license_id = :l0 AND product_type_id = :pt0 ORDER BY id LIMIT 1';
			$arg = [
				':l0' => $_ENV['license_id'],
				':pt0' => '019KAGVSC01MVH9QAZ75KEPY4D',
			];
			$_POST['product_id'] = $dbc->fetchOne($sql, $arg);
		}

		// Check Product, Auto-Create?
		if (!empty($_POST['product_id'])) {
			$sql = 'SELECT id FROM product WHERE license_id = :l0 AND id = :p1';
			$arg = [
				':l0' => $_ENV['license_id'],
				':p1' => $_POST['product_id'],
			];
			$chk = $dbc->fetchOne($sql, $arg);
			if (empty($chk)) {
				$dbc->insert('product', [
					'id' => $_POST['product_id'],
					'license_id' => $_ENV['license_id'],
					'product_type_id' => '019KAGVSC01MVH9QAZ75KEPY4D',
					'name' => '-unknown-',
					'hash' => '-',
				]);
			}
		}

		// Default Section
		if (empty($_POST['section_id'])) {
			$sql = 'SELECT id FROM section WHERE license_id = :l0 ORDER BY id LIMIT 1';
			$arg = [
				':l0' => $_ENV['license_id'],
			];
			$_POST['section_id'] = $dbc->fetchOne($sql, $arg);
		}


		$dbc->query('BEGIN');

		$sql = 'SELECT * FROM plant_collect WHERE license_id = :l AND id = :g FOR UPDATE';
		$arg = array(
			':l' => $_ENV['license_id'],
			':g' => $ARG['id']
		);

		$pc = $dbc->fetchRow($sql, $arg);
		if (empty($pc['id'])) {
			return $this->send404('Crop Collect not found [PCC-024]');
		}
		if (200 != $pc['stat']) {
			return $RES->withJSON([
				'data' => $pc,
				'meta' => [ 'detail' => 'Collect Not Open [PCC-028]' ],
			], 409);
		}

		// Sum Raw
		$sql = 'SELECT sum(qty) FROM plant_collect_plant WHERE plant_collect_id = :pc0 AND type = :pt0';
		$arg = [
			':pc0' => $pc['id'],
			':pt0' => 'raw',
		];
		$pc['raw'] = $dbc->fetchOne($sql, $arg);

		// Sum Net
		// $sql = 'SELECT sum(qty) FROM plant_collect_plant WHERE plant_collect_id = :pc0 AND type = :pt0';
		// $arg = [
		// 	':pc0' => $pc['id'],
		// 	':pt0' => 'net',
		// ];
		// $pc['net'] = $dbc->fetchOne($sql, $arg);

		// Check
		// if (($pc['net'] + $net) > $pc['raw']) {
		if (($pc['net'] + $net) > $pc['raw']) {
			return $RES->withJSON([
				'data' => $pc,
				'meta' => [ 'detail' => 'Collect Net Too Large [PCC-042]' ],
			], 413);
		}

		// Add this Net Collect Weight
		// Theory of Per-Plant Line Item for Net
		// if ($net > 0) {
		// 	$plant_list = [];
		// 	if (!empty($_POST['plant_list'])) {
		// 		// Use It
		// 	} else {
		// 		$sql = 'SELECT DISTINCT plant_id FROM plant_collect_plant WHERE plant_collect_id = :pc0 AND type = :pt0';
		// 		$arg = [
		// 			':pc0' => $pc['id'],
		// 			':pt0' => 'raw',
		// 		];
		// 		$res = $dbc->fetchAll($sql, $arg);
		// 		foreach ($res as $rec) {
		// 			$plant_list[] = $rec['plant_id'];
		// 		}
		// 		$net_pct = $net / count($plant_list);
		// 		foreach ($plant_list as $pid) {
		// 			$PCP = [
		// 				'id' => _ulid(),
		// 				'plant_collect_id' => $pc['id'],
		// 				'plant_id' => $pid,
		// 				'hash' => '-',
		// 				'type' => 'net',
		// 				'qty' => $net_pct,
		// 				'uom' => 'g', //  $_POST['uom'],
		// 			];
		// 			$dbc->insert('plant_collect_plant', $PCP);
		// 		}
		// 	}
		// }

		// Create the Inventory
		$lot = [
			'id' => _ulid(),
			'license_id' => $_ENV['license_id'],
			'product_id' => $_POST['product_id'],
			'variety_id' => $_POST['variety_id'],
			'section_id' => $_POST['section_id'],
			'qty' => $net,
			'hash' => '-',
		];
		$dbc->insert('inventory', $lot);

		// Update Crop Collect Record
		$pc['inventory_id'] = $lot['id'];
		$pc['stat'] = 301;
		$pc['net'] = $pc['net'] + $net;

		$sql = 'UPDATE plant_collect SET stat = :s0, raw = :r0, net = :n0 WHERE id = :pc0';
		$arg = [
			':s0' => $pc['stat'],
			':r0' => $pc['raw'],
			':n0' => $pc['net'],
			':pc0' => $pc['id'],
		];
		$dbc->query($sql, $arg);

		// Relationship
		$dbc->insert('inventory_family', [
			'id' => _ulid(),
			'inventory_id' => $lot['id'],
			'plant_collect_id' => $pc['id'],
		]);

		$dbc->query('COMMIT');

		return $RES->withJSON([
			'data' => [
				'plant_collect' => $pc,
				'inventory' => $lot,
			],
			'meta' => [
				'note' => 'Inventory Created, Collect Group Closed',
			],
		], 201);

	}
}
