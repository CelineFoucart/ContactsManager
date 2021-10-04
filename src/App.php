<?php

declare(strict_types=1);

namespace App;

use App\Exception\NotLoggedException;
use App\Router\Router;
use App\Router\RouterException;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class App
{
    /**
     * @var array Valids urls
     */
    private array $urls = [];
    /**
     * @var Router
     */
    private Router $router;
    /**
     * @var string controllers namspace
     */
    private string $controllerNamespace;
    
    public function __construct(array $urls, string $namespace = "\\App\\Controllers\\")
    {
        $this->urls = $urls;
        $this->controllerNamespace = $namespace;
        $this->makeRouter();
    }

    /**
     * Renvoie la réponse
     * 
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function run(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $response = $this->router->run($request);
            if ($response instanceof ResponseInterface) {
                return $response;
            }
            return new Response(200, [], $response);
        } catch (NotLoggedException $error) {
            return new Response(301, [], $error->getMessage());
        } catch(RouterException $error) {
            return new Response(404, [], $error->getMessage());
        }
    }

    /**
     * Hydrate le router et génère les urls valides
     * 
     * @return self
     */
    protected function makeRouter(): self
    {
        $this->router = new Router($this->controllerNamespace);
        foreach ($this->urls as $url) {
            $method = $url[0];
            $path = $url[1];
            $controller = $url[2];
            $routeName = $url[3];
            $this->router->$method($path, $controller, $routeName);
        }
        return $this;
    }
}