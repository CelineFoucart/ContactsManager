<?php

namespace Test\Database;

use \App\Model\Builder\QueryBuilder;
use PHPUnit\Framework\TestCase;

class QueryBuilderTest extends TestCase
{
    public function testSimpleQuery()
    {
        $query = new QueryBuilder();
        $expected = "SELECT * FROM posts";
        $actual = $query->from('posts')->toSQL();
        $this->assertEquals($expected, $actual);
    }

    public function testSimpleQueryWithOrder()
    {
        $query = new QueryBuilder();
        $expected = "SELECT * FROM posts ORDER BY name";
        $actual = $query->from('posts')->orderBy('name')->toSQL();
        $this->assertEquals($expected, $actual);
    }

    public function testSimpleQueryAlias()
    {
        $query = new QueryBuilder();
        $expected = "SELECT * FROM posts AS p";
        $actual = $query->from('posts', 'p')->toSQL();
        $this->assertEquals($expected, $actual);
    }

    public function testWithFields()
    {
        $query = new QueryBuilder();
        $expected = "SELECT id, name, content FROM posts";
        $actual = $query->from('posts')->select('id')->select('name')->select('content')->toSQL();
        $this->assertEquals($expected, $actual);
    }

    public function testLimitAndOffset()
    {
        $query = new QueryBuilder();
        $expected = "SELECT * FROM posts LIMIT 5 OFFSET 10";
        $actual = $query->from('posts')->limit(5)->offset(10)->toSQL();
        $this->assertEquals($expected, $actual);
    }

    public function testLimitOrderByAndOffset()
    {
        $query = new QueryBuilder();
        $expected = "SELECT * FROM posts ORDER BY name LIMIT 5 OFFSET 10";
        $actual = $query->from('posts')->limit(5)->offset(10)->orderBy('name')->toSQL();
        $this->assertEquals($expected, $actual);
    }

    public function testCount()
    {
        $query = new QueryBuilder();
        $expected = "SELECT COUNT(*) FROM posts";
        $actual = $query->from('posts')->count();
        $this->assertEquals($expected, $actual);
    }

    public function testCountWithField()
    {
        $query = new QueryBuilder();
        $expected = "SELECT COUNT(id) FROM posts";
        $actual = $query->from('posts')->count('id');
        $this->assertEquals($expected, $actual);
    }

    public function testCountWithCondition()
    {
        $query = new QueryBuilder();
        $expected = "SELECT COUNT(id) FROM posts WHERE name = John";
        $actual = $query->from('posts')->where('name = John')->count('id');
        $this->assertEquals($expected, $actual);
    }

    public function testSimpleWhere()
    {
        $query = new QueryBuilder();
        $expected = "SELECT * FROM posts WHERE id = 1";
        $actual = $query->from('posts')->where('id = 1')->toSQL();
        $this->assertEquals($expected, $actual);
    }

    public function testComplexeWhere()
    {
        $query = new QueryBuilder();
        $expected = "SELECT * FROM posts WHERE (name = :name OR author = :author) AND (created_at = :created)";
        $actual = $query
            ->from('posts')
            ->where('name = :name OR author = :author')
            ->where("created_at = :created")
            ->toSQL();
        $this->assertEquals($expected, $actual);
    }

    public function testJoin()
    {
        $query = new QueryBuilder();
        $expected = "SELECT p.name, p.id, p.slug FROM posts AS p JOIN categories AS c ON p.category = c.id";
        $actual = $query->from('posts', 'p')
            ->select('p.name', 'p.id', 'p.slug')
            ->join('categories AS c', 'p.category = c.id')
            ->toSQL();
        $this->assertEquals($expected, $actual);
    }

    public function testJoinWithType()
    {
        $query = new QueryBuilder();
        $expected = "SELECT p.name, p.id, p.slug FROM posts AS p RIGHT JOIN categories AS c ON p.category = c.id";
        $actual = $query->from('posts', 'p')
            ->select('p.name', 'p.id', 'p.slug')
            ->join('categories AS c', 'p.category = c.id', 'right')
            ->toSQL();
        $this->assertEquals($expected, $actual);
    }

    public function testComplexeQuery()
    {
        $query = new QueryBuilder();
        $expected = "SELECT p.name, p.id, p.slug FROM posts AS p RIGHT JOIN categories AS c ON p.category = c.id WHERE (name = :name OR author = :author) AND (created_at = :created)";
        $actual = $query->from('posts', 'p')
            ->select('p.name', 'p.id', 'p.slug')
            ->join('categories AS c', 'p.category = c.id', 'right')
            ->where('name = :name OR author = :author')
            ->where("created_at = :created")
            ->toSQL();
        $this->assertEquals($expected, $actual);
    }

    public function testInsert()
    {
        $query = new QueryBuilder();
        $expected = "INSERT INTO posts(name, slug, content, author) VALUES(:name, :slug, :content, :author)";
        $actual = $query->from('posts')
            ->select('name', 'slug', 'content', 'author')
            ->value('name', 'slug', 'content', 'author')
            ->toSQL("insert");
        $this->assertEquals($expected, $actual);
    }

    public function testInsertWithAlias()
    {
        $query = new QueryBuilder();
        $expected = "INSERT INTO posts(name, slug, content, author) VALUES(:name, :slug, :content, :author)";
        $actual = $query->from('posts', 'p')
            ->select('name', 'slug', 'content', 'author')
            ->value('name', 'slug', 'content', 'author')
            ->toSQL("insert");
        $this->assertEquals($expected, $actual);
    }
}