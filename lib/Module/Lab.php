<?php
/**
 * Lab Routes
 */

namespace App\Module;

class Lab extends \OpenTHC\Module\Base
{
	function __invoke($a)
	{
		$a->get('', function($REQ, $RES, $ARG) {
			return $RES->withJSON(array(
				'meta' => [],
				'data' => []
			), 200);
		});

		$a->get('/metric', 'App\Controller\Lab\Metric');

		$a->get('/sample', 'App\Controller\Lab\Search');
		$a->get('/result', 'App\Controller\Lab\Search');

		// $a->post('', 'App\Controller\Lab\Create');

		// $a->get('/{id}', 'App\Controller\Lab\Single');
		// $a->patch('/{id}', 'App\Controller\Lab\Update');
		// // $a->post('/{id}', 'App\Controller\Lot\Update');
		// $a->post('/{id}/adjust', 'App\Controller\Lab\Adjust');
		// // $a->post('/{id}/split', 'App\Controller\Lot\Split');
		// // $a->post('/{id}/revert', 'App\Controller\Lot\Revert');
		// $a->delete('/{id}', 'App\Controller\Lab\Delete');

	}
}
