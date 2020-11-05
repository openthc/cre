<?php
/**
 * OpenTHC CRE Bootstrap
 */

define('APP_ROOT', __DIR__);

error_reporting(E_ALL & ~ E_NOTICE);

openlog('openthc-core', LOG_ODELAY|LOG_PID, LOG_LOCAL0);

require_once(APP_ROOT . '/vendor/autoload.php');

/**
 * Add your own Custom, Top-Level Functions/Includes Here
 */
/**
 * Check ACL
 * @param string $sub [description]
 * @param string $obj [description]
 * @param string $act [description]
 * @return bool [description]
 */
function _acl($sub, $obj, $act)
{
	static $e;

	// We would have to implement a Model to cache it or make it faster
	// We would have to implement an Adapter to cache it or make it faster

	if (empty($e)) {
		$cmf = sprintf('%s/etc/casbin/model.conf', APP_ROOT); // Model
		$cpf = sprintf('%s/etc/casbin/policy.csv', APP_ROOT); // Adapter
		$e = new \Casbin\Enforcer($cmf, $cpf);
	}

	return $e->enforce($sub, $obj, $act);
}

function _acl_exit($s, $o, $a)
{
	if (!_acl($s, $o, $a)) {
		_exit_html('Access Denied [APP#169]<br><a href="/auth">/auth</a>', 403);
	}
}
