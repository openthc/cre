<?php
/**
 * Single Contact
 */

namespace App\Controller\Contact;

class Single extends \App\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		$sql = 'SELECT * FROM contact WHERE id = :g';
		$arg = array(':g' => $ARG['id']);
		$rec =$this->_container->DB->fetch_row($sql, $arg);

		if (empty($rec)) {
			return $this->send404($RES);
		}

		return $RES->withJSON(array(
			'meta' => [],
			'data' => json_decode($rec['meta'], true),
		));

	}
}
