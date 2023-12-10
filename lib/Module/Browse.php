<?php
/**
 * Authenticate Routes
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Module;

class Browse extends \OpenTHC\Module\Base
{
	/**
	 *
	 */
	function __invoke($a)
	{
		$a->get('', 'OpenTHC\CRE\Controller\Browse\Main');
		$a->get('/contact', 'OpenTHC\CRE\Controller\Browse\Search');
		$a->get('/company', 'OpenTHC\CRE\Controller\Browse\Search');
		$a->get('/license', 'OpenTHC\CRE\Controller\Browse\Search');
		$a->get('/section', 'OpenTHC\CRE\Controller\Browse\Search');
		$a->get('/variety', 'OpenTHC\CRE\Controller\Browse\Search');
		$a->get('/product', 'OpenTHC\CRE\Controller\Browse\Search');
		$a->get('/product/type', 'OpenTHC\CRE\Controller\Browse\Search');
		$a->get('/vehicle', 'OpenTHC\CRE\Controller\Browse\Search');
		$a->get('/crop', 'OpenTHC\CRE\Controller\Browse\Search');
		$a->get('/crop/collect', 'OpenTHC\CRE\Controller\Browse\Search');
		$a->get('/inventory', 'OpenTHC\CRE\Controller\Browse\Search');
		$a->get('/inventory/adjust', 'OpenTHC\CRE\Controller\Browse\Search');
		$a->get('/b2b/outgoing', 'OpenTHC\CRE\Controller\Browse\Search');
		$a->get('/b2b/incoming', 'OpenTHC\CRE\Controller\Browse\Search');
		$a->get('/b2c', 'OpenTHC\CRE\Controller\Browse\Search');

		// Single
		$a->get('/contact/{id}', 'OpenTHC\CRE\Controller\Browse\Single');
		$a->get('/company/{id}', 'OpenTHC\CRE\Controller\Browse\Single');
		$a->get('/license/{id}', 'OpenTHC\CRE\Controller\Browse\Single');
		$a->get('/section/{id}', 'OpenTHC\CRE\Controller\Browse\Single');
		$a->get('/variety/{id}', 'OpenTHC\CRE\Controller\Browse\Single');
		$a->get('/product/{id}', 'OpenTHC\CRE\Controller\Browse\Single');
		$a->get('/product/type/{id}', 'OpenTHC\CRE\Controller\Browse\Single');
		$a->get('/vehicle/{id}', 'OpenTHC\CRE\Controller\Browse\Single');
		$a->get('/crop/{id}', 'OpenTHC\CRE\Controller\Browse\Single');
		$a->get('/crop/collect/{id}', 'OpenTHC\CRE\Controller\Browse\Single');
		$a->get('/inventory/{id}', 'OpenTHC\CRE\Controller\Browse\Single');
		$a->get('/inventory/adjust/{id}', 'OpenTHC\CRE\Controller\Browse\Single');
		$a->get('/b2b/outgoing/{id}', 'OpenTHC\CRE\Controller\Browse\Single');
		$a->get('/b2b/incoming/{id}', 'OpenTHC\CRE\Controller\Browse\Single');
		$a->get('/b2c/{id}', 'OpenTHC\CRE\Controller\Browse\Single');

		// Update
		$a->post('/contact/{id}', 'OpenTHC\CRE\Controller\Browse\Update');
		$a->post('/company/{id}', 'OpenTHC\CRE\Controller\Browse\Update');
		$a->post('/license/{id}', 'OpenTHC\CRE\Controller\Browse\Update');
		$a->post('/section/{id}', 'OpenTHC\CRE\Controller\Browse\Update');
		$a->post('/variety/{id}', 'OpenTHC\CRE\Controller\Browse\Update');
		$a->post('/product/{id}', 'OpenTHC\CRE\Controller\Browse\Update');
		$a->post('/product/type/{id}', 'OpenTHC\CRE\Controller\Browse\Update');
		$a->post('/vehicle/{id}', 'OpenTHC\CRE\Controller\Browse\Update');
		$a->post('/crop/{id}', 'OpenTHC\CRE\Controller\Browse\Update');
		$a->post('/crop/collect/{id}', 'OpenTHC\CRE\Controller\Browse\Update');
		$a->post('/inventory/{id}', 'OpenTHC\CRE\Controller\Browse\Update');
		$a->post('/inventory/adjust/{id}', 'OpenTHC\CRE\Controller\Browse\Update');
		$a->post('/b2b/outgoing/{id}', 'OpenTHC\CRE\Controller\Browse\Update');
		$a->post('/b2b/incoming/{id}', 'OpenTHC\CRE\Controller\Browse\Update');
		$a->post('/b2c/{id}', 'OpenTHC\CRE\Controller\Browse\Update');


	}

}
