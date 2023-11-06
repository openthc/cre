<?php
/**
 * OpenTHC CRE Configuration Example
 */

// Init
$cfg = [];

// Database
$cfg['database'] = [
	'cre' => [
		'hostname' => '127.0.0.1',
		'database' => 'openthc_cre',
		'username' => 'openthc_cre',
		'password' => 'openthc_cre'
	]
];

// Redis
$cfg['redis'] = [
	'hostname' => '127.0.0.1',
	'database' => '0',
	'publish' => 'openthc_cre_pub',
];

// OpenTHC
$cfg['openthc'] = [
	'sso' => [
		'origin' => 'https://sso.openthc.example.com',
	]
];

return $cfg;
