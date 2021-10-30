<?php

namespace App\Model\Manager;

use App\Model\Builder\QueryBuilder;

class AdminManager extends Manager
{
    protected ?string $table = "admins";

    public function isAdmin(int $id): bool
    {
        return $this->count("user_id = ?", [$id]) !== 0;
    }

    protected function getPublicQuery(): QueryBuilder
    {
        return $this->makeQuery()
            ->select('id', 'user_id');
    }
}
