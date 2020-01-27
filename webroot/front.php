<?php
/**
 * Front Controller
 */

require_once(dirname(dirname(__FILE__)) . '/boot.php');

$_ENV['mwd'] = 0;

$cfg = [
	'debug' => true,
	'settings' => [
		'routerCacheFile' => '/tmp/slim-router.cache',
	]
];
$app = new \App\Core($cfg);
$con = $app->getContainer();

// Use my Custom Response Object
class Custom_Response extends \Slim\Http\Response
{
	function __construct($c=200, $h=null)
	{
		$h = new \Slim\Http\Headers(['Content-Type' => 'text/html; charset=utf-8']);
		parent::__construct($c, $h);
		$this->withProtocolVersion('1.1');
	}

	function withJSON($data, $code=null, $flag=null)
	{
		if (empty($flag)) {
			$flag = JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;
		}
		// if (empty($code))

		return parent::withJSON($data, $code, $flag);
	}
}

$con['response'] = function($c) {
	$r = new Custom_Response();
	// $r = new \Slim\Http\Response(200, $h);
	return $r;
};


// unset($con['errorHandler']);
// unset($con['phpErrorHandler']);
// unset($con['notFoundHandler']);
// set_error_handler(function ($severity, $message, $file, $line) {
//     if (!(error_reporting() & $severity)) {
//         // This error code is not included in error_reporting, so ignore it
//         return;
//     }
//     throw new \ErrorException($message, 0, $severity, $file, $line);
// });

$con['DB'] = function($c) {
	$cfg = \OpenTHC\Config::get('database_main');
	$c = sprintf('pgsql:host=%s;dbname=%s', $cfg['hostname'], $cfg['database']);
	$u = $cfg['username'];
	$p = $cfg['password'];
	$dbc = new \Edoceo\Radix\DB\SQL($c, $u, $p);
	return $dbc;
};

$con['Redis'] = function($c) {
	$cfg = \OpenTHC\Config::get('redis_pubsub');
	$red = new \Redis();
	$red->connect($cfg['hostname']);
	return $red;
};


// Authentication
$app->group('/auth', 'App\Module\Auth')
	->add('App\Middleware\Session')
	->add('OpenTHC\Middleware\Log\HTTP');


// Global Company Details (License and Contact container)
$app->group('/config/company', 'App\Module\Company')
	// Inject from Custom via Magic
	->add('App\Middleware\InputDataFilter')
	->add('App\Middleware\Authenticate')
	->add('App\Middleware\Session')
	->add('OpenTHC\Middleware\Log\HTTP');


// Global License Details
$app->group('/config/license', 'App\Module\License')
	->add('App\Middleware\InputDataFilter')
	->add('App\Middleware\Authenticate')
	->add('App\Middleware\Session')
	->add('OpenTHC\Middleware\Log\HTTP');


// Global Contact Details
$app->group('/config/contact', 'App\Module\Contact')
	->add('App\Middleware\InputDataFilter')
	->add('App\Middleware\Authenticate')
	->add('App\Middleware\Session')
	->add('OpenTHC\Middleware\Log\HTTP');


/*
	License Specific Data
*/

// Config Strain Details
$app->group('/config/strain', 'App\Module\Strain')
	->add('App\Middleware\InputDataFilter')
	->add('App\Middleware\Authenticate')
	->add('App\Middleware\Session')
	->add('OpenTHC\Middleware\Log\HTTP');


// Config Product Details
$app->group('/config/product', 'App\Module\Product')
	->add('App\Middleware\InputDataFilter')
	->add('App\Middleware\Authenticate')
	->add('App\Middleware\Session')
	->add('OpenTHC\Middleware\Log\HTTP');


// Config Zone
$app->group('/config/zone', 'App\Module\Zone')
	->add('App\Middleware\InputDataFilter')
	->add('App\Middleware\Authenticate')
	->add('App\Middleware\Session')
	->add('OpenTHC\Middleware\Log\HTTP');


// Inventory Lot
$app->group('/lot', 'App\Module\Lot')
	->add('App\Middleware\InputDataFilter')
	->add('App\Middleware\Authenticate')
	->add('App\Middleware\Session')
	->add('OpenTHC\Middleware\Log\HTTP');


// Plant
$app->group('/plant', 'App\Module\Plant')
	->add('App\Middleware\InputDataFilter')
	->add('App\Middleware\Authenticate')
	->add('App\Middleware\Session')
	->add('OpenTHC\Middleware\Log\HTTP');


// Plant Collect
$app->group('/plant-collect', 'App\Module\PlantCollect')
	->add('App\Middleware\InputDataFilter')
	->add('App\Middleware\Authenticate')
	->add('App\Middleware\Session')
	->add('OpenTHC\Middleware\Log\HTTP')
;


// Lab Samples and Results
$app->group('/lab', 'App\Module\Lab')
	->add('App\Middleware\InputDataFilter')
	->add('App\Middleware\Authenticate')
	->add('App\Middleware\Session')
	->add('OpenTHC\Middleware\Log\HTTP')
;



// Transfer
$app->group('/b2b', 'App\Module\Transfer')
	->add('App\Middleware\InputDataFilter')
	->add('App\Middleware\Authenticate')
	->add('App\Middleware\Session')
	->add('OpenTHC\Middleware\Log\HTTP');


// Retail Sale
$app->group('/b2c', 'App\Module\Sale')
	->add('App\Middleware\InputDataFilter')
	->add('App\Middleware\Authenticate')
	->add('App\Middleware\Session')
	->add('OpenTHC\Middleware\Log\HTTP');


// $app->add('App\Middleware\Log\PubSub');
// $app->add('App\Middleware\IP');

$app->get('/ulid', 'App\Controller\ULID');

// And...go!
$app->run();
