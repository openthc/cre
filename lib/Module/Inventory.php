<?php
/**
 * Inventory Routes
 */

namespace App\Module;

class Inventory extends \OpenTHC\Module\Base
{
	function __invoke($a)
	{
		$a->get('', 'App\Controller\Inventory\Search');
		$a->post('', 'App\Controller\Inventory\Create');

		$a->get('/{id}', 'App\Controller\Inventory\Single');
		$a->patch('/{id}', 'App\Controller\Inventory\Update');
		// $a->post('/{id}', 'App\Controller\Inventory\Update');
		$a->post('/{id}/adjust', 'App\Controller\Inventory\Adjust');
		// $a->post('/{id}/split', 'App\Controller\Inventory\Split');
		// $a->post('/{id}/revert', 'App\Controller\Inventory\Revert');

		$a->delete('/{id}', 'App\Controller\Inventory\Delete');

	}
}
