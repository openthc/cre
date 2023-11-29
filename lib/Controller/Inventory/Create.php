<?php
/**
 * Create a Inventory owned by a License
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Controller\Inventory;

class Create extends \OpenTHC\CRE\Controller\Base
{
	/**
	 *
	 */
	function __invoke($REQ, $RES, $ARG)
	{
		$this->_promote_post_vars();

		// if (!is_array($_POST['output'])) {
		// 	return $RES->withJSON([
		// 		'meta' => [ 'note' => 'Invalid Parameter for "output" [CLC-016]' ],
		// 		'data' => $_POST,
		// 	], 400);
		// }

		if (!isset($_POST['qty'])) {
			return $RES->withJSON([
				'meta' => [ 'note' => 'Invalid Parameter for "output.qty" [CLC-023]' ],
				'data' => $_POST,
			], 400);
		}

		$_POST['qty'] = floatval($_POST['qty']);
		if ($_POST['qty'] <= 0) {
			return $RES->withJSON([
				'meta' => [ 'note' => 'Invalid Parameter for "output.qty" [CLC-031]' ],
				'data' => $_POST,
			], 400);
		}

		// Create
		if (empty($_POST['source'])) {
			return $this->create_from_scratch($RES);
		}

		// Promote Single Value to Single Element Array
		if (is_string($_POST['source'])) {
			$x = $_POST['source'];
			$_POST['source'] = [];
			$_POST['source'][] = [
				'id' => $x,
			];
		}
		$source_c = count($_POST['source']);

		$output_t = null;
		if (!empty($_POST['product_id'])) {
			$output_t = $_POST['product_id'];
		}

		if (($source_c == 1) && empty($output_t)) {
			return $this->create_from_parent($RES);
		}

		if (($source_c >= 1) && !empty($output_t)) {
			return $this->create_from_convert($RES);
		}

		return $RES->withJSON([
			'meta' => [ 'note' => 'Invalid Parameters [CLC-072]' ],
			'data' => $_POST,
		], 400);

	}

	/**
	 *
	 */
	private function create_from_convert($RES)
	{
		$response_meta = [
			'warn' => array(),
		];
		$dbc = $this->_container->DB;

		// @todo Multi inventory conversions /mbw
		$lot_list = array();
		$meta_table = array();
		foreach ($_POST['source'] as $index => $source) {
			// @todo because we're updating `qty` below, we might want to use FOR UPDATE here, or maybe execute all updates in 1 xaction? /mbw
			$lot = $dbc->fetchRow('SELECT id, product_id, variety_id, section_id, meta, qty FROM inventory WHERE license_id = :l0 AND id = :pk', [
				':l0' => $_SESSION['License']['id'],
				':pk' => $source['id'],
				// ':pk' => $_POST['source'][0]['id'],
			]);

			$meta0 = [
				'convert' => [
					'children' => [
						// generated after $l1 is inserted.
						// [
						// 	'id' => '',
						// 	'qty' => '',
						// ],
					],
				]
			];
			$meta0 = array_merge_recursive((json_decode($lot['meta'], true) ?: []), $meta0);
			$meta_table[$lot['id']] = $meta0;

			$lot['qty_used'] = $source['qty'];
			$lot_list[] = $lot;
		}

		// Validate Variety
		$S = [];
		$sql = "SELECT * FROM variety WHERE license_id = :l0 AND id = :pk";
		$arg = [
			':l0' => $_SESSION['License']['id'],
			':pk' => $_POST['variety']['id'],
		];
		$S = $dbc->fetchRow($sql, $arg);
		if (empty($S['id'])) {
			$arg[':pk'] = $lot_list[0]['variety_id'];
			$S = $dbc->fetchRow($sql, $arg);
		}
		if (empty($S['id'])) {
			return $RES->withJSON([
				'meta' => [ 'note' => 'Invalid Variety given [CLC-123]' ],
				'data' => [
					'inventory_list' => $lot_list,
					// '_POST' => $_POST,
					// 'arg' => $arg,
				]
			], 400);
		}


		// Validate Section
		$Z = [];
		$sql = 'SELECT * FROM section WHERE license_id = :l0 AND id = :pk';
		if (!empty($_POST['section']['id'])) {
			$arg = [
				':l0' => $_SESSION['License']['id'],
				':pk' => $_POST['section']['id'],
			];
			$Z = $dbc->fetchRow($sql, $arg);
		}
		if (empty($Z['id'])) {
			$arg = [
				':l0' => $_SESSION['License']['id'],
				':pk' => $lot_list[0]['section_id'],
			];
			$Z = $dbc->fetchRow($sql, $arg);
		}
		if (empty($Z['id'])) {
			return $RES->withJSON([
				'meta' => [ 'note' => 'Invalid Section [CLC-137]' ],
				'data' => $_POST,
			], 400);
		}

		// Create new Inventory object
		$l1 = [
			'id' => _ulid(),
			'license_id' => $_SESSION['License']['id'],
			'product_id' => $obj['product_id'],
			'variety_id' => $S['id'],
			'section_id' => $Z['id'],
			// 'qty' => $_POST['qty'], // _POST['qty'] is the output qty, so we need to know the qty of the lot piece used
			'qty' => $_POST['qty'],
			'hash' => '-',
		];
		$meta1 = [
			'convert' => [
				'parents' => array_map(function($source) {
					return [
						'id' => $source['id'],
						'qty' => $source['qty_used'],
					];
				}, $lot_list),
			],
		];

		$l1['meta'] = json_encode($meta1);

		$dbc->query('BEGIN');

		$dbc->insert('inventory', $l1);

		foreach ($lot_list as $source_lot) {
			$meta0 = $meta_table[$source_lot['id']];
			$meta0['convert']['children'][] = [
				'id' => $l1['id'],
				'qty' => $l1['qty'],
			];

			$sql = 'UPDATE inventory SET meta = :meta, qty = :qty WHERE license_id = :l0 AND id = :pk';
			$res = $dbc->query($sql, [
				':pk' => $source_lot['id'],
				':l0' => $_SESSION['License']['id'],
				':qty' => floatval($source_lot['qty'] - $source_lot['qty_used']),
				':meta' => json_encode($meta0),
			]);
		}
		$dbc->query('COMMIT');

		unset($l1['meta']);
		return $RES->withJSON([
			'meta' => [ 'note' => 'Inventory Conversion Created' ],
			'data' => $l1,
		], 201);

	}

	/**
	 *
	 */
	private function create_from_parent($RES)
	{
		$l1 = [
			'id' => _ulid(),
			'license_id' => $_SESSION['License']['id'],
			'product_id' => $_POST['product']['id'],
			'variety_id' => $_POST['variety']['id'],
			'section_id' => '018NY6XC00SECT10N000000000',
			'qty' => $_POST['qty'],
			'hash' => '-',
		];

		$dbc = $this->_container->DB;
		$dbc->query('BEGIN');
		$dbc->insert('inventory', $l1);
		$dbc->query('COMMIT');

		return $RES->withJSON([
			'meta' => [ 'note' => 'Inventory Conversion Created'],
			'data' => $l1,
		], 201);

	}

	/**
	 *
	 */
	private function create_from_scratch($RES)
	{

		$DBC = $this->_container->DB;

		// Check for existance
		$sql = 'SELECT id FROM inventory WHERE license_id = :l AND id = :id';
		$arg = array(
			':l' => $_SESSION['License']['id'],
			':id' => $_POST['id'],
		);
		$chk = $DBC->fetchRow($sql, $arg);
		if (!empty($chk['id'])) {
			return $RES->withStatus(409);
		}

		// Create Inventory Object
		$obj = array(
			'id' => $_POST['id'],
			'product' => $_POST['product'],
			'variety' => $_POST['variety'],
			'section' => $_POST['section'],
			'name' => $_POST['name'],
			'type' => $_POST['type'],
		);
		if (empty($obj['id'])) {
			$obj['id'] = \Edoceo\Radix\ULID::generate();
		}

		// Lookup Product
		$sql = 'SELECT id FROM product WHERE license_id = :l AND id = :g';
		$arg = array($_SESSION['License']['id'], $obj['product']['id']);
		$P = $DBC->fetchRow($sql, $arg);
		if (empty($P['id'])) {
			// throw new \Exception('Product Not Found');
			// $P['id'] = $obj['product'];
			$P = [];
			$P['id'] = _ulid();
			$P['license_id'] = $_SESSION['License']['id'];
			$P['product_type_id'] = '';
			$P['name'] = '-';
			$P['hash'] = '-';
			$P['stat'] = 100;
			$DBC->insert('product', $P);
		}

		// Lookup Variety
		$sql = 'SELECT id FROM variety WHERE license_id = :l AND id = :g';
		$arg = array($_SESSION['License']['id'], $obj['variety']['id']);
		$S = $DBC->fetchRow($sql, $arg);
		if (empty($S['id'])) {
			// Variety Not Found
			// throw new \Exception('Variety Not Found');
			$S['id'] = $DBC->insert('variety', array(
				'id' => $obj['variety']['id'],
				'license_id' => $_SESSION['License']['id'],
				'name' => '-',
				'hash' => md5(json_encode($obj['variety'])),
			));
		}

		// Lookup Section
		$Z = [];
		if (!empty($obj['section']['id'])) {
			// @todo make this a configurable thing?
			// It's already a hard-coded value in other files
			if ('019KAGVX9MYQCNKPGWMCA49EGW' != $obj['section']['id']) {
				$sql = 'SELECT id FROM section WHERE license_id = :l AND id = :g';
				$arg = array($_SESSION['License']['id'], $obj['section']['id']);
				$Z = $DBC->fetchRow($sql, $arg);
				if (empty($Z['id'])) {
					// Section Not Found
					//throw new \Exception('Section Not Found');
					$Z['id'] = $DBC->insert('section', array(
						'id' => $obj['section']['id'],
						'license_id' => $_SESSION['License']['id'],
						'name' => '-', // $obj['section']['name'],
						'hash' => md5(json_encode($obj['section'])),
					));
				}
			}
		}

		// Create Inventory Record
		$rec = array(
			'id' => $obj['id'],
			'license_id' => $_SESSION['License']['id'],
			'product_id' => $P['id'],
			'variety_id' => $S['id'],
			'section_id' => $Z['id'],
			// 'name' => '-',
			'qty' => floatval($_POST['qty']),
		);

		if (empty($rec['product_id'])) {
			$rec['product_id'] = '019KAGVX9MYDYS8M2FNABNKGV1';
		}

		if (empty($rec['variety_id'])) {
			$rec['variety_id'] = '019KAGVX9MK1NZWTN7D14F09FC';
		}

		if (empty($rec['section_id'])) {
			$rec['section_id'] = '019KAGVX9MYQCNKPGWMCA49EGW';
		}

		$rec['meta'] = json_encode($obj);
		$rec['hash'] = sha1($rec['meta']);

		// $DBC->query('BEGIN');
		$this->logAudit('Inventory/Create', $rec['id'], $rec['meta']);
		$DBC->insert('inventory', $rec);
		// $DBC->query('COMMIT');

		return $RES->withJSON([
			'meta' => [],
			'data' => $obj,
		], 201);

	}

	/**
	 * [_promote_post_vars description]
	 * @return [type] [description]
	 */
	private function _promote_post_vars()
	{
		// Promote Up these Vars
		if (is_string($_POST['product'])) {
			$_POST['product'] = array(
				'id' => $_POST['product'],
			);
		}

		if (is_string($_POST['variety'])) {
			$_POST['variety'] = array(
				'id' => $_POST['variety'],
			);
		}

		if (is_string($_POST['section'])) {
			$_POST['section'] = array(
				'id' => $_POST['section'],
			);
		}

	}
}
