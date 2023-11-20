<?php
/**
 * Home
 */

namespace OpenTHC\CRE\Controller;

class Home extends \OpenTHC\CRE\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		$text = file_get_contents(APP_ROOT . '/README.md');
		$html = _markdown($text);

		$data = [
			'Page' => [ 'title' => 'OpenTHC :: CRE' ],
			'Content' => $html,
		];

		$path = sprintf('%s/webroot/test-output', APP_ROOT);
		if (is_dir($path)) {
			$data['test_output'] = true;
		}

		$file = 'page/index.html';

		return $this->_container->view->render($RES, $file, $data);

	}
}
