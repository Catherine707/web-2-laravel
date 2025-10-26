<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));


$desired  = getenv('DB_DATABASE') ?: '';
$fallback = '/tmp/sqlite/database.sqlite';
$sqlitePath = $desired !== '' ? $desired : $fallback;

$dir = dirname($sqlitePath);
if (!is_dir($dir)) {
    @mkdir($dir, 0777, true);
}
if (!file_exists($sqlitePath)) {
    @touch($sqlitePath);
}


putenv('DB_CONNECTION=sqlite');
putenv("DB_DATABASE={$sqlitePath}");


if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Autoloader
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';


$flag = '/tmp/_migrated_once.flag';
try {
    if (!file_exists($flag)) {
    
        $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
        $kernel->call('cache:clear');
        $kernel->call('migrate', ['--force' => true, '--no-interaction' => true]);
        @file_put_contents($flag, date('c'));
    }
} catch (\Throwable $e) {

}

$app->handleRequest(Request::capture());