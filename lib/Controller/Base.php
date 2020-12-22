<?php
/*
 * Base Controller
 * Provides hook for log_audit
 * Provides hook for lua_script
 */

namespace App\Controller;

use Edoceo\Radix\ULID;

class Base extends \OpenTHC\Controller\Base
{
	/**
	 * Enter an Audit Log Entry
	 * @param string $msg [description]
	 * @param string $oid [description]
	 * @param array $ctx Data Array
	 * @return void [description]
	 */
	function logAudit($msg, $oid, $ctx)
	{
		$arg = array(
			':id' => ULID::create(),
			':pk' => $oid,
			':m' => $msg,
			':x' => $ctx,
		);
		if (!is_string($arg[':x'])) {
			$arg[':x'] = json_encode($arg[':x']);
		}

		$this->_container->DB->query('INSERT INTO log_audit (id, pk, code, meta) values (:id, :pk, :m, :x)', $arg);

	}

	/**
	 * Evaluate Scripts applied to the Object
	 * @param string $src Script Source location
	 * @param string $obj_name The Name of the object to be sent to the lua script
	 * @param data-array $obj_data The data-array of the object
	 * @return data-array the modified $obj_data
	 */
	function evalObjectScript($src, $obj_name, $obj_data)
	{
		$script_list = $this->_load_scripts($src);

		foreach ($script_list as $f) {

			$lua = new Lua();
			$lua->assign($obj_name, $obj_data);
			$obj_data = $lua->eval(file_get_contents($f));
		}

		return $obj_data;

	}

	/**
	 * Load the lua sources from the specified path
	 * @param [type] $src name of the controller to load scripts for
	 * @return array List of lua scripts
	 */
	function _load_scripts($src)
	{
		$f = sprintf('%s/scripts/%s.lua', APP_ROOT, $src);
		if (is_file($f)) {
			return array($f);
		}

		$d = sprintf('%s/scripts/%s', APP_ROOT, $src);
		if (is_dir($d)) {
			$f_list = glob(sprintf('%s/*.lua', $d));
			return $f_list;
		}

		return array();
	}

	/**
	 * Send an Error Response
	 */
	function sendError($e, $c=500)
	{
		$ret = [];
		if (is_array($e)) {
			$ret = $e;
		} elseif (is_string($e)) {
			$ret = [
				'meta' => [ 'detail' => $e ]
			];
		}

		$R = new \Custom_Response($c);
		return $R->withJSON($ret);

	}

	/**
	 * [send404 description]
	 * @param string $m Message to Send
	 * @return object Response Object
	 */
	function send404($m)
	{
		$R = new \Custom_Response(404);
		return $R->withJSON([
			'meta' => [ 'detail' => $m ]
		]);
	}

}
