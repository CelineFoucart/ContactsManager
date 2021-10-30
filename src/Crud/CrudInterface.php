<?php

namespace App\Crud;

interface CrudInterface
{
    /**
     * Return a paginated list of items
     * 
     * @param array $params
     * 
     * @return array
     */
    public function list(array $params = []): array;

    /**
     * Insert an item
     * 
     * @param array $data
     * 
     * @return int|null
     */
    public function insert(array $data = []): ?int;

    /**
     * Update an item
     * 
     * @param array $data
     * 
     * @return int|null
     */
    public function update(array $data = []): ?int;

    /**
     * Delete an item
     * 
     * @param int $id
     * 
     * @return bool
     */
    public function delete(int $id): bool;
}