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

	function __invoke($REQ, $handler)
	{
		return $handler->handle($REQ);
	}
}
