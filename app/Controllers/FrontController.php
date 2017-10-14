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

    public function service(RequestInterface $request, ResponseInterface $response, $args)
    {
        if(isset($args['slug']) && $args['slug'] !== null){
            $returnArray['post'] = Post::where('slug', $args["slug"])->first();
            $returnView = 'front/sections/service_detail.twig';
        }else{
            $returnArray['postList'] = Post::where('is_active', 1)->where('zone', 'service')->get();
            $returnView = 'front/sections/service.twig';
        }

        return $this->render($response, $returnView, $returnArray);
    }

    public function legislative(RequestInterface $request, ResponseInterface $response)
    {
        $returnArray['postList'] = Post::where('is_active', 1)->where('zone', 'blog')->get();
        $returnView = 'front/sections/legislative.twig';

        return $this->render($response, $returnView, $returnArray);
    }

    public function offer(RequestInterface $request, ResponseInterface $response, $args)
    {
        return $this->render($response, 'front/sections/ask_for_offer.twig');
    }

    public function contact(RequestInterface $request, ResponseInterface $response, $args)
    {
        return $this->render($response, 'front/sections/contact.twig');
    }

    public function saveContact(RequestInterface $request, ResponseInterface $response)
    {


    }
}