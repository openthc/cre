<?php
/**
 * B2C Routes
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Module;

class B2C extends \OpenTHC\Module\Base
{
	function __invoke($a)
	{
		$a->get('', 'OpenTHC\CRE\Controller\B2C\Search');
		$a->post('', 'OpenTHC\CRE\Controller\B2C\Create');

		$a->get('/{id}', 'OpenTHC\CRE\Controller\B2C\Single');
		$a->post('/{id}', 'OpenTHC\CRE\Controller\B2C\Update');

		$a->delete('/{id}', 'OpenTHC\CRE\Controller\B2C\Delete');

	}
}
