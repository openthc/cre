<?php
/**
 * Lab Routes
 */

namespace OpenTHC\CRE\Module;

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

		$a->get('/metric', 'OpenTHC\CRE\Controller\Lab\Metric');

		$a->get('/sample', 'OpenTHC\CRE\Controller\Lab\Search');
		$a->get('/result', 'OpenTHC\CRE\Controller\Lab\Search');

		// $a->post('', 'OpenTHC\CRE\Controller\Lab\Create');

		// $a->get('/{id}', 'OpenTHC\CRE\Controller\Lab\Single');
		// $a->patch('/{id}', 'OpenTHC\CRE\Controller\Lab\Update');
		// // $a->post('/{id}', 'OpenTHC\CRE\Controller\Lot\Update');
		// $a->post('/{id}/adjust', 'OpenTHC\CRE\Controller\Lab\Adjust');
		// // $a->post('/{id}/split', 'OpenTHC\CRE\Controller\Lot\Split');
		// // $a->post('/{id}/revert', 'OpenTHC\CRE\Controller\Lot\Revert');
		// $a->delete('/{id}', 'OpenTHC\CRE\Controller\Lab\Delete');

	}
}
