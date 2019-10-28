<?php
/**
 * Transfer Routes
 */

namespace App\Module;

class Transfer extends \OpenTHC\Module\Base
{
	function __invoke($a)
	{
		// $a->get(''); // Show Active Incoming & Outgoing
		$a->post('', 'App\Controller\Transfer\Create');

		$a->get('/incoming', 'App\Controller\Transfer\Search');
		$a->get('/outgoing', 'App\Controller\Transfer\Search');

		$a->get('/{id}', 'App\Controller\Transfer\Single');

		$a->post('/{id}', 'App\Controller\Transfer\Update');
		$a->post('/{id}/accept', 'App\Controller\Transfer\Accept');

		// $a->get('/incoming/{id}', 'App\Controller\Transfer\Single');
		// $a->post('/incoming/{id}', 'App\Controller\Transfer\Update');
		//
		// $a->get('/outgoing/{id}', 'App\Controller\Transfer\Single');
		// $a->post('/outgoing/{id}', 'App\Controller\Transfer\Update');

		$a->delete('/outgoing/{id}', 'App\Controller\Transfer\Delete');

	}
}
