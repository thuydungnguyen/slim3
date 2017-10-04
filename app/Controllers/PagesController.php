<?php

namespace App\Controllers;


use App\Models\Image;
use App\Models\Post;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Respect\Validation\Validator as v;

class PagesController extends BaseController
{

    public function home(RequestInterface $request, ResponseInterface $response)
    {
        $postList = Post::all();
        return $this->render($response, 'dashboard/pages/home.twig', ['postList' => $postList]);
    }

    public function getPost(RequestInterface $request, ResponseInterface $response, $args)
    {
        $view = 'dashboard/pages/edit.twig';

        if(isset($args["id"])){
            $post = Post::find($args["id"]);
        }

        if(!isset($post) || !$post){
            $post = new Post();
            $view = 'dashboard/pages/add.twig';
        }

        return $this->render($response, $view, [
            'post'      => $post,
            'zoneList'  => Post::$zones
        ]);
    }

    public function savePost(RequestInterface $request, ResponseInterface $response)
    {
        $status = 400;
        $path = 'post';

        $validation = $this->validate($request, [
            'title'         => v::notEmpty(),
            'description'   => v::notEmpty(),
            'zone'          => v::notEmpty()->noWhitespace(),
            'content'       => v::notEmpty()
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

        $uploadedFile = $request->getUploadedFiles()['image'];

        if(!empty($uploadedFile->file) && $uploadedFile->getError() === UPLOAD_ERR_OK){
            $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);

            if(!in_array($extension, array('png', 'jpg', 'PNG', 'JPG'))){
                $this->setFlash('Image must be of type png ot jpg', 'error');
                return $this->redirect($response, $path, $status, ['id' => $postId]);
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

        $status = 302;
        $path = 'home';
        $message = null != $request->getParam('id') ? "Article has been modified" : "Article has been created";

        $this->setFlash($message);
        return $this->redirect($response, $path, $status);
    }

    public function changeStatus(RequestInterface $request, ResponseInterface $response)
    {
        $post = Post::find($request->getParam('id'));
        $post->is_active = 1 - $post->is_active;

        $data['result'] =  $post->save();
        $data['id'] = $request->getParam('id');

        return $response->withJson($data);

    }

    public function deletePost(RequestInterface $request, ResponseInterface $response)
    {
        $post = Post::find($request->getParam('id'));

        if($post->image !== null){
            $img = Image::find($post->image->id);
            $this->deleteImg($img);
        }

        $data['result'] =  $post->delete();
        $data['id'] = $request->getParam('id');

        return $response->withJson($data);
    }

    public function deleteImg($img)
    {
        $imgName = $img->name;
        $img->delete();
        unlink($this->upload_directory . DIRECTORY_SEPARATOR . $imgName);
    }

}