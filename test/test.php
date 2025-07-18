#!/usr/bin/env php
<?php
/**
 * OpenTHC CRE Test Runner
 *
 * SPDX-License-Identifier: MIT
 */

require_once(dirname(__DIR__) . '/boot.php');

// Default Option
if (empty($_SERVER['argv'][1])) {
	$_SERVER['argv'][1] = 'phpunit';
	$_SERVER['argc'] = count($_SERVER['argv']);
}

// Command Line
$doc = <<<DOC
OpenTHC CRE Test Runner

Usage:
	test [options]
	test <command> [options]
	test phpunit
	test phpstan
	test phplint

Options:
	--filter=<F>             Some Filter
	--phpunit-testcase=<T>   PHPUnit Testcase
DOC;

$res = \Docopt::handle($doc, [
	'exit' => false,
	'optionsFirst' => false,
]);
var_dump($res);
$cli_args = $res->args;
var_dump($cli_args);
// if (empty($cli_args)) {
// 	echo $res->output;
// 	echo "\n";
// 	exit(1);
// }
if ('all' == $cli_args['<command>']) {
	$cli_args['phplint'] = true;
	$cli_args['phpstan'] = true;
	$cli_args['phpunit'] = true;
} else {
	$cmd = $cli_args['<command>'];
	$cli_args[$cmd] = true;
	unset($cli_args['<command>']);
}


// Test Config
$cfg = [];
$cfg['base'] = APP_ROOT;
$cfg['site'] = 'cre';

$test_helper = new \OpenTHC\Test\Helper($cfg);
$cfg['output'] = $test_helper->output_path;


// PHPLint
if ($cli_args['phplint']) {
	$tc = new \OpenTHC\Test\Facade\PHPLint($cfg);
	$res = $tc->execute(); // 0=Success; 1=Failure
	switch ($res) {
	case 0:
		echo "PHPLint Success\n";
		break;
	case 1:
	default:
		echo "PHPLint Failure ($res)\n";
		break;
	}
}


// PHPStan
if ($cli_args['phpstan']) {
	$tc = new \OpenTHC\Test\Facade\PHPStan($cfg);
	$res = $tc->execute();
	var_dump($res);
}


// Psalm/Psalter?


// PHPUnit
// Pick Config File
$cfg_file_list = [];
$cfg_file_list[] = sprintf('%s/phpunit.xml', __DIR__);
$cfg_file_list[] = sprintf('%s/phpunit.xml.dist', __DIR__);
foreach ($cfg_file_list as $f) {
	if (is_file($f)) {
		$cfg['--configuration'] = $f;
		break;
	}
}
// Filter?
if ( ! empty($cli_args['--filter'])) {
	$cfg['--filter'] = $cli_args['--filter'];
}
if ( ! empty($cli_args['--phpunit-filter'])) {
	$cfg['--filter'] = $cli_args['--phpunit-filter'];
}
if ( ! empty($cli_args['--phpunit-testsuite'])) {
	$cfg['--testsuite'] = $cli_args['--phpunit-testsuite'];
}
$tc = new \OpenTHC\Test\Facade\PHPUnit($cfg);
$res = $tc->execute();
switch ($res['code']) {
case 0:
case 200:
	echo "\nTEST SUCCESS\n";
	break;
case 1:
case 2:
case 400:
case 500:
	echo "\nTEST FAILURE\n";
	echo $res['data'];
	break;
default:
	echo "\nTEST UNKNOWN ($res)\n";
	break;
}


// Output
$res = $test_helper->index_create($res['data']);
echo "TEST COMPLETE\n  $res\n";
