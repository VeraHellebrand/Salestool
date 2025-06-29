<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$container = require __DIR__ . '/../config/bootstrap.php';

$container->getByType(Nette\Application\Application::class)
	->run();
