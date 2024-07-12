<?php

// Load the Composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Set error reporting for development
if (isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] === 'development') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

// Start session
session_start();

// Load the application and handle the request
\App\Application\Kernel::create()
    ->withRoutes(__DIR__ . '/../routes/web.php')
    ->withMiddlewares([
        'auth' => \App\Application\Middleware\AuthMiddleware::class,
    ])
    ->run();


