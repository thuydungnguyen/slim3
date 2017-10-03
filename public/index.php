<?php

require '../vendor/autoload.php';

session_start();

$config = [
    'settings' => [
        'displayErrorDetails' => true,
        'db' => [
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => 'slim3',
            'username' => 'root',
            'password' => '',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ]
    ]
];

$app = new \Slim\App($config);

require '../app/container.php';

$app->add(new \App\Middlewares\SessionMiddleware($container->view->getEnvironment()));
//$app->add(new \App\Middlewares\TwigCsrfMiddleware($container->view->getEnvironment(), $container->csrf));

require '../app/rootes.php';

$app->run();