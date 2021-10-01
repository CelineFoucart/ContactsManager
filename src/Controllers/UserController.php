<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface;

class UserController extends Controller
{
    protected string $viewPath = "../views/users/";

    public function login(ServerRequestInterface $request)
    {
    }

    public function register(ServerRequestInterface $request)
    {
    }

    public function logout(ServerRequestInterface $request)
    {
    }
}
