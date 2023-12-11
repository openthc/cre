<?php
/**
 *
 */

$alpha_id = '';
$omega_id = '';

// var_dump($data);

$sql = <<<SQL
SELECT *
FROM %s
WHERE {WHERE}
ORDER BY id
OFFSET 0
LIMIT 100
SQL;

$sql = sprintf($sql, $data['table']);
// var_dump($sql);

$arg = [];
$sql_where = [];
if ( ! empty($_GET['license_id'])) {
	$sql_where[] = sprintf('%s.license_id = :l0', $data['table']);
	$arg[':l0'] = $_GET['license_id'];
}

if ($sql_where) {
	$sql = str_replace('{WHERE}', implode(' AND ', $sql_where), $sql);
} else {
	$sql = str_replace('WHERE {WHERE}', '', $sql);
}
// var_dump($sql);

$dbc = _dbc();
$res = $dbc->fetchAll($sql, $arg);
if (empty($res)) {
	echo '<div class="container">';
	echo '<div class="alert alert-warning">No results</div>';
	echo '</div>';
	return;
}

?>

<div class="container-fluid">

<h1><?= __h($data['name']) ?></h1>

<table class="table table-sm">
<thead class="table-dark">
	<tr>
		<td>ID</td>
		<td>Name</td>
		<td>Created</td>
		<td>Updated</td>
	</tr>
</thead>
<tbody>
<?php
foreach ($res as $rec) {

	$dtC = new \DateTime($rec['created_at']);
	$dtU = new \DateTime($rec['updated_at']);

?>
	<tr>
		<td><a href="/browse/<?= $data['path'] ?>/<?= $rec['id'] ?>"><?= __h($rec['id']) ?></td>
		<td><?= __h($rec['name']) ?></td>
		<td tile="<?= $dtC->format(\DateTimeInterface::RFC3339) ?>"><?= $dtC->format('D m/d H:i') ?></td>
		<td tile="<?= $dtU->format(\DateTimeInterface::RFC3339) ?>"><?= $dtU->format('D m/d H:i') ?></td>
	</tr>
<?php
}
?>
</tbody>
</div>
