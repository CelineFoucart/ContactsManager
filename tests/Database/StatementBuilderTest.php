<?php

namespace Test\Database;

use App\Model\Builder\StatementBuilder;
use PHPUnit\Framework\TestCase;
use Tests\Database\helpers\Entity;
use Tests\Database\helpers\DatabaseFake;

class StatementBuilderTest extends TestCase
{
    protected $builder;

    protected function setUp(): void
    {     
        $this->builder = new StatementBuilder(Entity::class, DatabaseFake::getPdo()); 
    }

    public function testFetchWithClass()
    {
        $result = $this->builder->fetch("SELECT * FROM posts WHERE id = 1");
        $this->assertInstanceOf(Entity::class, $result);
        $this->assertEquals("post 1", $result->name);
    }

    public function testFetchWithClassAndParams()
    {
        $result = $this->builder->fetch("SELECT * FROM posts WHERE id = :id", ['id'=> 1]);
        $this->assertInstanceOf(Entity::class, $result);
        $this->assertEquals("post 1", $result->name);
    }

    public function testFetchAllWithClass()
    {
        $results = $this->builder->fetchAll("SELECT * FROM posts");
        $this->assertInstanceOf(Entity::class, $results[0]);
        $this->assertEquals("post 1", $results[0]->name);
    }

    public function testFetchAllWithClassAndParams()
    {
        $results = $this->builder->fetchAll("SELECT * FROM posts WHERE id < :id", ['id' => 5]);
        $this->assertCount(4, $results);
        $this->assertInstanceOf(Entity::class, $results[1]);
        $this->assertEquals("post 1", $results[0]->name);
    }

    public function testWithArray()
    {
        $result = $this->builder->fetch("SELECT * FROM posts WHERE id = 1", [], "array");
        $this->assertIsArray($result);
        $this->assertArrayHasKey('name', $result);
    }

    public function testWithNumArray()
    {
        $result = $this->builder->fetch("SELECT COUNT(*) FROM posts", [], 'num')[0];
        $this->assertEquals(10, (int)$result);
    }

    public function testUnsetEntity()
    {
        $builder = clone $this->builder;
        $builder->unsetEntity();
        $result1 = $builder->fetch("SELECT * FROM posts WHERE id = 1");
        $result2 = $builder->fetch("SELECT * FROM posts WHERE id = 1", [], "class");
        $this->assertArrayHasKey('name', $result1);
        $this->assertArrayHasKey('name', $result2);
    }
    
}