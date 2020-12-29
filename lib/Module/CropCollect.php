<?php
/**
 * CropCollect Route Handlers
 */

namespace App\Module;

class CropCollect extends \OpenTHC\Module\Base
{
	function __invoke($a)
	{
		$a->get('/{id}', 'App\Controller\CropCollect\Single');
		$a->post('/{id}/commit', 'App\Controller\CropCollect\Commit');
	}
}
