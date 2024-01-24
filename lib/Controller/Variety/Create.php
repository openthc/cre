<?php
/**
 * Create a Variety owned by a License
 */

namespace OpenTHC\CRE\Controller\Variety;

class Create extends \OpenTHC\CRE\Controller\Base
{
	/**
	 *
	 */
	function __invoke($REQ, $RES, $ARG)
	{
		$source_data = $_POST;
		$source_data = \Opis\JsonSchema\Helper::toJSON($source_data);
		$schema_spec = \OpenTHC\CRE\Variety::getJSONSchema();
		$this->validateJSON($source_data, $schema_spec);

		$oid = \Edoceo\Radix\ULID::generate();

		// Variety Object
		$obj = array(
			'id' => $oid,
			'name' => $_POST['name'],
			'type' => $_POST['type'],
		);

		// Check Variety Record
		$sql = 'SELECT id FROM variety WHERE license_id = :l AND name = :n';
		$arg = array(
			':l' => $_SESSION['License']['id'],
			':n' => $_POST['name'],
		);
		$chk = $this->_container->DB->fetchRow($sql, $arg);
		if (!empty($chk)) {
			return $RES->withJSON([
				'meta' => [ 'note' => 'Variety Duplicate [CSC-030]' ],
				'data' => $chk,
			], 409);
		}

		// Variety Record
		$rec = array(
			'license_id' => $_SESSION['License']['id'],
			'id' => $oid,
			'hash' => null,
			'name' => $obj['name'],
			'meta' => json_encode($obj),
		);
		$rec['hash'] = sha1($rec['meta']);

		$this->_container->DB->query('BEGIN');
		$this->_container->DB->insert('variety', $rec);
		$this->logAudit('Variety/Create', $oid, $rec['meta']);
		$this->_container->DB->query('COMMIT');

		return $RES->withJSON([
			'meta' => [],
			'data' => $obj,
		], 201);

	}
}
