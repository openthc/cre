<?php
/**
 * Update Crop
 */

namespace OpenTHC\CRE\Controller\Crop;

class Update extends \OpenTHC\CRE\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		$dbc = $this->dic->get('DB');

		$sql = 'SELECT id, meta FROM crop WHERE license_id = :l AND id = :g';
		$arg = array(
			':l' => $_SESSION['License']['id'],
			':g' => $ARG['id'],
		);

		$chk = $dbc->fetch_row($sql, $arg);
		if (empty($chk)) {
			return $this->send404('Crop not found [CPU-022]');
		}

		// Old Object
		$obj0 = json_decode($chk['meta'], true);

		// New Object
		$obj1 = array_merge($obj0, array(
			'name' => $_POST['name'],
			'type' => $_POST['type'],
		));

		$meta = json_encode($obj1);

		$dbc->query('BEGIN');

		// Update the record
		$sql = 'UPDATE crop SET hash = :h, meta = :m WHERE id = :g';
		$arg = array(
			':g' => $ARG['id'],
			':h' => sha1($meta),
			':m' => $meta
		);
		$this->dic->get('DB')->query($sql, $arg);

		// Log the change
		$this->logAudit('Crop/Update', $ARG['id'], $meta);

		$this->dic->get('DB')->query('COMMIT');

		return $RES->withJSON([
			'meta' => [],
			'data' => $obj1,
		]);

	}
}
