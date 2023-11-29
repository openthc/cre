<?php
/**
 * Update Inventory
 */

namespace OpenTHC\CRE\Controller\Inventory;

class Update extends \OpenTHC\CRE\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		$dbc = $this->_container->DB;

		$sql = 'SELECT id, meta FROM inventory WHERE id = :g';
		$arg = array(
			':g' => $ARG['id'],
		);

		$lo0 = $dbc->fetch_row($sql, $arg);
		if (empty($lo0)) {
			return $this->send404('Inventory not found [CLU-020]');
		}

		// Old Object
		$obj0 = json_decode($lo0['meta'], true);
		if (empty($obj0)) {
			$obj0 = [];
		}

		// New Object
		$obj1 = array(
			'name' => $_POST['name'],
			'type' => $_POST['type'],
			'product' => $_POST['product'] ?: $_POST['type'],
		);

		$obj1 = array_merge($obj0, $obj1);
		$meta = json_encode($obj1);

		$sql = 'UPDATE inventory SET';
		$sql.= ' name = :n';
		$arg[':n'] = trim($_POST['name']);

		if (!empty($_POST['qty'])) {
				$sql.= ', qty = :q';
				$arg[':q'] = $_POST['qty'];
		}

		$sql.= ', hash = :h';
		$arg[':h'] = sha1($meta);

		$sql.= ', meta = :m';
		$arg[':m'] = $meta;

		$sql.= ' WHERE id = :g';
		$arg[':g'] = $ARG['id'];

		$dbc->query('BEGIN');

		// // Update the record
		// $sql = 'UPDATE inventory SET hash = :h, meta = :m WHERE id = :g';
		// $arg = array(
		// 	':g' => $ARG['id'],
		// 	':h' => sha1($meta),
		// 	':m' => $meta
		// );
		$dbc->query($sql, $arg);

		// Log the change
		$this->logAudit('Inventory/Update', $ARG['id'], $meta);

		$dbc->query('COMMIT');

		$lo1 = $dbc->fetchRow('SELECT * FROM inventory WHERE id = :pk', $lo0['id']);

		return $RES->withJSON([
			'meta' => [],
			'data' => $lo1,
		]);

	}
}
