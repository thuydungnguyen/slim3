<?php

namespace App\Controllers\Front;

use App\Controllers\BaseController;
use App\Models\Post;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Respect\Validation\Validator as v;
v::with('App\\Validation\\Rules\\');

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
        $returnArray['postList'] = Post::where('is_active', 1)->where('zone', 'blog')->orderByDesc('created_at')->get();
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

    public function postContact(RequestInterface $request, ResponseInterface $response)
    {
        $validation = $this->validate($request, [
            'name'          => v::notEmpty(),
            'surname'       => v::notEmpty(),
            'email'         => v::email(),
            'phone'         => v::notEmpty(),
            'message'       => v::notEmpty(),
            'g-recaptcha-response' => v::recaptcha()
        ]);

        if($validation->failed()) {
            $this->setFlash($validation->getErrors(), 'errors');
            return $this->redirect($response, 'contact', 400);
        }

        $messageBody = "Ai primit un mesaj de la " . $request->getParam('surname') . " cu nr de tel: " . $request->getParam('phone') . " cu mesaj: <br/>" . $request->getParam('message');

        $sent = self::sendMail($request, $this->mailer, 'mesaj de contact', $messageBody);

        if($sent == true){
            $this->setFlash("Mesajul dva a fost trimis");
            return $this->redirect($response, 'contact', 302);
        }else{
            $this->setFlash("Error sending mail", "error");
            return $this->redirect($response, 'contact', 400);
        }
    }


}