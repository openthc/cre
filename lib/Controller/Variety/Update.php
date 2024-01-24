<?php
/**
 * Update Variety
 */

namespace OpenTHC\CRE\Controller\Variety;

class Update extends \OpenTHC\CRE\Controller\Base
{
	use \OpenTHC\Traits\JSONValidator;

	function __invoke($REQ, $RES, $ARG)
	{

		$sql = 'SELECT id, meta FROM variety WHERE license_id = :l AND id = :g';
		$arg = array(
			':l' => $_SESSION['License']['id'],
			':g' => $ARG['id'],
		);

		$chk = $this->_container->DB->fetch_row($sql, $arg);
		if (empty($chk)) {
			return $this->send404('Variety Not Found [CSU-023]');
		}

		// Old Object
		$obj0 = json_decode($chk['meta'], true);

		// New Object
		$obj1 = array(
			'id' => $ARG['id'],
			'name' => $_POST['name'],
			'type' => $_POST['type'],
		);

		$meta = json_encode($obj1);

		$this->_container->DB->query('BEGIN');

		// Update the record
		$sql = 'UPDATE variety SET name = :n0, hash = :h, meta = :m WHERE id = :g';
		$arg = array(
			':g' => $ARG['id'],
			':n0' => trim($_POST['name']),
			':h' => sha1($meta),
			':m' => $meta
		);
		$this->_container->DB->query($sql, $arg);

		// Log the change
		$this->logAudit('Variety/Update', $ARG['id'], json_encode($_POST));

		$this->_container->DB->query('COMMIT');

		return $RES->withJSON([
			'meta' => [],
			'data' => $obj1
		]);

	}
}
