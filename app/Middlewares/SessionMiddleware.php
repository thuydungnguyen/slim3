<?php

namespace App\Middlewares;

use Slim\Http\Request;
use Slim\Http\Response;

class SessionMiddleware {

    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function __invoke(Request $request, Response $response, $next)
    {
        if(isset($_SESSION['user'])) {
            $this->twig->addGlobal('user', $_SESSION['user']);
        }

        $flash = isset($_SESSION['flash']) ? $_SESSION['flash'] : [];
        $this->twig->addGlobal('flash', $flash);

        if(isset($_SESSION['flash'])){
            unset($_SESSION['flash']);
        }

        $oldVal = isset($_SESSION['old']) ? $_SESSION['old'] : [];
        $this->twig->addGlobal('old', $oldVal);

        if(isset($_SESSION['old'])){
            unset($_SESSION['old']);
        }

        $response = $next($request, $response);

        if($response->getStatusCode() === 400) {
            $_SESSION['old'] = $request->getParams();
        }

        return $response;
    }
}