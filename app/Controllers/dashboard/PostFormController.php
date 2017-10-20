<?php

namespace App\Controllers\Dashboard;


use App\Controllers\BaseController;
use App\Models\Image;
use App\Models\Post;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Respect\Validation\Validator as v;

class PostFormController extends BaseController
{
    public function getPost(RequestInterface $request, ResponseInterface $response, $args)
    {
        $view = 'dashboard/post_form/edit.twig';

        if(isset($args["id"])){
            $post = Post::find($args["id"]);
        }

        if(!isset($post) || !$post){
            $post = new Post();
            $view = 'dashboard/post_form/add.twig';
        }

        return $this->render($response, $view, [
            'post'      => $post,
            'zoneList'  => Post::$zones
        ]);
    }

    public function savePost(RequestInterface $request, ResponseInterface $response)
    {
        $rules = [
            'title'         => v::notEmpty(),
            'zone'          => v::notEmpty()->noWhitespace(),
            'content'       => v::notEmpty()
        ];

        if($request->getParam('zone') !== 'blog'){
            $rules['description'] = v::notEmpty();
        }

        $validation = $this->validate($request, $rules);
        if($validation->failed()) {
            $this->setFlash($validation->getErrors(), 'errors');
            return $this->redirect($response, 'post', 400);
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

        $uploadedFile = $request->getUploadedFiles()['image'];

        if(!empty($uploadedFile->file) && $uploadedFile->getError() === UPLOAD_ERR_OK){
            $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);

            if(!in_array($extension, array('png', 'jpg', 'PNG', 'JPG'))){
                $this->setFlash('Image must be of type png or jpg', 'error');
                return $this->redirect($response, 'post', 400, ['id' => $postId]);
            }

            $existImg = Image::where('post_id', $postId)->first();
            if($existImg){
                $this->deleteImg($existImg);
            }

            $filename = parent::moveUploadedFile($this->upload_directory, $uploadedFile);

            Image::create([
                'post_id'   => $postId,
                'name'      => $filename
            ]);
        }

        $message = null != $request->getParam('id') ? "Article has been modified" : "Article has been created";
        $this->setFlash($message);

        return $this->redirect($response, 'home', 302);
    }

}