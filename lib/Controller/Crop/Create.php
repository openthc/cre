<?php
/**
 * Create a Crop owned by a License
 */

namespace OpenTHC\CRE\Controller\Crop;

class Create extends \OpenTHC\CRE\Controller\Base
{
	use \OpenTHC\Traits\JSONValidator;

	/**
	 *
	 */
	function __invoke($REQ, $RES, $ARG)
	{
		if (empty($_POST['id'])) {
			$_POST['id'] = _ulid();
		}

		// Crop Record
		$obj = array(
			'id' => $_POST['id'],
			'license_id' => $_SESSION['License']['id'],
			'stat' => 200,
		);

		// Section Check

		// Variety Check
		if ( ! empty($_POST['variety'])) {
			if (is_array($_POST['variety'])) {
				$obj['variety_id'] = $_POST['variety']['id'];
			} elseif (is_string($_POST['variety'])) {
				$obj['variety_id'] = $_POST['variety'];
			}
		} elseif ( ! empty($_POST['variety_id'])) {
			$obj['variety_id'] = $_POST['variety_id'];
		}
		if (empty($obj['variety_id'])) {
			// Invalid Request
			// $obj['variety_id'] = '018NY6XC00VAR1ETY000000000';
			return $this->sendError('Invalid Variety [CCC-038]', 400);
		}

		// Find and Auto-Create?
		$v0 = $this->dic->get('DB')->fetchRow('SELECT id, name FROM variety WHERE license_id = :l0 AND id = :v0', [
			':l0' => $_SESSION['License']['id'],
			':v0' => $obj['variety_id']
		]);
		if (empty($v0['id'])) {
			return $this->sendError('Invalid Variety [CCC-046]', 400);
		}

		$rec['meta'] = json_encode($_POST);
		$rec['hash'] = sha1($rec['meta']);

		//$rec = $this->evalObjectScript('Crop/Create', 'Crop', $rec);

		$this->dic->get('DB')->query('BEGIN');
		$this->dic->get('DB')->insert('crop', $rec);
		$this->logAudit('Crop/Create', $rec['id'], $_POST);
		$this->dic->get('DB')->query('COMMIT');

		unset($rec['meta']);

		return $RES->withJSON([
			'meta' => [],
			'data' => $rec,
		], 201);

	}

}
