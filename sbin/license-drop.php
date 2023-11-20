#!/usr/bin/php
<?php
/**
 * Drop a License from a Database
 */

require_once(__DIR__ . '/../boot.php');

$opt = getopt('', [
	'license:',
]);

if (empty($opt['license'])) {
	die("No License Provided. Use --license=<ULID>\n");
}
if (empty($opt['source'])) {
	$cfg = OpenTHC\Config::get('database_main');
	$opt['source'] = sprintf('pgsql:host=%s;dbname=%s;user=%s;password=%s', $cfg['hostname'], $cfg['database'], $cfg['username'], $cfg['password']);
}

$dbc_source = new \Edoceo\Radix\DB\SQL($opt['source']);


$tab_list = [
	'b2c_sale_item' => [
		'sql' => 'DELETE FROM %s WHERE b2c_sale_id IN (SELECT id FROM b2c_sale WHERE b2c_sale.license_id = :l0)',
	],
	'b2c_sale' => [],
	'b2b_outgoing_item' => [
		'sql' => 'DELETE FROM %s WHERE b2b_outgoing_id IN (SELECT id FROM b2b_outgoing WHERE license_id_source = :l0)',
	],
	'b2b_outgoing' => [
		'sql' => 'DELETE FROM %s WHERE license_id_source = :l0',
	],
	'b2b_incoming_item' => [
		'sql' => 'DELETE FROM %s WHERE b2b_incoming_id IN (SELECT id FROM b2b_incoming WHERE license_id_target = :l0)',
	],
	'b2b_incoming' => [
		'sql' => 'DELETE FROM %s WHERE license_id_target = :l0',
	],

	'plant_collect_plant' => [
		'sql' => 'DELETE FROM %s WHERE plant_collect_id IN (SELECT id FROM plant_collect WHERE license_id = :l0)',
	],
	'plant_collect' => [],
	'plant' => [],

	'lab_result' => [],
	'inventory' => [],
	// 'lab_result_inventory',
	// 'lab_result_metric',
	// 'log_audit',
	// 'log_delta',
	'product' => [],
	'strain' => [],
	'section' => [],

	// 'contact',
	//'auth_program_secret' => [
	//	'sql' => 'DELETE FROM %s WHERE auth_program_secret.program_id IN (SELECT auth_program.id FROM auth_program WHERE auth_program.company_id IN (SELECT license.company_id FROM license WHERE license.id = :l0))',
	//],
	//'auth_program' => [
	//	'sql' => 'DELETE FROM %s WHERE company_id IN (SELECT company_id FROM license WHERE license.id = :l0)',
	//],
	//'program' => [
	//	'sql' => 'DELETE FROM %s WHERE id IN (SELECT auth_program.id FROM auth_program WHERE auth_program.company_id IN (SELECT license.company_id FROM license WHERE license.id = :l0))',
	//],

	//'auth_contact' => [
	//	'sql' => 'DELETE FROM %s WHERE id IN (SELECT contact.id FROM contact WHERE contact.company_id IN (SELECT company_id FROM license WHERE license.id = :l0))',
	//],

	'license' => [
		'sql' => 'DELETE FROM %s WHERE id = :l0',
	],

];

foreach ($tab_list as $tab_name => $tab_info) {

	$sql = sprintf('DELETE FROM %s WHERE license_id = :l0', $tab_name);
	if (!empty($tab_info['sql'])) {
		$sql = sprintf($tab_info['sql'], $tab_name);
	}

	$arg[':l0'] = $opt['license'];

	echo "sql:$sql\n";

	$dbc_source->query($sql, $arg);
}
