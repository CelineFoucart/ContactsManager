<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface;

class PublicController extends Controller
{
    protected string $viewPath = "../views/public/";

    public function index(ServerRequestInterface $request)
    {
        return $this->render('home', []);
    }

}
