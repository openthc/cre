#!/usr/bin/php
<?php
/**
 * OpenTHC App Test
 */

require_once(dirname(__DIR__) . '/boot.php');

// $arg = \OpenTHC\Docopt::parse($doc, ?$argv=[]);
// Parse CLI
$doc = <<<DOC
OpenTHC App Test

Usage:
	test [options]
	test <command> [options]
	test phpunit
	test phpstan
	test phplint

Options:
	--filter=<FILTER>           Some Filter
	--phpunit-filter=<FILTER>   Some Filter for PHPUnit
	--phpunit-filter=<FILTER>   Some Filter for PHPUnit
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
var_dump($cli_args);
// exit;

define('OPENTHC_TEST_OUTPUT_BASE', \OpenTHC\Test\Helper::output_path_init());


// Call Linter?
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
$tc = new OpenTHC\Test\Facade\PHPStan([
	'output' => OPENTHC_TEST_OUTPUT_BASE
]);
$res = $tc->execute();
var_dump($res);


// Psalm/Psalter?


// PHPUnit
// $cfg = [];
// $tc = new OpenTHC\Test\Facade\PHPUnit($cfg);
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
if ( ! empty($arg['--filter'])) {
	$cfg['--filter'] = $arg['--filter'];
}
$tc = new \OpenTHC\Test\Facade\PHPUnit($cfg);
$res = $tc->execute();
// var_dump($res);
switch ($res) {
case 0:
	echo "\nTEST SUCCESS\n";
	break;
case 1:
	echo "\nTEST FAILURE\n";
	break;
case 2:
	echo "\nTEST FAILURE (ERRORS)\n";
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
