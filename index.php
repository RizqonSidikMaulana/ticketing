<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/Config/Application.php';

use App\Config\Application;

$app = new Application();
$router = new AltoRouter();
$app->run($router);