<?php
/**
 * Lot Routes
 */

namespace App\Module;

class Lot extends \OpenTHC\Module\Base
{
	function __invoke($a)
	{
		$a->get('', 'App\Controller\Lot\Search');
		$a->post('', 'App\Controller\Lot\Create');

		$a->get('/{id}', 'App\Controller\Lot\Single');
		$a->patch('/{id}', 'App\Controller\Lot\Update');
		// $a->post('/{id}', 'App\Controller\Lot\Update');
		$a->post('/{id}/adjust', 'App\Controller\Lot\Adjust');
		// $a->post('/{id}/split', 'App\Controller\Lot\Split');
		// $a->post('/{id}/revert', 'App\Controller\Lot\Revert');

		$a->delete('/{id}', 'App\Controller\Lot\Delete');

	}
}
