<?php
/**
 * Zone Routes
 */

namespace App\Module;

class Zone extends \OpenTHC\Module\Base
{
	function __invoke($a)
	{
		$a->get('', 'App\Controller\Zone\Search');
		$a->post('', 'App\Controller\Zone\Create');

		$a->get('/{id}', 'App\Controller\Zone\Single');
		$a->post('/{id}', 'App\Controller\Zone\Update');

		$a->delete('/{id}', 'App\Controller\Zone\Delete');
	}
}
