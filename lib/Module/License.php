<?php
/**
 * License Routes
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Module;

class License extends \OpenTHC\Module\Base
{
	function __invoke($a)
	{
		$a->get('', 'OpenTHC\CRE\Controller\License\Search');
		$a->post('', 'OpenTHC\CRE\Controller\License\Create');

		$a->get('/status', 'OpenTHC\CRE\Controller\License\Status');

		// $a->get('/type', 'OpenTHC\CRE\Controller\License\Search:type');

		$a->get('/{id}', 'OpenTHC\CRE\Controller\License\Single');
		$a->post('/{id}', 'OpenTHC\CRE\Controller\License\Update');

		$a->delete('/{id}', 'OpenTHC\CRE\Controller\License\Delete');

	}
}
