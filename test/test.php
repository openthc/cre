#!/usr/bin/php
<?php
/**
 * OpenTHC CRE Test
 *
 * SPDX-License-Identifier: MIT
 */

require_once(dirname(__DIR__) . '/boot.php');

// $arg = \OpenTHC\Docopt::parse($doc, ?$argv=[]);
// Parse CLI
$doc = <<<DOC
OpenTHC CRE Test

Usage:
	test [options]
	test <command> [options]
	test phpunit
	test phpstan
	test phplint

Options:
	--filter=<F>             Some Filter
	--phpunit-filter=<F>     Some Filter for PHPUnit
	--phpunit-testcase=<T>   PHPUnit Testcase
DOC;

$res = Docopt::handle($doc, [
	'exit' => false,
	'help' => true,
	'optionsFirst' => false,
]);
$cli_args = $res->args;
// if (empty($cli_args)) {
// 	echo $res->output;
// 	echo "\n";
// 	exit(1);
// }
// var_dump($cli_args);
// exit;
if ('all' == $cli_args['<command>']) {
	$cli_args['phplint'] = true;
	$cli_args['phpstan'] = true;
	$cli_args['phpunit'] = true;
} else {
	$cmd = $cli_args['<command>'];
	$cli_args[$cmd] = true;
	unset($cli_args['<command>']);
}


define('OPENTHC_TEST_OUTPUT_BASE', \OpenTHC\Test\Helper::output_path_init());


// PHPLint
$tc = new \OpenTHC\Test\Facade\PHPLint([
	'output' => OPENTHC_TEST_OUTPUT_BASE
]);
// $res = $tc->execute();
// var_dump($res);

#
# PHP-CPD
# vendor/openthc/common/test/phpcpd.sh
// vendor/bin/phpmd boot.php,webroot/main.php,lib/,test/ \
// 	html \
// 	cleancode \
// 	--report-file "${OUTPUT_BASE}/phpmd.html" \
// 	|| true

// Call PHPCS?
// $tc = \OpenTHC\Test\PHPStyle::execute();


// PHPStan
$tc = new \OpenTHC\Test\Facade\PHPStan([
	'output' => OPENTHC_TEST_OUTPUT_BASE
]);
// $res = $tc->execute();
// var_dump($res);

// Psalm/Psalter?


// PHPUnit
// $cfg = [];
// $tc = new \OpenTHC\Test\Facade\PHPUnit($cfg);
// $res = $tc->execute();
// var_dump($res);

chdir(sprintf('%s/test', APP_ROOT));

$cfg = [
	'output' => OPENTHC_TEST_OUTPUT_BASE
];
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
// var_dump($res);
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


// Done
\OpenTHC\Test\Helper::index_create($html);


// Output Information
$origin = \OpenTHC\Config::get('openthc/cre/origin');
$output = str_replace(sprintf('%s/webroot/', APP_ROOT), '', OPENTHC_TEST_OUTPUT_BASE);

echo "TEST COMPLETE\n  $origin/$output\n";
