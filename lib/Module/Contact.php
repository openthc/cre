<?php
/**
 * Contact Routes
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Module;

class Contact extends \OpenTHC\Module\Base
{
	function __invoke($a)
	{
		$a->get('', 'OpenTHC\CRE\Controller\Contact\Search');
		$a->post('', 'OpenTHC\CRE\Controller\Contact\Create');

		// $a->get('/type', 'OpenTHC\CRE\Controller\Contact\Search:type');

		$a->get('/{id}', 'OpenTHC\CRE\Controller\Contact\Single');
		$a->post('/{id}', 'OpenTHC\CRE\Controller\Contact\Update');

		$a->delete('/{id}', 'OpenTHC\CRE\Controller\Contact\Delete');

	}
}
