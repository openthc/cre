<?php
/**
 * PlantCollect Route Handlers
 */

namespace App\Module;

class PlantCollect extends \OpenTHC\Module\Base
{
	function __invoke($a)
	{
		$a->get('/{id}', 'App\Controller\PlantCollect\Single');
		$a->post('/{id}/commit', 'App\Controller\PlantCollect\Commit');
	}
}
