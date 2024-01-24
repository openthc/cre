<?php
/**
 * Create a Global Company
 */

namespace OpenTHC\CRE\Controller\Company;

class Create extends \OpenTHC\CRE\Controller\Base
{
	use \OpenTHC\Traits\JSONValidator;

	/**
	 *
	 */
	function __invoke($REQ, $RES, $ARG)
	{
		$obj = array(
			'id' => $_POST['id'],
			'name' => $_POST['name'],
			'type' => $_POST['type'],
		);
		if (empty($obj['id'])) {
			$obj['id'] = _ulid();
		}

		$rec = array(
			'id' => $obj['id'],
			'name' => $_POST['name'],
		);

		if (!empty($_POST['meta'])) {
			$rec['meta'] = $_POST['meta'];
		}
		$rec['meta'] = json_encode($rec['meta']);
		$rec['hash'] = sha1($rec['meta']);

		try {
			$this->_container->DB->insert('company', $rec);
			$this->logAudit('Company/Create', $rec['id'], $rec);
		} catch (\Exception $e) {
			$this->logAudit('Company/Create/Error', $rec['id'], $e);
			return $RES->withJSON([ 'meta' => [ 'note' => $e->getMessage() ]], 500);
		}

		$link = sprintf('/company/%s', $rec['id']);
		$RES = $RES->withHeader('location', $link);

		return $RES->withJSON([
			'meta' => [
				'link' => $link
			],
			'data' => $obj,
		], 201);

	}
}
