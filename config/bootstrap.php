<?php

declare(strict_types=1);

use Nette\Bootstrap\Configurator;


// Nastav výchozí časovou zónu pro celou aplikaci (včetně Tracy loggeru)
date_default_timezone_set('Europe/Prague');

require __DIR__ . '/../vendor/autoload.php';



$configurator = new Configurator();
$configurator->setDebugMode(false);
$configurator->setTempDirectory(__DIR__ . '/../temp');
$configurator->enableTracy(__DIR__ . '/../log');

$configurator->addConfig(__DIR__ . '/common.neon');
if (file_exists(__DIR__ . '/services.neon')) {
    $configurator->addConfig(__DIR__ . '/services.neon');
}

return $configurator->createContainer();
