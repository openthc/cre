#!/usr/bin/env php
<?php
/**
 * OpenTHC Docker CRE Application Init
 */

_init_config();

// Bootstrap OpenTHC Service
$d = dirname(__DIR__);
require_once("$d/boot.php");
// require_once("$d/vendor/openthc/common/lib/docker.php");

// Wait for Database
$dsn = getenv('OPENTHC_DSN_MAIN');
$dbc_main = _spin_wait_for_sql($dsn);
// echo "SQL Connection: MAIN\n";

$dsn = getenv('OPENTHC_DSN_AUTH');
$dbc_auth = _spin_wait_for_sql($dsn);
// echo "SQL Connection: AUTH\n";

_init_service($dbc_main, $dbc_auth);



// Init Application in this Service
// Add App as an Allowed Service
$dsn = getenv('OPENTHC_DSN_CRE');
$dbc_cre = _spin_wait_for_sql($dsn);

// Create Company
$arg = [];
$arg[':c1'] = getenv('OPENTHC_ROOT_COMPANY_ID');
$sql = <<<SQL
INSERT INTO public.company (id, name, stat, hash)
VALUES (:c1, 'OpenTHC/Demo', 200, '-')
ON CONFLICT (id) DO UPDATE SET
	name = EXCLUDED.name
	, stat = EXCLUDED.stat
SQL;
$dbc_cre->query($sql, $arg);

// Create App Service in cre.service
$arg = [];
$arg[':s1'] = getenv('OPENTHC_APP_ID');
$arg[':c1'] = getenv('OPENTHC_ROOT_COMPANY_ID');
$sql = <<<SQL
INSERT INTO public.service (id, company_id, stat, flag, name, hash)
VALUES (:s1, :c1, 0, 0, 'OpenTHC/Demo/App', '-')
ON CONFLICT (id) DO UPDATE SET
	company_id = EXCLUDED.company_id
	, flag = EXCLUDED.flag
	, stat = EXCLUDED.stat
	, hash = EXCLUDED.hash
SQL;
$dbc_cre->query($sql, $arg);

// Create App Service in cre.auth_service
$arg = [];
$arg[':s1'] = getenv('OPENTHC_APP_ID');
$arg[':c1'] = getenv('OPENTHC_ROOT_COMPANY_ID');
$arg[':pk1'] = getenv('OPENTHC_APP_PUBLIC');
$arg[':sk1'] = getenv('OPENTHC_APP_SECRET');

$sql = <<<SQL
INSERT INTO public.auth_service (id, company_id, stat, flag, name, code, hash)
VALUES (:s1, :c1, 0, 0, 'OpenTHC/Demo/App', :pk1, :sk1)
ON CONFLICT (id) DO UPDATE SET
	company_id = EXCLUDED.company_id
	, code = EXCLUDED.code
	, hash = EXCLUDED.hash
SQL;
$dbc_cre->query($sql, $arg);

// Create POS Service in cre.service
$arg = [];
$arg[':s1'] = getenv('OPENTHC_POS_ID');
$arg[':c1'] = getenv('OPENTHC_ROOT_COMPANY_ID');
$sql = <<<SQL
INSERT INTO public.service (id, company_id, stat, flag, name, hash)
VALUES (:s1, :c1, 0, 0, 'OpenTHC/Demo/POS', '-')
ON CONFLICT (id) DO UPDATE SET
	company_id = EXCLUDED.company_id
	, flag = EXCLUDED.flag
	, stat = EXCLUDED.stat
	, hash = EXCLUDED.hash
SQL;
$dbc_cre->query($sql, $arg);

// Create POS Service in cre.auth_service
$arg = [];
$arg[':s1'] = getenv('OPENTHC_POS_ID');
$arg[':c1'] = getenv('OPENTHC_ROOT_COMPANY_ID');
$arg[':pk1'] = getenv('OPENTHC_POS_PUBLIC');
$arg[':sk1'] = getenv('OPENTHC_POS_SECRET');

$sql = <<<SQL
INSERT INTO public.auth_service (id, company_id, stat, flag, name, code, hash)
VALUES (:s1, :c1, 0, 0, 'OpenTHC/Demo/POS', :pk1, :sk1)
ON CONFLICT (id) DO UPDATE SET
	company_id = EXCLUDED.company_id
	, code = EXCLUDED.code
	, hash = EXCLUDED.hash
