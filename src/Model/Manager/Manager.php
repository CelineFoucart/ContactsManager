<?php 

namespace App\Model\Manager;

use App\Model\Builder\QueryBuilder;
use App\Model\Builder\StatementBuilder;
use \PDO;
use App\Model\Exception\SqlException;

class Manager
{

    protected PDO $pdo;
    protected ?string $class = null;
    protected ?string $table = null;
    protected ?string $prefix = null;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Throw a SqlException
     */
    protected function getException(): void
    {
        if ($this->table === null) {
            throw new SqlException('the property $table of manager cannot be empty');
        }
    }

    /**
     * Return the result as an array of objects or associated arrays
     */
    public function findAll(?string $sql = null): array
    {
        $sql = ($sql === null) ? $this->getPublicQuery()->toSQL() : $sql;  
        return $this->makeBuilder()->fetchAll($sql);
    }

    /**
     * @param string|null $orderBy
     * @param int         $limit
     * @param int         $offset
     * @param string|null $where
     * @param array       $params
     * 
     * @return array
     */
    public function findPaginated(
        ?string $orderBy = null,
        int $limit = 30, 
        int $offset = 0, 
        ?string $where = null, 
        array $params = []
    ): array
    {
        $sql = $this->getPublicQuery()->limit($limit)->offset($offset);
        if($orderBy !== null) {
            $sql = $sql->unsetOrderBy()->orderBy($orderBy);
        }
        if($where !== null) {
            $sql->where($where);
            $sql = $sql->toSQL();
            return $this->makeBuilder()->fetchAll($sql, $params);
        } else {
            $sql = $sql->toSQL();
            return $this->findAll($sql);
        }
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function findById(int $id)
    {
        $sql = $this->getPublicQuery()->where("{$this->prefix}id = ?")->toSQL();
        return $this->makeBuilder()->fetch($sql, [$id]);
    }

    /**
     * Return the result of a prepared request or null
     *
     * @param  string $where
     * @param  string $params
     * @param  bool   $only
     */
    public function find(string $where, array $params, bool $only = false)
    {
        $sql = $this->getPublicQuery()->where($where)->toSQL();
        if ($only) {
            return $this->makeBuilder()->fetch($sql, $params);
        } else {
            return $this->makeBuilder()->fetchAll($sql, $params);
        }
    }

    /**
     * Count data in the table
     * 
     * @param null|string $where
     * @param array  $params
     * @return int
     */
    public function count(?string $where = null, array $params = []): int
    {
        if ($where === null) {
            $sql = $this->makeQuery()->count($this->prefix . 'id');
        } else {
            $sql = $this->makeQuery()->where($where)->count($this->prefix . 'id');
        }
        return (int)$this->makeBuilder()->fetch($sql, $params, 'num')[0];
    }

    /**
     * Delete data in the database
     * 
     * @param int   $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $this->getException();
        $query = $this->pdo->prepare("DELETE FROM {$this->table} WHERE {$this->prefix}id = ?");
        return $query->execute([$id]);
    }

    /**
     * Update a row in the database
     * 
     * @param  int   $id
     * @param  array $params
     * @param  array $exception
     * @return int
     */
    public function update(array $params, array $exception = ['id']): int
    {
        $id = $params['id'];
        $params = $this->filterArray($params, $exception);
        $fieldsQuery = join(', ', array_map(function ($field) {
            return "{$this->prefix}$field = :$field";
        }, array_keys($params)));
        $params['id'] = $id;

        $sql = "UPDATE " . $this->table . " SET $fieldsQuery WHERE {$this->prefix}id = :id";
        return $this->makeBuilder()->alter($sql, $params);
    }

    /**
     * Isert a row in the database
     * 
     * @param  int   $id
     * @param  mixed $params
     * @return int
     */
    public function insert($params, array $exception = []): int
    {
        $params = $this->filterArray($params, $exception);
        $fields = array_keys($params);
        $values = join(', ', array_map(function ($field) {
            return ':' . $field;
        }, $fields));
        $fields = join(', ', array_map(function($field) {
            return "{$this->prefix}$field";
        }, $fields));
        $sql = "INSERT INTO " . $this->table . "($fields) VALUES ($values)";
        return $this->makeBuilder()->alter($sql, $params);
    }

    /**
     * @return QueryBuilder
     */
    public function makeQuery(): QueryBuilder
    {
        $this->getException();
        return (new QueryBuilder())->from($this->table, $this->table[0]);
    }

    public function makeBuilder(): StatementBuilder
    {
        return new StatementBuilder($this->class, $this->pdo);
    }

    /**
     * Set the value of table
     *
     * @return  self
     */
    public function setTable(string $table): self
    {
        $this->table = $table;

        return $this;
    }

    /**
     * Return a sql request for public use
     * @return QueryBuilder
     */
    protected function getPublicQuery(): QueryBuilder
    {
        return $this->makeQuery();
    }

    protected function filterArray(array $params, array $excludes): array
    {
        return array_filter($params, function ($param) use ($excludes) {
            return !in_array($param, $excludes);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Get the value of table
     */ 
    public function getTable(): ?string
    {
        return $this->table;
    }
}