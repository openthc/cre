<?php
/**
 * OpenTHC CRE Bootstrap
 *
 * SPDX-License-Identifier: MIT
 */

define('APP_ROOT', __DIR__);

error_reporting(E_ALL & ~ E_NOTICE & ~ E_WARNING);

openlog('openthc-cre', LOG_ODELAY|LOG_PID, LOG_LOCAL0);

require_once(APP_ROOT . '/vendor/autoload.php');

if ( ! \OpenTHC\Config::init(APP_ROOT) ) {
	_exit_html_fail('<h1>Invalid Application Configuration [ALB-015]</h1>', 500);
}

/**
 * PostgreSQL Connection
 */
function _dbc() : \Edoceo\Radix\DB\SQL
{
	static $dbc;
	if (empty($dbc)) {
		$cfg = \OpenTHC\Config::get('database');
		$dsn = sprintf('pgsql:application_name=openthc-cre;host=%s;dbname=%s', $cfg['hostname'], $cfg['database']);
		$dbc = new \Edoceo\Radix\DB\SQL($dsn, $cfg['username'], $cfg['password']);
	}
	return $dbc;
}

/**
 * Redis Connection
 */
function _rdb() : \Redis
{
	static $rdb;
	if (empty($rdb)) {
		$cfg = \OpenTHC\Config::get('redis');
		$rdb = new \Redis();
		$rdb->connect($cfg['hostname']);
		$rdb->select(intval($cfg['database']));
	}
	return $rdb;
}

/**
 * Add your own Custom, Top-Level Functions/Includes Here
 */
