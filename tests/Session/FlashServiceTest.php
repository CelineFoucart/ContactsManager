<?php

namespace Tests\Session;

use App\Session\FlashService;
use App\Session\SessionArray;
use PHPUnit\Framework\TestCase;

class FlashServiceTest extends TestCase
{
    protected FlashService $flash;
    protected SessionArray $session;
    
    protected function setUp(): void
    {
        $this->session = new SessionArray();
        $this->flash = new FlashService($this->session);
    }

    public function testReturnNullIfNoFlash()
    {
        $this->assertNull($this->flash->get('success'));
    }

    public function testFlashDeleteAfterGettingMessage()
    {
        $this->flash->success('Bravo');
        $this->assertEquals('Bravo', $this->flash->get('success'));
        $this->assertNull($this->session->get('flash'));
        $this->assertEquals('Bravo', $this->flash->get('success'));
    }
    
}