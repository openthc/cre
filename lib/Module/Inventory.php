<?php
/**
 * Inventory Routes
 */

namespace OpenTHC\CRE\Module;

class Inventory extends \OpenTHC\Module\Base
{
	function __invoke($a)
	{
		$a->get('', 'OpenTHC\CRE\Controller\Inventory\Search');
		$a->post('', 'OpenTHC\CRE\Controller\Inventory\Create');

		$a->get('/{id}', 'OpenTHC\CRE\Controller\Inventory\Single');
		$a->patch('/{id}', 'OpenTHC\CRE\Controller\Inventory\Update');
		// $a->post('/{id}', 'OpenTHC\CRE\Controller\Inventory\Update');
		$a->post('/{id}/adjust', 'OpenTHC\CRE\Controller\Inventory\Adjust');
		// $a->post('/{id}/split', 'OpenTHC\CRE\Controller\Inventory\Split');
		// $a->post('/{id}/revert', 'OpenTHC\CRE\Controller\Inventory\Revert');

		$a->delete('/{id}', 'OpenTHC\CRE\Controller\Inventory\Delete');

	}
}
