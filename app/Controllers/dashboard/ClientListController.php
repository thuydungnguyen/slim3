<?php

namespace App\Controllers\Dashboard;


use App\Controllers\BaseController;
use App\Models\Client;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ClientListController extends BaseController
{
    public function show(RequestInterface $request, ResponseInterface $response)
    {
        $clients = Client::orderByDesc('created_at')->get();
        return $this->render($response, 'dashboard/clients/clients.twig', ['clients' => $clients]);
    }

}