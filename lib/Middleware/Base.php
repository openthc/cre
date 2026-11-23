<?php
namespace OpenTHC\CRE\Middleware;

use Psr\Container\ContainerInterface;

class Base
{
	protected $dic;
	function __construct(ContainerInterface $dic)
	{
		$this->dic = $dic;
	}

	function __invoke($REQ, $RES, $NMW)
	{
		return $NMW($REQ, $RES);
	}
}
