<?php
/**
 * B2C Routes
 */

namespace App\Module;

class B2C extends \OpenTHC\Module\Base
{
	function __invoke($a)
	{
		$a->get('', 'App\Controller\B2C\Search');
		$a->post('', 'App\Controller\B2C\Create');

		$a->get('/{id}', 'App\Controller\B2C\Single');
		$a->post('/{id}', 'App\Controller\B2C\Update');

		$a->delete('/{id}', 'App\Controller\B2C\Delete');

	}
}
