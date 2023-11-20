<?php
/**
 * Main Controller
 */

require_once(dirname(dirname(__FILE__)) . '/boot.php');

$cfg = [];
// $cfg['debug'] = true;
$cfg['settings'] = [];
// $cfg['settings']['routerCacheFile'] = '/tmp/slim-router.cache';

$app = new \OpenTHC\CRE\Core($cfg);
$con = $app->getContainer();

if ($cfg['debug']) {
	unset($con['errorHandler']);
	unset($con['phpErrorHandler']);
	unset($con['notFoundHandler']);
}

$con['response'] = function($c) {
	return new \OpenTHC\HTTP\Response();
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
$app->group('/auth', 'OpenTHC\CRE\Module\Auth')
	->add('OpenTHC\CRE\Middleware\Session')
	// ->add('OpenTHC\Middleware\Log\HTTP')
	;


// Global Company
$app->group('/company', 'OpenTHC\CRE\Module\Company')
	->add('OpenTHC\CRE\Middleware\InputDataFilter')
	->add('OpenTHC\CRE\Middleware\Authenticate')
	->add('OpenTHC\CRE\Middleware\Session')
	// ->add('OpenTHC\Middleware\Log\HTTP')
	;


// Global License
$app->group('/license', 'OpenTHC\CRE\Module\License')
	->add('OpenTHC\CRE\Middleware\InputDataFilter')
	->add('OpenTHC\CRE\Middleware\Authenticate')
	->add('OpenTHC\CRE\Middleware\Session')
	// ->add('OpenTHC\Middleware\Log\HTTP')
	;


// Global Contact
$app->group('/contact', 'OpenTHC\CRE\Module\Contact')
	->add('OpenTHC\CRE\Middleware\InputDataFilter')
	->add('OpenTHC\CRE\Middleware\Authenticate')
	->add('OpenTHC\CRE\Middleware\Session')
	// ->add('OpenTHC\Middleware\Log\HTTP')
	;


/*
 * Company/License Specific Data
 */

// Config Product
$app->group('/product', 'OpenTHC\CRE\Module\Product')
	->add('OpenTHC\CRE\Middleware\InputDataFilter')
	->add('OpenTHC\CRE\Middleware\Authenticate')
	->add('OpenTHC\CRE\Middleware\Session')
	// ->add('OpenTHC\Middleware\Log\HTTP')
	;


// Config Variety
$app->group('/variety', 'OpenTHC\CRE\Module\Variety')
	->add('OpenTHC\CRE\Middleware\InputDataFilter')
	->add('OpenTHC\CRE\Middleware\Authenticate')
	->add('OpenTHC\CRE\Middleware\Session')
	// ->add('OpenTHC\Middleware\Log\HTTP')
	;


// Config Section
$app->group('/section', 'OpenTHC\CRE\Module\Section')
	->add('OpenTHC\CRE\Middleware\InputDataFilter')
	->add('OpenTHC\CRE\Middleware\Authenticate')
	->add('OpenTHC\CRE\Middleware\Session')
	// ->add('OpenTHC\Middleware\Log\HTTP')
	;


// Inventory
$app->group('/inventory', 'OpenTHC\CRE\Module\Inventory')
	->add('OpenTHC\CRE\Middleware\InputDataFilter')
	->add('OpenTHC\CRE\Middleware\Authenticate')
	->add('OpenTHC\CRE\Middleware\Session')
	// ->add('OpenTHC\Middleware\Log\HTTP')
	;


// Crop
$app->group('/crop', 'OpenTHC\CRE\Module\Crop')
	->add('OpenTHC\CRE\Middleware\InputDataFilter')
	->add('OpenTHC\CRE\Middleware\Authenticate')
	->add('OpenTHC\CRE\Middleware\Session')
	// ->add('OpenTHC\Middleware\Log\HTTP')
	;


// Crop Collect
$app->group('/crop-collect', 'OpenTHC\CRE\Module\CropCollect')
	->add('OpenTHC\CRE\Middleware\InputDataFilter')
	->add('OpenTHC\CRE\Middleware\Authenticate')
	->add('OpenTHC\CRE\Middleware\Session')
	// ->add('OpenTHC\Middleware\Log\HTTP')
	;


// Lab Samples and Results
$app->group('/lab', 'OpenTHC\CRE\Module\Lab')
	->add('OpenTHC\CRE\Middleware\InputDataFilter')
	->add('OpenTHC\CRE\Middleware\Authenticate')
	->add('OpenTHC\CRE\Middleware\Session')
	// ->add('OpenTHC\Middleware\Log\HTTP')
	;


// B2B
$app->group('/b2b', 'OpenTHC\CRE\Module\B2B')
	->add('OpenTHC\CRE\Middleware\InputDataFilter')
	->add('OpenTHC\CRE\Middleware\Authenticate')
	->add('OpenTHC\CRE\Middleware\Session')
	// ->add('OpenTHC\Middleware\Log\HTTP')
	;


// B2C
$app->group('/b2c', 'OpenTHC\CRE\Module\B2C')
	->add('OpenTHC\CRE\Middleware\InputDataFilter')
	->add('OpenTHC\CRE\Middleware\Authenticate')
	->add('OpenTHC\CRE\Middleware\Session')
	// ->add('OpenTHC\Middleware\Log\HTTP')
	;


// ULID Generator
$app->get('/ulid', 'OpenTHC\CRE\Controller\ULID');


// Common Middleware
// $app->add('OpenTHC\CRE\Middleware\Log\PubSub');
// $app->add('OpenTHC\CRE\Middleware\RateLimit');
// $app->add('OpenTHC\CRE\Middleware\IP');
// $app->add('OpenTHC\CRE\Middleware\TestMode');


// Custom Middleware?
$f = sprintf('%s/Custom/boot.php', APP_ROOT);
if (is_file($f)) {
	require_once($f);
}


// And...go!
$app->run();

// $rdb = _rdb();
// $rdb->publish('openthc_cre', sprintf('%s %s', $_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']));

exit(0);
