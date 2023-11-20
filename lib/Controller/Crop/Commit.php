<?php
/**
 * Commits a Group of Crop Collections
 */

 namespace OpenTHC\CRE\Controller\Crop;

 class Commit extends \OpenTHC\CRE\Controller\Base
 {
 	function __invoke($REQ, $RES, $ARG)
 	{
		if (empty($ARG['id'])) {
			return $RES->withJSON([
				'data' => [],
				'meta' => [
					'note' => 'Argument Not Provided [CPC-017]',
				]
			], 400);

			// Commit needs to process the Crop , something else and then

			// return a Lot id, adn then may be the Lot in the lot field of the list.
		}

	}
}
