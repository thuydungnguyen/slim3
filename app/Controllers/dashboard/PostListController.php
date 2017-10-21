<?php

namespace App\Controllers\Dashboard;


use App\Controllers\BaseController;
use App\Models\Image;
use App\Models\Post;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class PostListController extends BaseController
{
    public function home(RequestInterface $request, ResponseInterface $response, $args)
    {
        $perPage = 15;
        $totalPosts = Post::count();

        if ($totalPosts > $perPage) {
            $postList = Post::orderByDesc('created_at')->paginate($perPage, ['*'], 'page', $request->getParam('page'));
        } else {
            $postList = Post::orderByDesc('created_at')->get();
        }
        return $this->render($response, 'dashboard/post_list/post_list.twig', ['postList' => $postList]);
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
}