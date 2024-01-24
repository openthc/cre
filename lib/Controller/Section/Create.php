<?php
/**
 * Create a Section owned by a License
 */

namespace OpenTHC\CRE\Controller\Section;

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

		$schema_spec = \OpenTHC\CRE\Section::getJSONSchema();
		$this->validateJSON($source_data, $schema_spec);

		$_POST['name'] = trim($_POST['name']);
		$_POST['type'] = trim($_POST['type']);

		if (empty($_POST['name'])) {
			return $RES->withJSON([
				'data' => [],
				'meta' => [
					'note' => 'Invalid Section Name [CSC-019]',
				]
			], 400);
		}

		if (empty($_POST['type'])) {
			$_POST['type'] = 'section';
		}

		// Section Object
		$obj = array(
			'id' => $source_data->id,
			'name' => $_POST['name'],
			'type' => $_POST['type'],
		);

		// Section Record
		$rec = array(
			'license_id' => $_SESSION['License']['id'],
			'id' => $source_data->id,
			'hash' => null,
			'name' => $obj['name'],
			'meta' => json_encode($obj),
		);
		$rec['hash'] = sha1($rec['meta']);

		$this->_container->DB->query('BEGIN');
		$this->_container->DB->insert('section', $rec);
		$this->logAudit('Section/Create', $rec['id'], $rec['meta']);
		$this->_container->DB->query('COMMIT');

		return $RES->withJSON(array(
			'data' => $obj,
			'meta' => [
				'note' => 'Section Created',
			]
		), 201);

	}
}
