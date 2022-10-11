<?php
/**
 * Company
 */

namespace App\Module;

class Company extends \OpenTHC\Module\Base
{
	function __invoke($a)
	{
		$a->get('', 'App\Controller\Company\Search');
		// Runs *AFTER* Middleware Registered on Group in Front
		//->add(function($REQ, $RES, $NMW) { return $NMW($REQ, $RES); });

		$a->post('', 'App\Controller\Company\Create');

		// $a->get('/type', 'App\Controller\Company\Search:type');

		$a->get('/{id}', 'App\Controller\Company\Single');
		$a->post('/{id}', 'App\Controller\Company\Update');

		$a->delete('/{id}', 'App\Controller\Company\Delete');

		// Runs *BEFORE* Middleware Registered on Group in Front
		//$a->add(function($REQ, $RES, $NMW) { return $NMW($REQ, $RES); });

	}
}
