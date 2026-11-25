<?php
/**
 * Inflate the JSON inbound
 */

namespace OpenTHC\CRE\Middleware;

class InputDataFilter extends \OpenTHC\CRE\Middleware\Base
{
	public function __invoke($REQ, $handler)
	{
		$RES = new \OpenTHC\HTTP\Response();

		// Alpha Magic
		$type = strtolower(strtok($_SERVER['CONTENT_TYPE'] ?? '', ';'));
		$size = intval($_SERVER['CONTENT_LENGTH'] ?? 0);

		if (($size > 0) && ('application/json' == $type)) {

			$json = file_get_contents('php://input');
			$data = json_decode($json, true);

			if (empty($data)) {
				$e = json_last_error_msg();
				return $RES->withJSON([
					'data' => null,
					'meta' => [ 'note' => sprintf('JSON Parsing Error "%s" [LMI-025]', $e) ],
				], 400);
			}

			$REQ = $REQ->withAttribute('JSON', $data);

			// SHIT HACK overloads $_POST to our JSON object
			$_POST = $data;

		}

		return $handler->handle($REQ);

	}
}
