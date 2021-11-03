<?php

namespace App\Router;

use Psr\Http\Message\ServerRequestInterface;

class Router
{
        
    /**
     * @var AltoRouter
     */
    private $router;

    private string $namespace;
    
    public function __construct(string $namespace = "\\App\\Controllers\\")
    {
        $this->router = new \AltoRouter();
        $this->namespace = $namespace;
    }
    
    /**
     * @param  mixed $url         the url
     * @param  mixed $controller  a path to the controller and the method like Post#index
     * @param  mixed $name        the path name
     * @return self
     */
    public function get(string $url, string $controller, ?string $name = null): self
    {
        $this->router->map('GET', $url, $controller, $name);

        return $this;
    }

    /**
     * @param  mixed $url         the url
     * @param  mixed $controller  a path to the controller and the method like Post#index
     * @param  mixed $name        the path name
     * @return self
     */
    public function post(string $url, string $controller, ?string $name = null): self
    {
        $this->router->map('POST', $url, $controller, $name);

        return $this;
    }

    /**
     * @param  mixed $url         the url
     * @param  mixed $controller  a path to the controller and the method like Post#index
     * @param  mixed $name        the path name
     * @return self
     */
    public function match(string $url, string $controller, ?string $name = null): self
    {
        $this->router->map('POST|GET', $url, $controller, $name);

        return $this;
    }

    /**
     * @param ServerRequestInterface $request
     * 
     * @return string|Response
     */
    public function run(ServerRequestInterface $request)
    {
        $match = $this->router->match($request->getUri()->getPath());
        $router = $this;
        if ($match === false) {
            throw new RouterException("Cette url n'est pas valide");
        } else {
            foreach ($match['params'] as $key => $value) {
                $request = $request->withAttribute($key, $value);
            }
            $action = explode('#', $match['target']);
            if (preg_match("#Admin#", $action[0])) {
                $controller = $this->namespace . "Admin\\" . $action[0] . 'Controller';
            } else {
                $controller = $this->namespace . $action[0] . 'Controller';
            }
            $controller = new $controller($router);
            $method = $action[1];
            return $controller->$method($request);
        }
    }

    public function url(string $name, array $params = [])
    {
        return $this->router->generate($name, $params);
    }
}
