<?php
$container = new Phalcon\Di\FactoryDefault();
$container->set(
    'db',
    function () {
        return new Phalcon\Db\Adapter\Pdo\Mysql(
            [
                'host'     => getenv('DBZ_HOSTPATH'),
                'username' => getenv('DBZ_USERNAME'),
                'password' => getenv('DBZ_PASSWORD'),
                'dbname'   => getenv('DBZ_DATABASE'),
            ]
        );
    }
);
