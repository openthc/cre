<?php
/**
 * Main Controller
 */

ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once(dirname(dirname(__FILE__)) . '/boot.php');

$cfg = [];
// $cfg['debug'] = true;
$cfg['settings'] = [];
// $cfg['settings']['routerCacheFile'] = '/tmp/slim-router.cache';

$dic = new \DI\Container();
\Slim\Factory\AppFactory::setContainer($dic);
\Slim\Factory\AppFactory::setResponseFactory(new \OpenTHC\HTTP\Response\Factory());
$app = \Slim\Factory\AppFactory::create();

// Add Error Handler
$errorMiddleware = $app->addErrorMiddleware(
	displayErrorDetails: true,
	logErrors: true,
	logErrorDetails: true
);
$errorMiddleware->setDefaultErrorHandler(function (
		\Psr\Http\Message\ServerRequestInterface $request,
		\Throwable $exception,
		bool $displayErrorDetails
	) use ($app, $cfg) {
		$code = (int)$exception->getCode();
		if ($code < 100 || $code > 599) {
			$code = 500;
		}
		$response = $app->getResponseFactory()->createResponse($code);

		$msg = $exception->getMessage();

		$meta = [
			'note' => $msg,
			'origin' => 'app',
		];

		if (!empty($cfg['debug']) || $displayErrorDetails) {
			$meta['source'] = [
				'code' => $exception->getCode(),
				'file' => $exception->getFile(),
				'line' => $exception->getLine(),
			];
			$meta['stack'] = $exception->getTraceAsString();
		}

		$response->getBody()->write(json_encode([
			'data' => [],
			'meta' => $meta
		], JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE));

		return $response->withHeader('content-type', 'application/json');
});

$errorMiddleware->setErrorHandler(\Slim\Exception\HttpNotFoundException::class, function() use ($app) {
	$response = $app->getResponseFactory()->createResponse(404);
	$response->getBody()->write(json_encode([
		'data' => [],
		'meta' => [ 'note' => 'Not Found [404]' ]
	]));
	return $response->withHeader('content-type', 'application/json');
});

$errorMiddleware->setErrorHandler(\Slim\Exception\HttpMethodNotAllowedException::class, function() use ($app) {
	$response = $app->getResponseFactory()->createResponse(405);
	$response->getBody()->write(json_encode([
		'data' => [],
		'meta' => [ 'note' => 'HTTP Method Not Allowed' ]
	]));
	return $response->withHeader('content-type', 'application/json');
});

// Database
$dic->set('DB', function() {
	return _dbc();
});

// Redis
$dic->set('Redis', function() {
	return _rdb();
});

// Common Middleware
// ->add('OpenTHC\Middleware\Log\HTTP')
// ->add('OpenTHC\CRE\Middleware\Log\PubSub');
// ->add('OpenTHC\CRE\Middleware\RateLimit');
// ->add('OpenTHC\CRE\Middleware\IP');
// ->add('OpenTHC\CRE\Middleware\TestMode');


// Authentication
$app->group('/auth', 'OpenTHC\CRE\Module\Auth')
	;


// Browse Shim
$app->group('/browse', 'OpenTHC\CRE\Module\Browse');
$app->get('/search', 'OpenTHC\CRE\Controller\Search');


// Global Company
$app->group('/company', 'OpenTHC\CRE\Module\Company')
	->add('OpenTHC\CRE\Middleware\InputDataFilter')
	->add('OpenTHC\CRE\Middleware\Authorize')
	->add('OpenTHC\CRE\Middleware\Authenticate')
	;


// Global License
$app->group('/license', 'OpenTHC\CRE\Module\License')
	->add('OpenTHC\CRE\Middleware\InputDataFilter')
	->add('OpenTHC\CRE\Middleware\Authenticate')
	;


// Global Contact
$app->group('/contact', 'OpenTHC\CRE\Module\Contact')
	->add('OpenTHC\CRE\Middleware\InputDataFilter')
	->add('OpenTHC\CRE\Middleware\Authenticate')
	;


/*
 * Company/License Specific Data
 */

// Config Product
$app->group('/product', 'OpenTHC\CRE\Module\Product')
	->add('OpenTHC\CRE\Middleware\InputDataFilter')
	// ->add('OpenTHC\CRE\Middleware\Authorize')
	->add('OpenTHC\CRE\Middleware\Authenticate')
	;


// Config Variety
$app->group('/variety', 'OpenTHC\CRE\Module\Variety')
	->add('OpenTHC\CRE\Middleware\InputDataFilter')
	// ->add('OpenTHC\CRE\Middleware\Authorize')
	->add('OpenTHC\CRE\Middleware\Authenticate')
	;


// Config Section
$app->group('/section', 'OpenTHC\CRE\Module\Section')
	->add('OpenTHC\CRE\Middleware\InputDataFilter')
	// ->add('OpenTHC\CRE\Middleware\Authorize')
	->add('OpenTHC\CRE\Middleware\Authenticate')
	;


// Config Section
$app->group('/vehicle', 'OpenTHC\CRE\Module\Vehicle')
	->add('OpenTHC\CRE\Middleware\InputDataFilter')
	// ->add('OpenTHC\CRE\Middleware\Authorize')
	->add('OpenTHC\CRE\Middleware\Authenticate')
	;


// Inventory
$app->group('/inventory', 'OpenTHC\CRE\Module\Inventory')
	->add('OpenTHC\CRE\Middleware\InputDataFilter')
	// ->add('OpenTHC\CRE\Middleware\Authorize')
	->add('OpenTHC\CRE\Middleware\Authenticate')
	;


// Crop
$app->group('/crop', 'OpenTHC\CRE\Module\Crop')
	->add('OpenTHC\CRE\Middleware\InputDataFilter')
	// ->add('OpenTHC\CRE\Middleware\Authorize')
	->add('OpenTHC\CRE\Middleware\Authenticate')
	;


// Crop Collect
$app->group('/crop-collect', 'OpenTHC\CRE\Module\CropCollect')
	->add('OpenTHC\CRE\Middleware\InputDataFilter')
	// ->add('OpenTHC\CRE\Middleware\Authorize')
	->add('OpenTHC\CRE\Middleware\Authenticate')
	;


// Lab Samples and Results
$app->group('/lab', 'OpenTHC\CRE\Module\Lab')
	->add('OpenTHC\CRE\Middleware\InputDataFilter')
	// ->add('OpenTHC\CRE\Middleware\Authorize')
	->add('OpenTHC\CRE\Middleware\Authenticate')
	;


// B2B
$app->group('/b2b', 'OpenTHC\CRE\Module\B2B')
	->add('OpenTHC\CRE\Middleware\InputDataFilter')
	// ->add('OpenTHC\CRE\Middleware\Authorize')
	->add('OpenTHC\CRE\Middleware\Authenticate')
	;


// B2C
$app->group('/b2c', 'OpenTHC\CRE\Module\B2C')
	->add('OpenTHC\CRE\Middleware\InputDataFilter')
	// ->add('OpenTHC\CRE\Middleware\Authorize')
	->add('OpenTHC\CRE\Middleware\Authenticate')
	;


// ULID Generator
$app->get('/ulid', 'OpenTHC\CRE\Controller\ULID');


// And...go!
$app->run();

exit(0);
