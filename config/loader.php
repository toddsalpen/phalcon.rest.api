<?php

$loader = new Phalcon\Autoload\Loader();
$loader->setNamespaces(
    [
        'MyApp\Models' => __DIR__ . '/../models/',
        'MyApp\Controllers' => __DIR__ . '/../controllers/',
    ]
)->register();