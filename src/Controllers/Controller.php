<?php

namespace App\Controllers;

use App\Router\Router;

abstract class Controller
{
    protected Router $router;

    protected string $viewPath;

    protected string $template = "../views/layout";

    public function __construct(Router $router)
    {
        $this->router = $router;
        if(!defined('WEBSITE_NAME')) {
            define('WEBSITE_NAME', 'MyWebsite');
        }
    }
    
    protected function render(string $view, array $params = []): string
    {
        $router = $this->router;
        extract($params);
        ob_start();
        require $this->viewPath . $view . '.php';
        $content = ob_get_clean();
        ob_start();
        require $this->template . '.php';
        $page = ob_get_clean();
        return $page;
    }
}