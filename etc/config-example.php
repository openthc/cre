<?php
/**
 * OpenTHC CRE Configuration Example
 */

$cfg = [];

$cfg['database'] = [
	'hostname' => '127.0.0.1',
	'database' => 'openthc_cre',
	'username' => 'openthc_cre',
	'password' => 'openthc_cre'
];

$cfg['redis'] = [
	'hostname' => '127.0.0.1',
	'database' => '0',
];

$cfg['openthc'] = [
	'sso' => [
		'hostname' => 'sso.openthc.dev',
	]
];

return $cfg;
