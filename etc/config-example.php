<?php
/**
 * OpenTHC CRE Configuration Example
 */

$cfg = [];

$cfg['database'] = [
	'cre' => [
		'hostname' => '127.0.0.1',
		'database' => 'openthc_cre',
		'username' => 'openthc_cre',
		'password' => 'openthc_cre'
	]
];

$cfg['redis'] = [
	'hostname' => '127.0.0.1',
	'database' => '0',
	'publish' => 'openthc_cre_pub',
];

$cfg['openthc'] = [
	'sso' => [
		'hostname' => 'sso.openthc.example.com',
	]
];

return $cfg;
