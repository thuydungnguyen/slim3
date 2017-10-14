<?php

namespace App\Controllers;

use App\Models\Post;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Respect\Validation\Validator as v;

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
        $returnArray['postList'] = Post::where('is_active', 1)->where('zone', 'blog')->orderByDesc('created_at');
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
        $status = 400;
        $path = 'post';

        $validation = $this->validate($request, [
            'name'          => v::notEmpty(),
            'surname'       => v::notEmpty(),
            'email'         => v::email(),
            'phone'         => v::notEmpty(),
            'message'       => v::notEmpty()
        ]);

        if($validation->failed()) {
            $this->setFlash($validation->getErrors(), 'errors');
            return $this->redirect($response, $path, $status);
        }

        $postId = Post::updateOrCreate(
            ['id'   => $request->getParam('id')],
            [
                'title' => $request->getParam('title'),
                'description' => $request->getParam('description'),
                'zone'  => $request->getParam('zone'),
                'content'  => $request->getParam('content'),
                'slug'      => $this->slugify($request),
                'is_active' => $request->getParam('is_active')
            ]
        )->id;


    }
}