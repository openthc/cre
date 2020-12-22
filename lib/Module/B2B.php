<?php
/**
 * B2B Routes
 */

namespace App\Module;

class B2B extends \OpenTHC\Module\Base
{
	function __invoke($a)
	{
		// $a->get(''); // Show Active Incoming & Outgoing
		$a->post('', 'App\Controller\B2B\Create');

		$a->get('/incoming', 'App\Controller\B2B\Search:incoming');
		$a->get('/outgoing', 'App\Controller\B2B\Search:outgoing');

		$a->get('/{id}', 'App\Controller\B2B\Single');

		$a->post('/{id}', 'App\Controller\B2B\Update');
		$a->post('/{id}/accept', 'App\Controller\B2B\Accept');
		// $a->post('/{id}/commit', 'App\Controller\B2B\Commit');
		$a->delete('/{id}', 'App\Controller\B2B\Delete');

		// $a->get('/incoming/{id}', 'App\Controller\B2B\Single');
		// $a->post('/incoming/{id}', 'App\Controller\B2B\Update');

		// $a->get('/outgoing/{id}', 'App\Controller\B2B\Single');
		// $a->post('/outgoing/{id}', 'App\Controller\B2B\Update');

		$a->delete('/outgoing/{id}', 'App\Controller\B2B\Delete');

	}
}
