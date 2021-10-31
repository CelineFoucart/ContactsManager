<?php

namespace Tests;

use App\Controllers\Helpers\UserHelper;
use App\Exceptions\NotFoundException;
use App\Exceptions\NotLoggedException;
use App\Model\Manager\UserManager;
use App\Session\Auth;
use App\Tools\Validator;
use Tests\DatabaseTestCase;

class UserHelperTest extends DatabaseTestCase
{
    private UserManager $userManager;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->userManager = new UserManager($this->pdo);
    }

    public function testFindLoggedUserValid()
    {
        $auth = $this->getAuth();
        $user = UserHelper::findLoggedUser($auth, $this->userManager);
        $this->assertTrue($user->getId() === 2);
    }

    public function testFindLoggedUserNotLogged()
    {
        $this->expectException(NotLoggedException::class);
        $auth = $this->getAuth(false, null);
        $user = UserHelper::findLoggedUser($auth, $this->userManager);
    }

    public function testFindLoggedUserNotFound()
    {
        $this->expectException(NotFoundException::class);
        $auth = $this->getAuth(true, 2222222);
        $user = UserHelper::findLoggedUser($auth, $this->userManager);
    }

    public function testEditWithEmailValid()
    {
        $data = ['email' => 'demo@email.fr', 'id' => 2];
        $validator = new Validator($data);
        $errors = UserHelper::edit($data, $validator, $this->userManager);
        $this->assertTrue(empty($errors));
    }

    public function testEditWithPasswordValid()
    {
        $data = ['password' => 'demo12345', 'confirm' => 'demo12345', 'id' => 2];
        $validator = new Validator($data);
        $errors = UserHelper::edit($data, $validator, $this->userManager);
        $this->assertTrue(empty($errors));
    }

    private function getAuth(bool $logged = true, ?int $id = 2)
    {
        $auth = $this->getMockBuilder(Auth::class)->disableOriginalConstructor()->getMock();
        $auth->method('logged')->willReturn($logged);
        $auth->method('getUserId')->willReturn($id); 
        return $auth;
    }
}