<?php
// front
$app->get('/', \App\Controllers\FrontController::class.':index')->setName('index');
$app->get('/servicii', \App\Controllers\FrontController::class.':service')->setName('service');
$app->get('/servicii-detaliu/{slug}', \App\Controllers\FrontController::class.':serviceDetail')->setName('service_detail');


// admin
$app->group('/admin', function() {
    $this->get('', \App\Controllers\PagesController::class.':home')->setName('home');
    $this->get('/post[/{id}]', \App\Controllers\PagesController::class.':getPost')->setName('post');
    $this->post('/post', \App\Controllers\PagesController::class.':savePost');
    $this->post('/changeStatus', \App\Controllers\PagesController::class.':changeStatus');
    $this->post('/deletePost', \App\Controllers\PagesController::class.':deletePost');
    $this->get('/signout', \App\Controllers\AuthController::class.':getSignOut')->setName('signout');
})->add(new \App\Middlewares\AuthMiddleware($container));


$app->group('/admin', function() use ($container) {
    $this->get('/signin', \App\Controllers\AuthController::class.':getSignIn')->setName('signin')
        ->add(new \App\Middlewares\TwigCsrfMiddleware($container->view->getEnvironment(), $container->csrf));
    $this->post('/signin', \App\Controllers\AuthController::class.':postSignIn');
})->add($container->csrf);
