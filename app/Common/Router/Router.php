<?php declare(strict_types = 1);

// use Contributte\Application\Router\MethodRoute; // not used, left for reference
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;

$router = new RouteList();

// Tariffs REST API


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

// Other API endpoints (GET only)
$router[] = new Route('api/v1/addresses', 'Api:Address:default');
$router[] = new Route('api/v1/addresses/<id>', 'Api:Address:detail');
$router[] = new Route('api/v1/customers', 'Api:Customer:default');
$router[] = new Route('api/v1/customers/<id>', 'Api:Customer:detail');
$router[] = new Route('api/v1/calculations', 'Api:Calculation:default');

return $router;
