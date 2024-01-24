<?php
/**
 * Create a Global Contact
 */

namespace OpenTHC\CRE\Controller\Contact;

class Create extends \OpenTHC\CRE\Controller\Base
{
	use \OpenTHC\Traits\JSONValidator;

	function __invoke($REQ, $RES, $ARG)
	{
		$C = array();

		if (empty($_POST['company'])) {
			return $this->sendError('Missing "company" parameter [CCC-017]', 400);
		}


		$sql = 'SELECT * FROM company WHERE id = :g';
		$arg = array(':g' => $_POST['company']);
		$rec = $this->_container->DB->fetchRow($sql, $arg);
		if (empty($rec['id'])) {
			return $this->sendError('Invalid "company" parameter [CCC-028]', 400);
		}

		$C = $rec;
		if (empty($C['id'])) {
			return $this->sendError('The company_id missing', 400);
		}

		$obj = array(
			'id' => $_POST['id'],
			'name' => $_POST['name'],
			// 'username' => $_POST['username']
		);
		if (empty($obj['id'])) {
			$obj['id'] = _ulid();
		}

		$rec = array(
			'company_id' => $C['id'],
			'id' => $obj['id'],
			'name' => $_POST['name'],
			// 'username' => $_POST['username'],
		);

		$rec['meta'] = json_encode($obj);
		$rec['hash'] = sha1($rec['meta']);

		try {
			$this->_container->DB->insert('contact', $rec);
			$this->logAudit('Contact/Create', $rec['id'], $rec['meta']);
		} catch (\Exception $e) {
			$this->logAudit('Contact/Create/Failure', $rec['id'], json_encode($e->getMessage()));
			return $this->sendError($e->getMessage(), 500);
		}

		return $RES->withJSON(array(
			'meta' => [],
			'data' => $obj
		), 201);

	}
}
