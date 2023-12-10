<?php
/**
 * Browse Main Page
 */

namespace OpenTHC\CRE\Controller\Browse;

class Main extends \OpenTHC\CRE\Controller\Base
{
	/**
	 *
	 */
	function __invoke($REQ, $RES, $ARG)
	{
		$html = $this->render('browse/main.php', $data);

		return $RES->write($html);
	}

	function _get_path()
	{
		$path = $_SERVER['REQUEST_URI'];
		$path = strtok($path, '?');
		$path = str_replace('/browse/', '', $path);

		if (preg_match('/^(company|license|contact|section|vehicle|crop|crop\/collect|inventory|inventory\/adjust|lab|b2b\/outgoing|b2b\/incoming|b2c)/', $path, $m)) {
			$path = $m[1];
			// $base = $m[1];
			// switch ($base) {
			// 	case 'crop':
			// 	case 'inventory':
			// 	case 'b2b':
			// 		// Next Case
			// 		var_dump($m);
			// 		break;
			// }
		}

		return $path;
	}

	function _get_table($path)
	{
		switch ($path) {
			case 'company':
			case 'license':
			case 'contact':
			case 'section':
			case 'product':
			case 'vehicle':
			case 'inventory':
				return $path;
				break;
			case 'crop': return 'plant';
			case 'crop/collect': return 'plant_collect';
			case 'inventory/adjust': return 'inventory_adjust';
			case 'product/type': return 'product_type';
			case 'lab': return 'lab_result';
			case 'b2b/outgoing':
			case 'b2b/incoming':
				return str_replace('/', '_', $path);
			case 'b2c':
				return 'b2c_sale';

		}

		throw new \Exception('Invalid Table [CBS-050]');

	}

}
