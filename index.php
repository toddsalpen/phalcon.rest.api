<?php

use Phalcon\Autoload\Loader;
use Phalcon\Mvc\Micro;
use Phalcon\Di\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Mysql as PdoMysql;
use Phalcon\Http\Response;

$loader = new Loader();
$loader->setNamespaces(
    [
        'MyApp\Models' => __DIR__ . '/models/',
    ]
);
$loader->register();

$container = new FactoryDefault();
$container->set(
    'db',
    function () {
        return new PdoMysql(
            [
                'host'     => getenv('DBZ_HOSTPATH'),
                'username' => getenv('DBZ_USERNAME'),
                'password' => getenv('DBZ_PASSWORD'),
                'dbname'   => getenv('DBZ_DATABASE'),
            ]
        );
    }
);

$app = new Micro($container);

$app->get(
    '/robots',
    function () use ($app) {
        try{
            $phql = 'SELECT * '
                . 'FROM MyApp\Models\Robots '
                . 'ORDER BY name'
            ;

            $robots = $app
                ->modelsManager
                ->executeQuery($phql)
            ;

            $data = [];

            foreach ($robots as $robot) {
                $data[] = [
                    'id'   => $robot->id,
                    'name' => $robot->name,
                    'type' => $robot->type,
                    'year'  => $robot->year,
                ];
            }
        echo json_encode($data);
        } catch (\Exception $e) {
            // Handle exceptions gracefully
            echo json_encode([
                'error' => true,
                'message' => $e->getMessage(),
            ]);
        }
    }
);

// Searches for robots with $name in their name
$app->get(
    '/robots/search/{name}',
    function ($name) use ($app) {
        $phql = 'SELECT * '
            . 'FROM MyApp\Models\Robots '
            . 'WHERE name '
            . 'LIKE :name: '
            . 'ORDER BY name'
        ;

        $robots = $app
            ->modelsManager
            ->executeQuery(
                $phql,
                [
                    'name' => '%' . $name . '%'
                ]
            )
        ;

        $data = [];

        foreach ($robots as $robot) {
            $data[] = [
                'id'   => $robot->id,
                'name' => $robot->name,
            ];
        }

        echo json_encode($data);
    }
);

$app->get(
    '/robots/{id:[0-9]+}',
    function ($id) use ($app) {
        $phql = 'SELECT * '
            . 'FROM MyApp\Models\Robots '
            . 'WHERE id = :id:'
        ;

        $robot = $app
            ->modelsManager
            ->executeQuery(
                $phql,
                [
                    'id' => $id,
                ]
            )
            ->getFirst()
        ;

        $response = new Response();
        if ($robot === false) {
            $response->setJsonContent(
                [
                    'status' => 'NOT-FOUND'
                ]
            );
        } else {
            $response->setJsonContent(
                [
                    'status' => 'FOUND',
                    'data'   => [
                        'id'   => $robot->id,
                        'name' => $robot->name
                    ]
                ]
            );
        }

        return $response;
    }
);

$app->post(
    '/robots',
    function () use ($app) {
        $robot = $app->request->getJsonRawBody();
        $phql  = 'INSERT INTO MyApp\ModelsRobots '
            . '(name, type, year) '
            . 'VALUES '
            . '(:name:, :type:, :year:)'
        ;

        $status = $app
            ->modelsManager
            ->executeQuery(
                $phql,
                [
                    'name' => $robot->name,
                    'type' => $robot->type,
                    'year' => $robot->year,
                ]
            )
        ;

        $response = new Response();

        if ($status->success() === true) {
            $response->setStatusCode(201, 'Created');

            $robot->id = $status->getModel()->id;

            $response->setJsonContent(
                [
                    'status' => 'OK',
                    'data'   => $robot,
                ]
            );
        } else {
            $response->setStatusCode(409, 'Conflict');

            $errors = [];
            foreach ($status->getMessages() as $message) {
                $errors[] = $message->getMessage();
            }

            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => $errors,
                ]
            );
        }

        return $response;
    }
);

$app->put(
    '/robots/{id:[0-9]+}',
    function ($id) use ($app) {
        $robot = $app->request->getJsonRawBody();
        $phql  = 'UPDATE MyApp\Models\Robots '
            . 'SET name = :name:, type = :type:, year = :year: '
            . 'WHERE id = :id:';

        $status = $app
            ->modelsManager
            ->executeQuery(
                $phql,
                [
                    'id'   => $id,
                    'name' => $robot->name,
                    'type' => $robot->type,
                    'year' => $robot->year,
                ]
            )
        ;

        $response = new Response();

        if ($status->success() === true) {
            $response->setJsonContent(
                [
                    'status' => 'OK'
                ]
            );
        } else {
            $response->setStatusCode(409, 'Conflict');

            $errors = [];
            foreach ($status->getMessages() as $message) {
                $errors[] = $message->getMessage();
            }

            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => $errors,
                ]
            );
        }

        return $response;
    }
);

$app->delete(
    '/robots/{id:[0-9]+}',
    function ($id) use ($app) {
        $phql = 'DELETE '
            . 'FROM MyApp\Models\Robots '
            . 'WHERE id = :id:';

        $status = $app
            ->modelsManager
            ->executeQuery(
                $phql,
                [
                    'id' => $id,
                ]
            )
        ;

        $response = new Response();

        if ($status->success() === true) {
            $response->setJsonContent(
                [
                    'status' => 'OK'
                ]
            );
        } else {
            $response->setStatusCode(409, 'Conflict');

            $errors = [];
            foreach ($status->getMessages() as $message) {
                $errors[] = $message->getMessage();
            }

            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => $errors,
                ]
            );
        }

        return $response;
    }
);

// Add a Not-Found handler
$app->notFound(
    function () {
        $response = new Phalcon\Http\Response();
        $response->setStatusCode(404, 'Not Found');
        $response->setJsonContent([
            'status'  => 'error',
            'message' => 'The requested endpoint was not found.',
        ]);
        return $response;
    }
);

$app->handle($_SERVER["REQUEST_URI"]);