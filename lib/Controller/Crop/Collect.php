<?php
/**
 * Add a Collection Record to a Crop
 * Collects Raw(Harvest/Manicure/Wet) Net(Cure/Dry) materials
 *
 *
 */

namespace OpenTHC\CRE\Controller\Crop;

class Collect extends \OpenTHC\CRE\Controller\Base
{
	/**
	 *
	 */
	function __invoke($REQ, $RES, $ARG)
	{
		$dbc = $this->_container->DB;

		if (empty($ARG['id'])) {
			return $RES->withJSON([
				'data' => null,
				'meta' => [
					'note' => 'Argument Not Provided [CPC-017]',
				]
			], 400);
		}

		if (empty($_POST['type'])) {
			return $RES->withJSON([
				'data' => null,
				'meta' => [
					'note' => 'Argument Not Provided [CPC-028]',
				]
			], 400);
		}

		if (!in_array($_POST['type'], [ 'raw', 'wet', 'dry', 'net'])) {
			return $RES->withJSON([
				'data' => null,
				'meta' => [
					'note' => 'Argument Not Valid [CPC-037]',
				]
			], 400);
		}

		if (empty($_POST['uom'])) {
			$_POST['uom'] = 'g';
		}

		$dbc->query('BEGIN');

		$sql = 'SELECT * FROM plant WHERE license_id = :l0 AND id = :pk FOR UPDATE';
		$arg = [
			':l0' => $_SESSION['License']['id'],
			':pk' => $ARG['id']
		];
		$P = $dbc->fetchRow($sql, $arg);
		if (empty($P['id'])) {
			return $this->send404('Crop not found [CPC-052]');
		}

		// Crop Collect
		$PC = [
			'id' => $_POST['plant_collect_id'],
		];
		// Want Specific Plant Collect
		if ( ! empty($PC['id'])) {
			// Lookup
			$chk = $dbc->fetchRow('SELECT * FROM plant_collect WHERE id = ?', [ $PC['id'] ]);
			if (empty($chk['id'])) {
				// Create one w/specified ID
				$dbc->insert('plant_collect', [
					'id' => $PC['id'],
					'license_id' => $_SESSION['License']['id'],
					'hash' => '-',
				]);
			} else {
				if ($chk['license_id'] != $_SESSION['License']['id']) {
					return $this->withJSON([
						'meta' => [ 'note' => 'Plant Collect ID Conflict [CPC#075]' ],
						'data' => null,
					], 409);
				}
			}
		}

		// Create PC?
		if (empty($PC['id'])) {
			$PC = [
				'id' => _ulid(),
				'license_id' => $_SESSION['License']['id'],
				'hash' => '-',
			];
			$dbc->insert('plant_collect', $PC);
			$this->logAudit('Crop/Collect/Create', $PC['id'], $_POST);
		}

		$PCP = [
			'id' => _ulid(),
			'plant_collect_id' => $PC['id'],
			'plant_id' => $P['id'],
			'hash' => '-',
			'type' => $_POST['type'],
			'qty' => max(0, floatval($_POST['qty'])),
			'uom' => $_POST['uom'],
		];
		$dbc->insert('plant_collect_plant', $PCP);

		$sql = <<<SQL
		UPDATE plant_collect
		SET raw = (SELECT sum(qty) FROM plant_collect_plant WHERE plant_collect_id = :pc0 AND type = 'raw')
		    , net = (SELECT sum(qty) FROM plant_collect_plant WHERE plant_collect_id = :pc0 AND type = 'net')
		WHERE plant_collect.id = :pc0
		SQL;
		$arg = [ ':pc0' => $PC['id'] ];
		$dbc->query($sql, $arg);

		$dbc->query('COMMIT');

		$ret = $PC;
		$ret['collect_item'] = $PCP;

		return $RES->withJSON([
			'data' => $ret,
			'meta' => [
				'note' => 'Crop Collect Created',
			]
		], 201);

	}
}
