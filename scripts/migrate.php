<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Dibi\Connection;

$db = new Connection([
	   'driver' => 'sqlite3',
	   'database' => __DIR__ . '/../database/database.sqlite',
]);

$migrationDir = __DIR__ . '/../migrations';
$files = glob($migrationDir . '/*.sql');
sort($files);

foreach ($files as $file) {
	$sql = file_get_contents($file);
	$db->nativeQuery($sql);
	echo "Migrace $file byla úspěšně provedena.\n";
}

echo "Všechny migrace proběhly.\n";
