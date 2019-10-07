<?php
/**
 * Product Routes
 */

namespace App\Module;

class Product extends \OpenTHC\Module\Base
{
	function __invoke($a)
	{
		$a->get('', 'App\Controller\Product\Search');
		$a->post('', 'App\Controller\Product\Create');

		$a->get('/type', 'App\Controller\Product\Type');
		// $a->post('/type', 'App\Controller\Product\Type\Create');
		// $a->delete('/type', 'App\Controller\Product\Type\Create');

		$a->get('/{id}', 'App\Controller\Product\Single');
		$a->post('/{id}', 'App\Controller\Product\Update');

		$a->delete('/{id}', 'App\Controller\Product\Delete');

	}
}
