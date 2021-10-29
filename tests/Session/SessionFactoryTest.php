<?php

namespace Tests\Session;

use App\Session\SessionFactory;
use App\Session\{SessionPHP, FlashService, Auth};
use PHPUnit\Framework\TestCase;

class SessionFactoryTest extends TestCase
{
    public function testGetSession()
    {
        $session1 = SessionFactory::getSession();
        $session2 = SessionFactory::getSession();
        $this->assertTrue($session1 === $session2);
        $this->assertInstanceOf(SessionPHP::class, $session1);
    }

    public function testFactories()
    {
        $this->assertInstanceOf(FlashService::class, SessionFactory::getFlash());
        $this->assertInstanceOf(Auth::class, SessionFactory::getAuth());
    }
}