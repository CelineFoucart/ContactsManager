<?php 

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface;

class ContactController extends Controller
{
    protected string $viewPath = "../views/contact/";

    public function list(ServerRequestInterface $request)
    {
    }

    public function show(ServerRequestInterface $request)
    {
    }
}