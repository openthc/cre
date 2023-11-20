<?php
/**
 * Crop Route Handlers
 */

namespace OpenTHC\CRE\Module;

class Crop extends \OpenTHC\Module\Base
{
	function __invoke($a)
	{
		$a->get('', 'OpenTHC\CRE\Controller\Crop\Search');
		$a->post('', 'OpenTHC\CRE\Controller\Crop\Create');

		$a->get('/{id}', 'OpenTHC\CRE\Controller\Crop\Single');
		$a->post('/{id}', 'OpenTHC\CRE\Controller\Crop\Update');

		$a->delete('/{id}', 'OpenTHC\CRE\Controller\Crop\Delete');

		$a->post('/{id}/collect', 'OpenTHC\CRE\Controller\Crop\Collect');
		$a->post('/{id}/finish', 'OpenTHC\CRE\Controller\Crop\Commit');

	}
}
