<?php
/**
 * Contact Routes
 */

namespace App\Module;

class Contact extends \OpenTHC\Module\Base
{
	function __invoke($a)
	{
		$a->get('', 'App\Controller\Contact\Search');
		$a->post('', 'App\Controller\Contact\Create');

		// $a->get('/type', 'App\Controller\Contact\Search:type');

		$a->get('/{id}', 'App\Controller\Contact\Single');
		$a->post('/{id}', 'App\Controller\Contact\Update');

		$a->delete('/{id}', 'App\Controller\Contact\Delete');

	}
}
