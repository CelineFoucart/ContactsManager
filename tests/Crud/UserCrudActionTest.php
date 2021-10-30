<?php

namespace Tests\Crud;

use App\Crud\UserCrudAction;
use App\Model\Manager\UserManager;
use App\Session\FlashService;
use App\Session\SessionArray;
use App\Tools\Validator;
use Tests\DatabaseTestCase;

class UserCrudActionTest extends DatabaseTestCase
{
    private UserManager $userManager;
    private FlashService $flash;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userManager = new UserManager($this->pdo);
        $this->flash = new FlashService(new SessionArray([]));
    }

    public function testLoginValid()
    {
        $data = [
            'username' => 'Admin',
            'password' => 'admin'
        ];
        $validator = new Validator($data);
        $crud = new UserCrudAction($this->userManager, $this->flash, $validator);
        $id = $crud->login($data);
        $errors = $crud->getValidator()->getErrors();
        $this->assertTrue($id === 1);
        $this->assertEmpty($errors);
    }

    public function testLoginInvalid()
    {
        $data = [
            'username' => 'Admin',
            'password' => 'aze'
        ];
        $validator = new Validator($data);
        $crud = new UserCrudAction($this->userManager, $this->flash, $validator);
        $id = $crud->login($data);
        $errors = $crud->getValidator()->getErrors();
        $this->assertNull($id);
        $this->assertEmpty($errors);
    }

    public function testLoginInvalidField()
    {
        $data = [
            'username' => 'Admin'
        ];
        $validator = new Validator($data);
        $crud = new UserCrudAction($this->userManager, $this->flash, $validator);
        $status = $crud->login($data);
        $errors = $crud->getValidator()->getErrors();
        $this->assertNull($status);
        $this->assertNotEmpty($errors);
        $this->assertArrayHasKey('password', $errors);
    }

    public function testRegisterValid()
    {
        $data = [
            'username' => 'John Doe',
            'email' => 'john@dayrep.com',
            'password' => "demo123456",
            'confirm' => "demo123456"
        ];
        $validator = new Validator($data);
        $crud = new UserCrudAction($this->userManager, $this->flash, $validator);
        $id = $crud->register($data);
        $errors = $crud->getValidator()->getErrors();
        $this->assertTrue($id === 4);
        $this->assertEmpty($errors);
    }

    public function testRegisterInvalid()
    {
        $data = [
            'username' => 'John Doe',
            'email' => 'john@dayrep.com',
            'password' => "demo",
            'confirm' => "aze"
        ];
        $validator = new Validator($data);
        $crud = new UserCrudAction($this->userManager, $this->flash, $validator);
        $id = $crud->register($data);
        $errors = $crud->getValidator()->getErrors();
        $this->assertNull($id);
        $this->assertNotEmpty($errors);
        $this->assertArrayHasKey('password', $errors);
        $this->assertArrayHasKey('confirm', $errors);
    }
}