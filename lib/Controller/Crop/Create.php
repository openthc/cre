<?php
/**
 * Create a Crop owned by a License
 */

namespace App\Controller\Crop;

class Create extends \App\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		if (empty($_POST['id'])) {
			$_POST['id'] = _ulid();
		}

		// Crop Record
		$rec = array(
			'id' => $_POST['id'],
			'license_id' => $_ENV['license_id'],
			// 'variety_id' => $_POST['variety'],
			// 'section_id' => $_POST['section'],
			'stat' => 200,
		);

		// @todo maybe we should do this validation before we introduce $rec, it confusing /mbw
		if (!empty($_POST['variety'])) {
			if (is_array($_POST['variety'])) {
				if (!empty($_POST['variety']['id'])) {
					$rec['variety_id'] = $_POST['variety']['id'];
				}
			} else {
				$rec['variety_id'] = $_POST['variety'];
			}
		}

		if (!empty($_POST['section'])) {
			if (is_array($_POST['section'])) {
				if (!empty($_POST['section']['id'])) {
					$rec['section_id'] = $_POST['section']['id'];
				}
			} else {
				$rec['section_id'] = $_POST['section'];
			}
		}

		$rec['meta'] = json_encode($_POST);
		$rec['hash'] = sha1($rec['meta']);

		//$rec = $this->evalObjectScript('Crop/Create', 'Crop', $rec);

		$this->_container->DB->query('BEGIN');
		$this->_container->DB->insert('plant', $rec);
		$this->logAudit('Crop/Create', $rec['id'], $_POST);
		$this->_container->DB->query('COMMIT');

		unset($rec['meta']);

		return $RES->withJSON([
			'meta' => [],
			'data' => $rec,
		], 201);

	}
}
