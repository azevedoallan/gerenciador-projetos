<?php 

$composer = require __DIR__.'/vendor/autoload.php';

require __DIR__ . '/config/Modules.php';

$app = new SON\Framework\App($composer, $modules);


$app->run();


