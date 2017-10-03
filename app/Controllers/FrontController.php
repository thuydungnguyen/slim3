<?php

namespace App\Controllers;

use App\Models\Post;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class FrontController extends BaseController
{
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        return $this->render($response, 'front/sections/index.twig');
    }

    public function service(RequestInterface $request, ResponseInterface $response)
    {
        $postList = Post::where('is_active', 1)->where('zone', 'service')->get();
        return $this->render($response, 'front/sections/service.twig', ['postList' => $postList]);
    }

    public function serviceDetail(RequestInterface $request, ResponseInterface $response, $args)
    {
        $post = Post::where('slug', $args["slug"])->first();
        return $this->render($response, 'front/sections/service_detail.twig', ['post' => $post]);
    }

}