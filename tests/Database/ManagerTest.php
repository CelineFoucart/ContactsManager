<?php

namespace Tests\Database;

use App\Model\Exception\SqlException;
use App\Model\Manager\Manager;
use PHPUnit\Framework\TestCase;
use Tests\Database\helpers\DatabaseFake;

class ManagerTest extends TestCase
{
    protected function makeManager(): Manager
    {
        $pdo = DatabaseFake::getPdo();
        $pdo->exec('CREATE TABLE posts(
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(255)
        )');
        for ($i = 1; $i <= 10; $i++) {
            $pdo->exec("INSERT INTO posts(name) VALUES('post $i')");
        }
        return new Manager($pdo);
    }

    public function testFindAll()
    {
        $manager = ($this->makeManager())->setTable('posts');
        $results = $manager->findAll();
        $this->assertCount(10, $results);
    }

    public function testFindWithOnly()
    {
        $manager = ($this->makeManager())->setTable('posts');
        $result = $manager->find("id = :id", ['id'=> 1], true);
        $this->assertArrayHasKey('name', $result);
    }

    public function testFindWithoutOnly()
    {
        $manager = ($this->makeManager())->setTable('posts');
        $results = $manager->find("id <= :id", ['id' => 4], false);
        $this->assertCount(4, $results);
    }

    public function testCount()
    {
        $manager = ($this->makeManager())->setTable('posts');
        $result = $manager->count();
        $this->assertEquals(10, $result);
    }

    public function testCountWithWhere()
    {
        $manager = ($this->makeManager())->setTable('posts');
        $result = $manager->count("id <= :id", ['id' => 4]);
        $this->assertEquals(4, $result);
    }

    public function testException()
    {
        $manager = $this->makeManager();
        $this->expectException(SqlException::class);
        $manager->findAll();
    }
    
}