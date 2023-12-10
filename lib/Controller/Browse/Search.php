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
		$data['table'] = $this->_get_table($path);

		$html = $this->render('browse/search.php', $data);

		return $RES->write($html);

	}



	function _search()
	{

	}

}
