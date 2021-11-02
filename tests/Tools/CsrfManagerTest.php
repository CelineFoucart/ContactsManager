<?php

namespace Tests\Tools;

use App\Session\SessionArray;
use App\Tools\Csrf\CsrfInvalidException;
use App\Tools\Csrf\CsrfManager;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Psr7\ServerRequest;

class CsrfManagerTest extends TestCase
{
    private CsrfManager $csrfManager;

    protected function setUp(): void
    {
        $this->csrfManager = new CsrfManager(new SessionArray());
    }

    public function testGetRequest()
    {
        $request = new ServerRequest('GET', '/demo');
        $status = $this->csrfManager->process($request);
        $this->assertTrue($status);
    }

    public function testBlockPostRequestWithoutCsrf()
    {
        $request = new ServerRequest('POST', '/demo');
        $this->expectException(CsrfInvalidException::class);
        $this->csrfManager->process($request);
    }

    public function testPostRequestWithToken()
    {
        $request = new ServerRequest('POST', '/demo');
        $token = $this->csrfManager->generateToken();
        $request = $request->withParsedBody(['_csrf' => $token]);
        $status = $this->csrfManager->process($request);
        $this->assertTrue($status);
    }

    public function testBlockPostRequestWithInvalidToken()
    {
        $request = new ServerRequest('POST', '/demo');
        $this->expectException(CsrfInvalidException::class);
        $this->csrfManager->generateToken();
        $request->withParsedBody(['_csrf' => 'azaezaze']);
        $this->csrfManager->process($request);
    }

    public function testBlockPostRequestWithTokenOnce()
    {
        $request = new ServerRequest('POST', '/demo');
        $token = $this->csrfManager->generateToken();
        $request = $request->withParsedBody(['_csrf' => $token]);
        $this->csrfManager->process($request);
        $this->expectException(CsrfInvalidException::class);
        $this->csrfManager->process($request);
    }
    
}