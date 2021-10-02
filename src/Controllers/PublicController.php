<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface;

class PublicController extends Controller
{
    protected string $viewPath = "../views/public/";

    public function index(ServerRequestInterface $request)
    {
        $title = WEBSITE_NAME . " | Gestion de mes contacts";
        return $this->render('home', compact('title'));
    }

}
