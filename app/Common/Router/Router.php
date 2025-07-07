<?php declare(strict_types = 1);

use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;

$router = new RouteList();

// Tariffs REST API
$router[] = new Route('api/v1/tariffs', [
	'module' => 'Api',
	'presenter' => 'Tariff',
	'action' => 'default',
]);
$router[] = new Route('api/v1/tariffs/<id \d+>', [
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

// Calculations REST API
$router[] = new Route('api/v1/calculations', [
	'module' => 'Api',
	'presenter' => 'Calculation',
	'action' => 'default',
]);
$router[] = new Route('api/v1/calculations/<id \d+>', [
	'module' => 'Api',
	'presenter' => 'Calculation',
	'action' => 'detail',
]);

return $router;
