<?php
/**
 * Variety Routes
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Module;

class Variety extends \OpenTHC\Module\Base
{
	function __invoke($a)
	{
		$a->get('', 'OpenTHC\CRE\Controller\Variety\Search');
		$a->post('', 'OpenTHC\CRE\Controller\Variety\Create');

		// $a->get('/type', 'OpenTHC\CRE\Controller\Variety\Search:type');

		$a->get('/status', 'OpenTHC\CRE\Controller\Variety\Status');

		$a->get('/{id}', 'OpenTHC\CRE\Controller\Variety\Single');
		$a->post('/{id}', 'OpenTHC\CRE\Controller\Variety\Update');

		$a->delete('/{id}', 'OpenTHC\CRE\Controller\Variety\Delete');

	}
}
