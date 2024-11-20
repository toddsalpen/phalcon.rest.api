<?php

// Instantiate the controller
$controller = new MyApp\Controllers\RobotsController();

if (isset($app)) {

    /**
     * Route: Create a new robot
     * URL: /robots (POST)
     */
    $app->post(
        '/robot',
        [$controller, 'create']
    );

    /**
     * Route: Get all robots
     * URL: /robots
     */
    $app->get(
        '/robots',
        [$controller, 'getAll']
    );

    /**
     * Route: Get a single robot by ID
     * URL: /robot/{id}
     */
    $app->get(
        '/robot/{id:[0-9]+}',
        [$controller, 'getById']
    );

    /**
     * Route: Search robots by text
     * URL: /robots/{text}
     */
    $app->get(
        '/robots/{text}',
        [$controller, 'search']
    );

    /**
     * Route: Update an existing robot
     * URL: /robot/{id} (PUT)
     */
    $app->put(
        '/robot/{id:[0-9]+}',
        [$controller, 'update']
    );

    /**
     * Route: Delete a robot by ID
     * URL: /robot/{id} (DELETE)
     */
    $app->delete(
        '/robot/{id:[0-9]+}',
        [$controller, 'delete']
    );
}
