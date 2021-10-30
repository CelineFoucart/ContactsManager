<?php

namespace Tests\Crud;

use App\Crud\CrudAction;
use App\Model\Manager\UserManager;
use Tests\DatabaseTestCase;

class CrudActionTest extends DatabaseTestCase
{
    private UserManager $userManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userManager = new UserManager($this->pdo);
    }

    public function testInsertWithoutValidator()
    {
        $data = [
            'username' => 'John Doe',
            'email' => 'john@dayrep.com',
            'password' => password_hash("123456", PASSWORD_DEFAULT)
        ];
        $crud = new CrudAction($this->userManager);
        $id = $crud->insert($data);
        $this->assertNotNull($id);
        $this->assertEquals(4, (int)$this->userManager->count());
    }

    public function testUpdate()
    {
        $data = [
            'username' => 'John Doe',
            'email' => 'john@dayrep.com',
            'id' => 2
        ];
        $crud = new CrudAction($this->userManager);
        $id = $crud->update($data);
        $this->assertNotNull($id);
        $user = $this->userManager->findById(2);
        $this->assertEquals($data['username'], $user->getUsername());
        $this->assertEquals($data['email'], $user->getEmail());
    }

    public function testDelete()
    {
        $crud = new CrudAction($this->userManager);
        $status = $crud->delete(3);
        $this->assertTrue($status);
        $user = $this->userManager->findById(3);
        $this->assertNull($user);
    }

    // test liste avec ContactManager
}