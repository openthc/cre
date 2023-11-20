<?php
/**
 * Product Routes
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Module;

class Product extends \OpenTHC\Module\Base
{
	function __invoke($a)
	{
		$a->get('', 'OpenTHC\CRE\Controller\Product\Search');
		$a->post('', 'OpenTHC\CRE\Controller\Product\Create');

		$a->get('/status', 'OpenTHC\CRE\Controller\Product\Status');

		$a->get('/type', 'OpenTHC\CRE\Controller\Product\Type');
		// $a->post('/type', 'OpenTHC\CRE\Controller\Product\Type\Create');
		// $a->delete('/type', 'OpenTHC\CRE\Controller\Product\Type\Create');

		$a->get('/{id}', 'OpenTHC\CRE\Controller\Product\Single');
		$a->post('/{id}', 'OpenTHC\CRE\Controller\Product\Update');

		$a->delete('/{id}', 'OpenTHC\CRE\Controller\Product\Delete');

	}
}
