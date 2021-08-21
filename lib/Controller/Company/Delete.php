<?php
/**
 * Delete a Company
 */

// acl_permit();
// acl_reject()

namespace App\Controller\Company;

class Delete extends \App\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		// If you're not root, deny
		// @todo replace with acl
		if ('018NY6XC00SERV1CE000000000' != $_SESSION['service_id']) {
			return $RES->withJSON([
				'data' => null,
				'meta' => [ 'detail' => 'Not Allowed [CCD-018]' ]
			], 403);
		}

		// Lookup
		$sql = 'SELECT * FROM company WHERE id = :g';
		$arg = array(':g' => $ARG['id']);
		$rec = $this->_container->DB->fetch_row($sql, $arg);

		if (empty($rec)) {
			return $this->send404('Company not found [CCD-030]');
		}

		// But Deny Anyway
		return $RES->withJSON([
			'data' => null,
			'meta' => [ 'detail' => 'Not Allowed [CCD-032]'],
		], 405);

	}
}
