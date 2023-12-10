<?php
/**
 * Single
 */

namespace OpenTHC\CRE\Controller\Browse;

class Single extends \OpenTHC\CRE\Controller\Browse\Main
{
	function __invoke($REQ, $RES, $ARG)
	{
		$path = $this->_get_path();

		$data = [];
		$data['path'] = $path;
		$data['table'] = $this->_get_table($path);

		$data['object_id'] = $ARG['id'];

		$sql = <<<SQL
		SELECT *
		FROM %s
		WHERE id = :o1
		SQL;

		$sql = sprintf($sql, $data['table']);
		// var_dump($sql);

		$arg[':o1'] = $data['object_id'];
		// var_dump($arg);

		$dbc = _dbc();
		$res = $dbc->fetchRow($sql, $arg);
		$res['meta'] = json_decode($res['meta'], true);
		$data['object'] = $res;

		$data['License'] = $dbc->fetchRow('SELECT id, name FROM license WHERE id = :l0', [ ':l0' => $data['object']['license_id'] ]);
		$data['Section'] = $dbc->fetchRow('SELECT id, name FROM section WHERE id = :l0', [ ':l0' => $data['object']['section_id'] ]);
		$data['Variety'] = $dbc->fetchRow('SELECT id, name FROM variety WHERE id = :l0', [ ':l0' => $data['object']['variety_id'] ]);
		$data['Product'] = $dbc->fetchRow('SELECT id, name FROM product WHERE id = :l0', [ ':l0' => $data['object']['product_id'] ]);


		$html = $this->render('browse/single.php', $data);

		return $RES->write($html);

	}

}
