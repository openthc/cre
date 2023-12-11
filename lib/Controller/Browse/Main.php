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

	function _get_name($path)
	{
		$path = str_replace('/', ' ', $path);
		// $path = ucfirst($path);
		$path = ucwords($path);
		$path = str_replace('B2b', 'B2B', $path);
		$path = str_replace('B2c', 'B2C', $path);
		return $path;
	}


	function _get_path()
	{
		$path = $_SERVER['REQUEST_URI'];
		$path = strtok($path, '?');
		$path = str_replace('/browse/', '', $path);

		// Complex Names
		if (preg_match('/^(crop\/collect|inventory\/adjust|lab\/result|license\/type|product\/type)/', $path, $m)) {
			$path = $m[1];
			return $path;
		}

		if (preg_match('/^(contact|company|license|section|variety|product|vehicle|crop|inventory|b2b\/outgoing|b2b\/incoming|b2c)/', $path, $m)) {
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
			case 'contact':
			case 'company':
			case 'license':
			case 'section':
			case 'variety':
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
			case 'lab/result': return 'lab_result';
			case 'b2b/outgoing':
			case 'b2b/incoming':
				return str_replace('/', '_', $path);
			case 'b2c':
				return 'b2c_sale';

		}

		throw new \Exception('Invalid Table [CBS-050]');

	}

}
