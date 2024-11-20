<?php

// Set the folders loader for the app
require_once __DIR__ . '/config/loader.php';

// Dependency Injection Container
require_once __DIR__ . '/config/services.php';

if (isset($container)) {
    $app = new Phalcon\Mvc\Micro($container);
}

//Headers and Content Type validation
require_once __DIR__ . '/validations/content_type.php';

// Include Routes
require_once __DIR__ . '/routes/robots.php';
require_once __DIR__ . '/routes/default.php';

$app->handle($_SERVER["REQUEST_URI"]);