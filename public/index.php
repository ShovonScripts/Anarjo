<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Check if running on cPanel from public_html with core in ../anarjo
if (file_exists(__DIR__.'/../anarjo/bootstrap/app.php')) {
    if (file_exists($maintenance = __DIR__.'/../anarjo/storage/framework/maintenance.php')) {
        require $maintenance;
    }
    require __DIR__.'/../anarjo/vendor/autoload.php';
    /** @var Application $app */
    $app = require_once __DIR__.'/../anarjo/bootstrap/app.php';
    $app->usePublicPath(__DIR__);
} else {
    // Default local / standard structure
    if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
        require $maintenance;
    }
    require __DIR__.'/../vendor/autoload.php';
    /** @var Application $app */
    $app = require_once __DIR__.'/../bootstrap/app.php';
}

$app->handleRequest(Request::capture());
