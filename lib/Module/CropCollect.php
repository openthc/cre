<?php
/**
 * CropCollect Route Handlers
 */

namespace OpenTHC\CRE\Module;

class CropCollect extends \OpenTHC\Module\Base
{
	function __invoke($a)
	{
		$a->get('/{id}', 'OpenTHC\CRE\Controller\CropCollect\Single');
		$a->post('/{id}/commit', 'OpenTHC\CRE\Controller\CropCollect\Commit');

		$a->post('/{id}/finish', 'OpenTHC\CRE\Controller\CropCollect\Finish');
	}
}
