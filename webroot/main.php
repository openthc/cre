<?php
/**
 * Front Controller
 */

require_once(dirname(dirname(__FILE__)) . '/boot.php');

$cfg = [];
// $cfg['debug'] = true;
$cfg['settings'] = [];
// $cfg['settings']['routerCacheFile'] = '/tmp/slim-router.cache';

$app = new \App\Core($cfg);
$con = $app->getContainer();

if ($cfg['debug']) {
	unset($con['errorHandler']);
	unset($con['phpErrorHandler']);
	unset($con['notFoundHandler']);
}


// Use my Custom Response Object
class Custom_Response extends \Slim\Http\Response
{
	function __construct($c=200, $h=null)
	{
		$h = new \Slim\Http\Headers(['content-type' => 'text/html; charset=utf-8']);
		parent::__construct($c, $h);
		$this->withProtocolVersion('1.1');
	}

	function withJSON($data, $code=null, $flag=null)
	{
		if (empty($flag)) {
			$flag = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;
		}

		return parent::withJSON($data, $code, $flag);
	}
}

$con['response'] = function($c) {
	$r = new Custom_Response();
	return $r;
};

// Database
$con['DB'] = function($c) {
	return _dbc();
};

// Redis
$con['Redis'] = function($c) {
	return _rdb();
};


// Authentication
$app->group('/auth', 'App\Module\Auth')
	->add('App\Middleware\Session')
	// ->add('OpenTHC\Middleware\Log\HTTP')
	;


// Global Company
$app->group('/company', 'App\Module\Company')
	->add('App\Middleware\InputDataFilter')
	->add('App\Middleware\Authenticate')
	->add('App\Middleware\Session')
	// ->add('OpenTHC\Middleware\Log\HTTP')
	;


// Global License
$app->group('/license', 'App\Module\License')
	->add('App\Middleware\InputDataFilter')
	->add('App\Middleware\Authenticate')
	->add('App\Middleware\Session')
	// ->add('OpenTHC\Middleware\Log\HTTP')
	;


// Global Contact
$app->group('/contact', 'App\Module\Contact')
	->add('App\Middleware\InputDataFilter')
	->add('App\Middleware\Authenticate')
	->add('App\Middleware\Session')
	// ->add('OpenTHC\Middleware\Log\HTTP')
	;


/*
 * Company/License Specific Data
 */

// Config Product
$app->group('/product', 'App\Module\Product')
	->add('App\Middleware\InputDataFilter')
	->add('App\Middleware\Authenticate')
	->add('App\Middleware\Session')
	// ->add('OpenTHC\Middleware\Log\HTTP')
	;


// Config Variety
$app->group('/variety', 'App\Module\Variety')
	->add('App\Middleware\InputDataFilter')
	->add('App\Middleware\Authenticate')
	->add('App\Middleware\Session')
	// ->add('OpenTHC\Middleware\Log\HTTP')
	;


// Config Section
$app->group('/section', 'App\Module\Section')
	->add('App\Middleware\InputDataFilter')
	->add('App\Middleware\Authenticate')
	->add('App\Middleware\Session')
	// ->add('OpenTHC\Middleware\Log\HTTP')
	;


// Inventory Lot
$app->group('/lot', 'App\Module\Lot')
	->add('App\Middleware\InputDataFilter')
	->add('App\Middleware\Authenticate')
	->add('App\Middleware\Session')
	// ->add('OpenTHC\Middleware\Log\HTTP')
	;


// Crop
$app->group('/crop', 'App\Module\Crop')
	->add('App\Middleware\InputDataFilter')
	->add('App\Middleware\Authenticate')
	->add('App\Middleware\Session')
	// ->add('OpenTHC\Middleware\Log\HTTP')
	;


// Crop Collect
$app->group('/crop-collect', 'App\Module\CropCollect')
	->add('App\Middleware\InputDataFilter')
	->add('App\Middleware\Authenticate')
	->add('App\Middleware\Session')
	// ->add('OpenTHC\Middleware\Log\HTTP')
	;


// Lab Samples and Results
$app->group('/lab', 'App\Module\Lab')
	->add('App\Middleware\InputDataFilter')
	->add('App\Middleware\Authenticate')
	->add('App\Middleware\Session')
	// ->add('OpenTHC\Middleware\Log\HTTP')
	;


// B2B
$app->group('/b2b', 'App\Module\B2B')
	->add('App\Middleware\InputDataFilter')
	->add('App\Middleware\Authenticate')
	->add('App\Middleware\Session')
	// ->add('OpenTHC\Middleware\Log\HTTP')
	;


// B2C
$app->group('/b2c', 'App\Module\B2C')
	->add('App\Middleware\InputDataFilter')
	->add('App\Middleware\Authenticate')
	->add('App\Middleware\Session')
	// ->add('OpenTHC\Middleware\Log\HTTP')
	;


// ULID Generator
$app->get('/ulid', 'App\Controller\ULID');


// Common Middleware
// $app->add('App\Middleware\Log\PubSub');
// $app->add('App\Middleware\RateLimit');
// $app->add('App\Middleware\IP');
// $app->add('App\Middleware\TestMode');


// Custom Middleware?
$f = sprintf('%s/Custom/boot.php', APP_ROOT);
if (is_file($f)) {
	require_once($f);
}


// And...go!
$app->run();


exit(0);
