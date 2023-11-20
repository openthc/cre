<?php
/**
 * Section Routes
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Module;

class Section extends \OpenTHC\Module\Base
{
	function __invoke($a)
	{
		$a->get('', 'OpenTHC\CRE\Controller\Section\Search');
		$a->post('', 'OpenTHC\CRE\Controller\Section\Create');

		$a->get('/status', 'OpenTHC\CRE\Controller\Section\Status');

		// $a->get('/type', 'OpenTHC\CRE\Controller\Section\Search:type');

		$a->get('/{id}', 'OpenTHC\CRE\Controller\Section\Single');
		$a->post('/{id}', 'OpenTHC\CRE\Controller\Section\Update');
		$a->delete('/{id}', 'OpenTHC\CRE\Controller\Section\Delete');

	}
}
