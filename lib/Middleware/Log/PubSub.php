<?php
/**
 * Log some details to PubSub
 */

namespace App\Middleware\Log;

class PubSub extends \OpenTHC\Middleware\Base
{
	public function __invoke($REQ, $RES, $NMW)
	{
		$uid = $_SERVER['UNIQUE_ID'];

		$red = $this->_container->Redis;
		$red->publish('gwsrps', \json_encode([
			$uid,
			$_SERVER['REMOTE_ADDR'],
			$REQ->getMethod(),
			$REQ->getURI()->getPath(),
		]));

		$RES = $NMW($REQ, $RES);

		$red->publish('gwsrps', \json_encode([
			$uid,
			$RES->getStatusCode(),
		]));

		return $RES;


	}

}
