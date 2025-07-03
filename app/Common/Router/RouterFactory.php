<?php declare(strict_types = 1);

namespace Common\Router;

use Nette\Application\Routers\RouteList;

class RouterFactory
{

	public static function createRouter(): RouteList
	{
		return require __DIR__ . '/Router.php';
	}

}
