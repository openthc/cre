<?php
/**
 * Log some details to PubSub
 */

namespace OpenTHC\CRE\Middleware\Log;

class PubSub extends \OpenTHC\CRE\Middleware\Base
{
	public function __invoke($REQ, $handler)
	{
		$uid = $_SERVER['UNIQUE_ID'];

		$red = $this->dic->get('Redis');
		$red->publish('gwsrps', \json_encode([
			$uid,
			$_SERVER['REMOTE_ADDR'],
			$REQ->getMethod(),
			$REQ->getURI()->getPath(),
		]));

		$RES = $handler->handle($REQ);

		$red->publish('gwsrps', \json_encode([
			$uid,
			$RES->getStatusCode(),
		]));

		return $RES;

	}

}
