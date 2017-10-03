<?php

namespace App\Middlewares;

use Slim\Http\Request;
use Slim\Http\Response;

class AuthMiddleware
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, $next)
    {
        if(!isset($_SESSION['user']) || empty($_SESSION['user'])) {
            $_SESSION['flash']['error'] = 'Please sign in !';

            return $response->withRedirect($this->container->router->pathFor('signin'));
        }

        $response = $next($request, $response);
        return $response;
    }

}