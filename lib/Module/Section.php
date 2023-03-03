<?php
/**
 * Section Routes
 *
 * SPDX-License-Identifier: MIT
 */

namespace App\Module;

class Section extends \OpenTHC\Module\Base
{
	/**
	 *
	 */
	function __invoke($a)
	{
		$a->get('', 'App\Controller\Section\Search');
		$a->post('', 'App\Controller\Section\Create');

		$a->get('/status', 'OpenTHC\CRE\Controller\Section\Status');

		// $a->get('/type', 'App\Controller\Section\Search:type');

		$a->get('/{id}', 'App\Controller\Section\Single');
		$a->post('/{id}', 'App\Controller\Section\Update');
		$a->delete('/{id}', 'App\Controller\Section\Delete');

	}
}
