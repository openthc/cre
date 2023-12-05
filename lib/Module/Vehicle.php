<?php
/**
 * Vehicle Routes
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Module;

class Vehicle extends \OpenTHC\Module\Base
{
	function __invoke($a)
	{
		$a->get('', 'OpenTHC\CRE\Controller\Vehicle\Search');
		$a->post('', 'OpenTHC\CRE\Controller\Vehicle\Create');

		// $a->get('/type', 'OpenTHC\CRE\Controller\Vehicle\Search:type');

		$a->get('/status', 'OpenTHC\CRE\Controller\Vehicle\Status');

		$a->get('/{id}', 'OpenTHC\CRE\Controller\Vehicle\Single');
		$a->post('/{id}', 'OpenTHC\CRE\Controller\Vehicle\Update');

		$a->delete('/{id}', 'OpenTHC\CRE\Controller\Vehicle\Delete');

	}
}
