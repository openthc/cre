<?php
/**
 * Search
 */

namespace OpenTHC\CRE\Controller\Browse;

class Search extends \OpenTHC\CRE\Controller\Browse\Main
{
	/**
	 *
	 */
	function __invoke($REQ, $RES, $ARG)
	{
		$path = $this->_get_path();

		$data = [];
		$data['path'] = $path;
		$data['name'] = $this->_get_name($path);
		$data['table'] = $this->_get_table($path);

		$html = '';

		switch ($path) {
			case 'license/type':
				$html = $this->render('browse/license-type.php', []);
				break;
			default:
				$data['table'] = $this->_get_table($path);
				$html = $this->render('browse/search.php', $data);
				break;
		}

		return $RES->write($html);

	}



	function _search()
	{

	}

}
