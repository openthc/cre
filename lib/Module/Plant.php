<?php
/**
 * Plant Route Handlers
 */

namespace App\Module;

class Plant extends \OpenTHC\Module\Base
{
	function __invoke($a)
	{
		$a->get('', 'App\Controller\Plant\Search');
		$a->post('', 'App\Controller\Plant\Create');

		$a->get('/{id}', 'App\Controller\Plant\Single');
		$a->post('/{id}', 'App\Controller\Plant\Update');

		$a->delete('/{id}', 'App\Controller\Plant\Delete');

		$a->post('/{id}/collect', 'App\Controller\Plant\Collect');
		$a->post('/{id}/finish', 'App\Controller\Plant\Commit');

	}
}
