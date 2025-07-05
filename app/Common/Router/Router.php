<?php declare(strict_types = 1);

// use Contributte\Application\Router\MethodRoute; // not used, left for reference
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;

$router = new RouteList();

// Tariffs REST API
$router[] = new Route('api/v1/tariffs', [
	'module' => 'Api',
	'presenter' => 'Tariff',
	'action' => 'default',
]);
$router[] = new Route('api/v1/tariffs/<code [a-zA-Z0-9_\-]+>', [
	'module' => 'Api',
	'presenter' => 'Tariff',
	'action' => 'detail',
]);

// Customers REST API
$router[] = new Route('api/v1/customers', [
	'module' => 'Api',
	'presenter' => 'Customer',
	'action' => 'default',
]);
$router[] = new Route('api/v1/customers/<id \d+>', [
	'module' => 'Api',
	'presenter' => 'Customer',
	'action' => 'detail',
]);

return $router;
