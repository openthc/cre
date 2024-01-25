<?php
/**
 * B2B Routes
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Module;

class B2B extends \OpenTHC\Module\Base
{
	function __invoke($a)
	{
		// $a->get('', 'OpenTHC\CRE\Controller\B2B\Search'); // Show Active Incoming & Outgoing
		$a->post('', 'OpenTHC\CRE\Controller\B2B\Create');

		$a->get('/incoming', 'OpenTHC\CRE\Controller\B2B\Search:incoming');
		$a->get('/outgoing', 'OpenTHC\CRE\Controller\B2B\Search:outgoing');

		// $a->get('/type', 'OpenTHC\CRE\Controller\B2B\Search:type');
		// $a->get('/item/type', 'OpenTHC\CRE\Controller\B2B\Search:type');

		$a->post('/{id}/accept', 'OpenTHC\CRE\Controller\B2B\Accept');

		// $a->post('/{id}/item', 'OpenTHC\CRE\Controller\B2B\Update::item');
		// $a->delete('/{id}/item/{id}', 'OpenTHC\CRE\Controller\B2B\Delete::item');

		$a->get('/{id}', 'OpenTHC\CRE\Controller\B2B\Single');
		$a->post('/{id}', 'OpenTHC\CRE\Controller\B2B\Update');
		$a->delete('/{id}', 'OpenTHC\CRE\Controller\B2B\Delete');

		// $a->post('/{id}/commit', 'OpenTHC\CRE\Controller\B2B\Commit');

		// $a->get('/incoming/{id}', 'OpenTHC\CRE\Controller\B2B\Single');
		// $a->post('/incoming/{id}', 'OpenTHC\CRE\Controller\B2B\Update');

		// $a->get('/outgoing/{id}', 'OpenTHC\CRE\Controller\B2B\Single');
		// $a->post('/outgoing/{id}', 'OpenTHC\CRE\Controller\B2B\Update');

		$a->delete('/outgoing/{id}', 'OpenTHC\CRE\Controller\B2B\Delete');

	}
}
