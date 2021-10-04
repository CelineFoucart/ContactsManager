<?php

namespace Tests;

use App\App;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Tests\Helper\DemoController;

class AppTest extends TestCase
{
    protected App $app;
    
    protected function setUp(): void
    {
        $this->app = new App([['get', '/demo', 'Demo#index', 'home']], "Tests\\Helper\\");
    }

    public function testRunWithValidUrl()
    {
        $request = new ServerRequest('GET', '/demo?p=2');
        $body =(new DemoController())->index();
        $response = $this->app->run($request);
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($body, $response->getBody());
    }

    public function testRunWithInvalidUrl()
    {
        $request = new ServerRequest('GET', '/fake');
        $response = $this->app->run($request);
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
    }
    
}