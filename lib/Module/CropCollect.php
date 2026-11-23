<?php
/**
 * CropCollect Route Handlers
 *
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\CRE\Module;

class CropCollect
{
	function __invoke($a)
	{
		$a->get('/{id}', 'OpenTHC\CRE\Controller\CropCollect\Single');
		$a->post('/{id}/commit', 'OpenTHC\CRE\Controller\CropCollect\Commit');

		$a->post('/{id}/finish', 'OpenTHC\CRE\Controller\CropCollect\Finish');
	}
}