SQL;
$dbc_cre->query($sql, $arg);

// INSERT INTO public.service (id, company_id, created_at, updated_at, deleted_at, stat, flag, hash, name, meta)
// VALUES ('010DEM0XXX0000SVC000000APP', '010PENTHC0C0MPANY000000000', '2014-04-20', '2014-04-20', NULL, 200, 0, '-', 'OpenTHC Demo Service', NULL);

// --
// -- Data for Name: auth_service; Type: TABLE DATA; Schema: public; Owner: openthc_cre
// --

// INSERT INTO public.auth_service (id, company_id, stat, flag, code, hash, name)
// VALUES ('010DEM0XXX0000SVC000000APP', '010PENTHC0C0MPANY000000000', 200, 0, '', '', 'OpenTHC Demo Auth_Service');


// Init Contact


// Init Company


// Init License



exit(0);

/**
 *
 */
function _init_service($dbc_main, $dbc_auth)
{
	$arg = [];
	$arg[':s1'] = getenv('OPENTHC_CRE_ID');
	$arg[':c1'] = getenv('OPENTHC_ROOT_COMPANY_ID');
	$arg[':pk1'] = getenv('OPENTHC_CRE_PUBLIC');
	$arg[':sk1'] = getenv('OPENTHC_CRE_SECRET');

	$sql = <<<SQL
	INSERT INTO public.auth_service (id, company_id, created_at, stat, flag, name, code, hash, context_list)
	VALUES (:s1, :c1, '2014-04-20', 200, 0, 'OpenTHC/Demo/CRE', :pk1, :sk1, 'company contact license cre')
	ON CONFLICT (id) DO UPDATE SET
		company_id = EXCLUDED.company_id
		, code = EXCLUDED.code
		, hash = EXCLUDED.hash
	SQL;
	$dbc_auth->query($sql, $arg);

}


/**
 * Create Service Config File
 */
function _init_config()
{
	$cfg = [];

	$cfg['database'] = [
		'cre' => [
			'dsn' => getenv('OPENTHC_DSN_CRE'),
			'hostname' => 'sql',
			'username' => 'openthc_cre',
			'password' => 'openthc_cre',
			'database' => 'openthc_cre',
		],
	];

	// Redis
	$cfg['redis'] = [
		'hostname' => 'rdb',
	];

	// $cfg['statsd'] = [
	// 	'hostname' => '127.0.0.1',
	// 	'host' => '127.0.0.1',
	// 	'port' => 8192,
	// ];

	// OpenTHC Services
	$cfg['openthc'] = [
		'cre' => [
			'origin' => getenv('OPENTHC_CRE_ORIGIN'),
			'public' => getenv('OPENTHC_CRE_PUBLIC'),
			'secret' => getenv('OPENTHC_CRE_SECRET'),
		],
		'dir' => [
			'id' => getenv('OPENTHC_DIR_ID'),
			'origin' => getenv('OPENTHC_DIR_ORIGIN'),
			'public' => getenv('OPENTHC_DIR_PUBLIC'),
		],
		'sso' => [
			'id' => getenv('OPENTHC_SSO_ID'),
			'origin' => getenv('OPENTHC_SSO_ORIGIN'),
			'public' => getenv('OPENTHC_SSO_PUBLIC'),
			'secret' => getenv('OPENTHC_SSO_SECRET'),
		]
	];

	$cfg_data = var_export($cfg, true);
	$cfg_text = sprintf("<?php\n// Generated File\n\nreturn %s;\n", $cfg_data);
	$cfg_file = sprintf('%s/etc/config.php', dirname(__DIR__));

	file_put_contents($cfg_file, $cfg_text);

	return $cfg;

}

/**
 *
 */
function _spin_wait_for_sql(string $dsn)
{

	$try = 0;

	do {

		$try++;

		try {

			$ret = new \Edoceo\Radix\DB\SQL($dsn);

			return $ret;

		} catch (Exception $e) {
			// Ignore
			echo "SQL Failure: ";
			echo $e->getMessage();
			// echo "\n";
			// var_dump($e);
		}

		sleep(4);

	} while ($try < 16);

	throw new \Exception('Failed to connect to database');

	exit(1);
}
