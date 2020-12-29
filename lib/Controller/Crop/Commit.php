<?php
/**
 * Commits a Group of Crop Collections
 */

 namespace App\Controller\Crop;

 class Commit extends \App\Controller\Base
 {
 	function __invoke($REQ, $RES, $ARG)
 	{
		if (empty($ARG['id'])) {
			return $RES->withJSON([
				'data' => [],
				'meta' => [
					'detail' => 'Argument Not Provided [CPC#017]',
				]
			], 400);

			// Commit needs to process the Crop , something else and then

			// return a Lot id, adn then may be the Lot in the lot field of the list.
		}

	}
}
