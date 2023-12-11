<?php
/**
 * Update
 */

namespace OpenTHC\CRE\Controller\Browse;

class Update extends \OpenTHC\CRE\Controller\Browse\Main
{
	/**
	 *
	 */
	function __invoke($REQ, $RES, $ARG)
	{
		header('content-type: text/plain');

		var_dump($_POST);

		$path = $this->_get_path();

		$data = [];
		$data['path'] = $path;
		$data['table'] = $this->_get_table($path);
		$data['object_id'] = $ARG['id'];

		//if ( ! _acl($sub, $obj, $act) ) {
		//}

		switch ($data['table']) {
			case 'contact':
			case 'company':
			case 'license':
			case 'crop':
			case 'plant':
			case 'inventory':

				// $sql = <<<SQL
				// SELECT *
				// FROM %s
				// WHERE id = :o1
				// SQL;

				// $sql = sprintf($sql, $data['table']);
				// var_dump($sql);

				$arg[':o1'] = $data['object_id'];
				// var_dump($arg);

				$dbc = _dbc();
				$dbc->query('BEGIN');
				// $res = $dbc->fetchRow($sql, $arg);
				$sql = sprintf('UPDATE %s SET stat = 451 WHERE id = :o1', $data['table']);
				$dbc->query($sql, $arg);
				$dbc->query('COMMIT');

				return $RES->withRedirect(sprintf('/browse/%s/%s', $data['path'], $data['object_id']));


		}

		__exit_text('Invalid Request [CBU-061]', 400);
	}

}
