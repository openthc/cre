<?php
/**
 * Crop Route Handlers
 */

namespace App\Module;

class Crop extends \OpenTHC\Module\Base
{
	function __invoke($a)
	{
		$a->get('', 'App\Controller\Crop\Search');
		$a->post('', 'App\Controller\Crop\Create');

		$a->get('/{id}', 'App\Controller\Crop\Single');
		$a->post('/{id}', 'App\Controller\Crop\Update');

		$a->delete('/{id}', 'App\Controller\Crop\Delete');

		$a->post('/{id}/collect', 'App\Controller\Crop\Collect');
		$a->post('/{id}/finish', 'App\Controller\Crop\Commit');

	}
}
