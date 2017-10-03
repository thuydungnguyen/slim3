<?php

namespace App\Middlewares;


use Slim\Csrf\Guard;
use Slim\Http\Request;
use Slim\Http\Response;

class TwigCsrfMiddleware
{
    private $twig;
    private $csrf;

    public function __construct(\Twig_Environment $twig, Guard $csrf)
    {
        $this->twig = $twig;
        $this->csrf = $csrf;
    }

    public function __invoke(Request $request, Response $response, $next)
    {
        $csrf = $this->csrf;
        $this->twig->addFunction(new \Twig_SimpleFunction('csrf', function() use($csrf, $request)  {
            $nameKey = $csrf->getTokenNameKey();
            $valueKey = $csrf->getTokenValueKey();
            $name = $request->getAttribute($nameKey);
            $value = $request->getAttribute($valueKey);

            return "<input type=\"hidden\" name=\"$nameKey\" value=\"$name\"><input type=\"hidden\" name=\"$valueKey\" value=\"$value\">";
        }));

        $response = $next($request, $response);
        return $response;
    }

}