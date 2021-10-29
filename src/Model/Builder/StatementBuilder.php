<?php

namespace App\Model\Builder;

use PDO;

class StatementBuilder
{
    private PDO $pdo;

    private $entity;

    public function __construct($entity, PDO $pdo)
    {
        $this->setEntity($entity);
        $this->pdo = $pdo;
    }

    /**
     * Alter data and return the last inserted id
     * 
     * @param string $sql
     * @param array $data
     * @return int
     */
    public function alter(string $sql, array $data): int
    {
        $statement = $this->pdo->prepare($sql);
        $statement->execute($data);
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Make a request and a fetch
     * 
     * @param string $sql
     * @param array $params
     * @param string $mode
     * @return mixed
     */
    public function fetch(string $sql, array $params = [], string $mode = "class")
    {
        if (empty($params)) {
            return $this->execute($sql, $mode)->fetch() ?: null;
        } else {
            return $this->prepare($sql, $params, $mode)->fetch() ?: null;
        }
    }

    /**
     * Make a request and a fetchAll
     * 
     * @param mixed string$sql
     * @param array $params
     * @return mixed
     */
    public function fetchAll(string$sql, array $params = [], string $mode = "class")
    {
        if (empty($params)) {
            return $this->execute($sql, $mode)->fetchAll();
        } else {
            return $this->prepare($sql,$params, $mode)->fetchAll();
        }
    }

    /**
     * Set the value of entity
     *
     * @return  self
     */ 
    public function setEntity($entity): self
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * @return self
     */
    public function unsetEntity(): self
    {
        $this->entity = null;
        return $this;
    }

    /**
     * Define the fetch mode
     * 
     * @param  PDOStatement|false $query
     * @return PDOStatement|false
     */
    private function setFetchMode($query, $mode)
    {
        if($mode === "num") {
            $query->setFetchMode(PDO::FETCH_NUM);
        } elseif($mode === "assoc" || $mode === "array") {
            $query->setFetchMode(PDO::FETCH_ASSOC);
        } else {
            if($this->entity === null) {
                $query->setFetchMode(PDO::FETCH_ASSOC);
            } else {
                $query->setFetchMode(PDO::FETCH_CLASS, $this->entity);
            }
        }
        return $query;
    }

    /**
     * Make a request
     * 
     * @param string $sql
     * @return PDOStatement|false
     */
    private function execute(string $sql, string $mode)
    {
        $query = $this->pdo->query($sql);
        return $this->setFetchMode($query, $mode);
    }

    /**
     * Make a prepared request
     * 
     * @param string $sql
     * @param array $params
     * @return PDOStatement|false
     */
    private function prepare(string $sql, array $params, string $mode)
    {
        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);
        return $this->setFetchMode($statement, $mode);
    }
}