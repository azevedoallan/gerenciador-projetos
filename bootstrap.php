<?php 

require __DIR__.'/vendor/autoload.php';

$router = new SON\Framework\Router;

require __DIR__ . '/config/containers.php';
require __DIR__. '/config/events.php';
require __DIR__ . '/config/routes.php';

$app = new SON\Framework\App($router, $container);

require __DIR__. '/config/middlewares.php';

$app->run();


