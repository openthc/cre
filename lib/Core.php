<?php
/**
 * A Slim App Core with improved error handlers
 */

namespace App;

class Core extends \OpenTHC\App
{
	function __construct($options)
	{
		// $default =
		// $options = array_merge($default, $options);
		parent::__construct($options);
		$this->_load_default_config();

	}

	function _load_default_config()
	{
		// 404 Handler
		$con = $this->getContainer();

		// Called when the error happens
		$con['errorHandler'] = function($c) {
			// $c == container
			// returns a function, that takes the info
			return function ($REQ, $RES, $ERR) use ($c) {

				// Log it?

				// Default message, very brief (we hope)
				$msg = $ERR->getMessage();

				// @todo Filters?
				// if (preg_match('/SQLSTATE/', $msg)) {
				// 	$msg = 'SQL Error';
				// }

				// New response, don't trust the thing we get
				$RES = new \Slim\Http\Response(500);
				$RES = $RES->withProtocolVersion('1.1');

				// Debug information?
				if ($c->debug) {
					// var_dump($ERR); exit;
					return $RES->withJSON([
						'data' => [],
						'meta' => [
							'detail' => $msg,
							'origin' => 'app',
							'source' => [
								'code' => $ERR->getCode(),
								'file' => $ERR->getFile(),
								'line' => $ERR->getLine(),
							],
							'stack' => $ERR->getTraceAsString(),
							// 'trace' => $ERR->getTrace(),
							// '_methods' => get_class_methods($ERR),
							// '_json' => json_decode(json_encode($ERR), true)
						]
					], 500, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
				}


				return $RES->withJSON([
					'data' => [],
					'meta' => [
						'detail' => $msg,
						'origin' => 'app',
					]
				]);

			};

		};

		$con['phpErrorHandler'] = function($c) {
			// $c == container
			// returns a function, that takes the info
			return function ($REQ, $RES, $ERR) use ($c) {

				// Log it?

				// Default message, very brief (we hope)
				$msg = $ERR->getMessage();

				// @todo Filters?

				// New response, don't trust the thing we get
				$RES = new \Slim\Http\Response(500);
				$RES = $RES->withProtocolVersion('1.1');

				// Debug information?
				if ($c->debug) {
					// var_dump($ERR); exit;
					return $RES->withJSON([
						'data' => [],
						'meta' => [
							'detail' => $msg,
							'origin' => 'php',
							'source' => [
								'code' => $ERR->getCode(),
								'file' => $ERR->getFile(),
								'line' => $ERR->getLine(),
							],
							// 'stack' => $ERR->getTraceAsString(),
							// 'trace' => $ERR->getTrace(),
							//'_methods' => get_class_methods($ERR),
							// '_json' => json_decode(json_encode($ERR), true)
						]
					], 500, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
				}


				return $RES->withJSON([
					'data' => [],
					'meta' => [
						'detail' => $msg,
						'origin' => 'app',
					]
				]);

			};
		};

		$con['notAllowedHandler'] = function($c) {
			return function ($REQ, $RES) {
				$RES = new \Slim\Http\Response(405);
				$RES = $RES->withProtocolVersion('1.1');
				return $RES->withJSON(array(
					'data' => [],
					'meta' => [
						'detail' => 'HTTP Method Not Allowed',
					],
				));
			};
		};

	}
}
