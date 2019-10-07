<?php
/**
 * Strain Routes
 */

namespace App\Module;

class Strain extends \OpenTHC\Module\Base
{
	function __invoke($a)
	{
		$a->get('', 'App\Controller\Strain\Search');
		$a->post('', 'App\Controller\Strain\Create');

		$a->get('/{id}', 'App\Controller\Strain\Single');
		$a->post('/{id}', 'App\Controller\Strain\Update');

		$a->delete('/{id}', 'App\Controller\Strain\Delete');

	}
}
