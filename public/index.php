<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

if (getenv('APP_ENV') === 'production' && getenv('DB_CONNECTION') === 'sqlite') {

    $dbPath = getenv('DB_DATABASE') ?: __DIR__ . '/../storage/database.sqlite';


    if ($dbPath && ! file_exists($dbPath)) {
        @mkdir(dirname($dbPath), 0777, true);
        @touch($dbPath);
    }

    $flag = __DIR__ . '/../storage/framework/.sqlite_bootstrapped';
    if (file_exists($dbPath) && ! file_exists($flag)) {
        try {
            $app->make(Illuminate\Contracts\Console\Kernel::class)->call('migrate', [
                '--force' => true,
                '--no-interaction' => true,
            ]);
            @file_put_contents($flag, date('c'));
        } catch (\Throwable $e) {
            error_log('[sqlite bootstrap] ' . $e->getMessage());
        }
    }
}


$app->handleRequest(Request::capture());
