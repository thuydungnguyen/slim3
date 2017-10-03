<?php

namespace App\Controllers;


use App\Models\User;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as v;

class AuthController extends BaseController
{

//    public function postSignUp(Request $request, Response $response)
//    {
//
//        $validation = $this->validate($request, [
//            'email'     => v::noWhitespace()->notEmpty()->email(),
//            'name'      => v::notEmpty()->alpha(),
//            'password'  => v::noWhitespace()->notEmpty()
//        ]);
//
//        if($validation->failed()) {
//            return $this->redirect($response, 'signup');
//        }
//
//
//        $user = new User;
//        $user->create([
//            'email' => $request->getParam('email'),
//            'name'  => $request->getParam('name'),
//            'password'  => password_hash($request->getParam('password'), PASSWORD_DEFAULT)
//        ]);
//
//        return $this->redirect($response, 'home');
//    }

    public function getSignIn(Request $request, Response $response)
    {
        return $this->render($response, 'dashboard/auth/signin.twig');
    }

    public function postSignIn(Request $request, Response $response)
    {
        $name = $request->getParam('name');
        $password = $request->getParam('password');
        $status = 400;
        $path = 'signin';

        $validation = $this->validate($request, [
            'name'      => v::notEmpty()->alpha(),
            'password'  => v::noWhitespace()->notEmpty()
        ]);

        if($validation->failed()) {
            $this->setFlash($validation->getErrors(), 'errors');
            return $this->redirect($response, $path, $status);
        }


        $user = User::where('name', $name)->first();

        if($user && password_verify($password, $user->password)) {
            $this->setFlash("You're logged in");
            $this->setUser($user);
            $status = 302;
            $path = 'home';
        }else{
            $this->setFlash("Name or password are not correct", 'error');
        }

        return $this->redirect($response, $path, $status);

    }

    public function getSignOut(Request $request, Response $response)
    {
        if(isset($_SESSION['user'])) {
            unset($_SESSION['user']);
        }

        return $this->redirect($response, 'signin', 302);
    }

}