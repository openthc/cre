<?php
/**
 * Create a Vehicle owned by a License
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Controller\Vehicle;

class Create extends \OpenTHC\CRE\Controller\Base
{
	use \OpenTHC\Traits\JSONValidator;

	/**
	 *
	 */
	function __invoke($REQ, $RES, $ARG)
	{
		$source_data = $_POST;
		$source_data = \Opis\JsonSchema\Helper::toJSON($source_data);
		if (empty($source_data->id)) {
			$source_data->id = \Edoceo\Radix\ULID::generate();
		}
		$schema_spec = \OpenTHC\CRE\Variety::getJSONSchema();
		$this->validateJSON($source_data, $schema_spec);

		// Check Vehicle Record
		$sql = 'SELECT id FROM vehicle WHERE license_id = :l AND name = :n';
		$arg = array(
			':l' => $_SESSION['License']['id'],
			':n' => $source_data->name,
		);
		$chk = $this->_container->DB->fetchRow($sql, $arg);
		if (!empty($chk)) {
			return $RES->withJSON([
				'meta' => [ 'note' => 'Vehicle Duplicate [CSC-030]' ],
				'data' => $chk,
			], 409);
		}

		// Vehicle Record
		$rec = array(
			'license_id' => $_SESSION['License']['id'],
			'id' => $source_data->id,
			'hash' => null,
			'name' => $source_data->name,
			'meta' => json_encode($source_data),
		);
		$rec['hash'] = sha1($rec['meta']);

		$this->_container->DB->query('BEGIN');
		$this->_container->DB->insert('vehicle', $rec);
		$this->logAudit('Vehicle/Create', $oid, $rec['meta']);
		$this->_container->DB->query('COMMIT');

		return $RES->withJSON([
			'meta' => [],
			'data' => $source_data,
		], 201);

	}
}
