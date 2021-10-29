<?php

namespace Test\Session;

use App\Session\Auth;
use App\Session\SessionArray;
use PHPUnit\Framework\TestCase;

class AuthTest extends TestCase
{
    protected Auth $auth;

    protected function setUp(): void
    {
        $this->auth = new Auth(new SessionArray([]));
    }

    public function testSession()
    {
        $this->auth->session(1, 2);
        $this->assertEquals(1, $this->auth->getSession()->get('id'));
        $this->assertEquals(2, $this->auth->getSession()->get('auth'));
    }

    public function testLoggedTrue()
    {
        $this->auth->session(1, 2);
        $this->assertTrue($this->auth->logged());
        $this->assertEquals(1, $this->auth->getUserId());
    }

    public function testLogout()
    {
        $this->auth->session(1, 2);
        $this->assertTrue($this->auth->logged());
        $this->auth->logout();
        $this->assertFalse($this->auth->logged());
        $this->assertNull($this->auth->getUserId());
    }

    public function testIsAdmin()
    {
        $this->auth->session(1, 1);
        $this->assertTrue($this->auth->isAdmin());
    }

    public function testIsAdminFalse()
    {
        $this->auth->session(1, 2);
        $this->assertFalse($this->auth->isAdmin());
    }
    
}