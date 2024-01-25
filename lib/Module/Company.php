<?php
/**
 * Company
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Module;

class Company extends \OpenTHC\Module\Base
{
	function __invoke($a)
	{
		$a->get('', 'OpenTHC\CRE\Controller\Company\Search');
		// Runs *AFTER* Middleware Registered on Group in Front
		//->add(function($REQ, $RES, $NMW) { return $NMW($REQ, $RES); });

		$a->post('', 'OpenTHC\CRE\Controller\Company\Create');

		// $a->get('/type', 'OpenTHC\CRE\Controller\Company\Search:type');

		$a->get('/{id}', 'OpenTHC\CRE\Controller\Company\Single');
		$a->post('/{id}', 'OpenTHC\CRE\Controller\Company\Update');

		$a->delete('/{id}', 'OpenTHC\CRE\Controller\Company\Delete');

		// Runs *BEFORE* Middleware Registered on Group in Front
		//$a->add(function($REQ, $RES, $NMW) { return $NMW($REQ, $RES); });

	}
}
