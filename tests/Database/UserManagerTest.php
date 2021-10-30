<?php

namespace Tests\Database;

use App\Model\Manager\UserManager;
use App\Entity\UserEntity;
use Tests\DatabaseTestCase;

class UserManagerTest extends DatabaseTestCase
{
    public function testFindById()
    {
        $manager = $this->makeManager();
        /**
         * @var UserEntity
         */
        $user = $manager->findById(1);
        $this->assertEquals("Admin", $user->getUsername());
    }

    public function testFind()
    {
        $manager = $this->makeManager();
        $users = $manager->find("id > ?", ["1"], false);
        $this->assertCount(2, $users);
        $this->assertEquals("Nicole", $users[0]->getUsername());
    }

    public function testFindWithOneResult()
    {
        $manager = $this->makeManager();
        $user = $manager->find("email = ?", ["nicole_lewis@dayrep.com"], true);
        $this->assertEquals("Nicole", $user->getUsername());
    }

    public function testFindAll()
    {
        $manager = $this->makeManager();
        $users = $manager->findAll();
        $this->assertCount(3, $users);
    }

    public function testDelete()
    {
        $manager = $this->makeManager();
        $countBeforeDelete = $manager->count();
        $this->assertEquals(3, $countBeforeDelete);
        $manager->delete(3);
        $countAfterDelete = $manager->count();
        $this->assertEquals(2, $countAfterDelete);
    }

    public function testUpdate()
    {
        $manager = $this->makeManager();
        $manager->update(['id'=> 3, 'username'=> 'Demo']);
        $user = $manager->findById(3);
        $this->assertEquals("Demo", $user->getUsername());
    }

    public function testInsert()
    {
        $manager = $this->makeManager();
        $data = [
            'username' => 'John Doe',
            'email' => 'john@dayrep.com',
            'password' => password_hash("123456", PASSWORD_DEFAULT)
        ];
        $id = $manager->insert($data);
        $this->assertEquals(4, (int)$id);
        $user = $manager->findById($id);
        $this->assertEquals($data['username'], $user->getUsername());
        $this->assertEquals($data['email'], $user->getEmail());
        $this->assertTrue(password_verify("123456", $user->getPassword()));
    }

    private function makeManager(): UserManager
    {
        return new UserManager($this->pdo);
    }
    
}