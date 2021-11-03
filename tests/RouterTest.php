<?php

namespace Tests;

use App\Router\Router;
use App\Router\RouterException;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Tests\Helper\DemoController;

class RouterTest extends TestCase
{
    protected Router $router;

    protected function setUp(): void
    {
        $this->router = new Router("Tests\\Helper\\");
    }

    public function testGetValid()
    {
        $this->router->get('/demo', "Demo#index", "demo");
        $body = (new DemoController())->index();
        $request = new ServerRequest('GET', '/demo');
        $response = $this->router->run($request);
        $this->assertEquals($body, $response);
    }

    public function testGetValidWithParamsGet()
    {
        $this->router->get('/demo', "Demo#index", "demo");
        $body = (new DemoController())->index();
        $request = new ServerRequest('GET', '/demo?p=2');
        $response = $this->router->run($request);
        $this->assertEquals($body, $response);
    }

    public function testInvalidGet()
    {
        $this->expectException(RouterException::class);
        $this->router->get('/demo', "Demo#index", "demo");
        $request = new ServerRequest('GET', '/azazae');
        $response = $this->router->run($request);
    }

    public function testPostValid()
    {
        $this->router->get('/demo', "Demo#index", "demo");
        $body = (new DemoController())->index();
        $request = new ServerRequest('POST', '/demo');
        $response = $this->router->run($request);
        $this->assertEquals($body, $response);
    }

    public function testInvalidPost()
    {
        $this->expectException(RouterException::class);
        $this->router->get('/demo', "Demo#index", "demo");
        $request = new ServerRequest('POST', '/azazae');
        $response = $this->router->run($request);
    }

    public function testMatch()
    {
        $this->router->match('/demo', "Demo#index", "demo");
        $requestGET = new ServerRequest('GET', '/demo');
        $requestPOST = new ServerRequest('POST', '/demo');
        $responseGET = $this->router->run($requestGET);
        $responsePOST = $this->router->run($requestPOST);
        $body = (new DemoController())->index();
        $this->assertEquals($body, $responseGET);
        $this->assertEquals($body, $responsePOST);
    }

    public function testGenerateUrl()
    {
        $url = '/demo';
        $this->router->match($url, "Demo#index", "demo");
        $this->assertEquals($url, $this->router->url("demo"));
    }

    public function testGetWithUrlParams()
    {
        $this->router->match('/demo/[i:id]', "Demo#index", "integer");
        $this->router->match('/demo/[a:action]', "Demo#index", "alphanumeric");
        $this->router->match('/demo/posts/[*:slug]-[i:id]', "Demo#index", "post");
        $integer = $this->router->url("integer", ['id'=>3]);
        $this->assertEquals('/demo/3', $integer);
        $alphanumeric = $this->router->url("alphanumeric", ['id'=>3, 'action'=>'edit']);
        $this->assertEquals('/demo/edit', $alphanumeric);
        $post = $this->router->url("post", ['id'=>3, 'slug'=>'lorem-ipsum']);
        $this->assertEquals('/demo/posts/lorem-ipsum-3', $post);
    }

    public function testGetWithInjection()
    {
        $this->expectException(RouterException::class);
        $this->router->match('/demo/[i:id]', "Demo#index", "integer");
        $request = new ServerRequest('GET', "/demo/1'%20or%20'1'%20=%20'1'");
        $response = $this->router->run($request);
    }

    public function testGetValidWithParams()
    {
        $this->router->get('/demo', "Demo#index", "demo");
        $body = (new DemoController())->index();
        $request = new ServerRequest('GET', '/demo?page=2');
        $response = $this->router->run($request);
        $this->assertEquals($body, $response);
    }
}