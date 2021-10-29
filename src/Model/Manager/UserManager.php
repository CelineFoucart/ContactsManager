<?php

namespace App\Model\Manager;

use App\Entity\UserEntity;
use App\Model\Builder\QueryBuilder;

class UserManager extends Manager
{
    protected ?string $class = UserEntity::class;
    protected ?string $table = "users";

    protected function getPublicQuery(): QueryBuilder
    {
        return $this->makeQuery()
        ->select('id','username','email','password','created_at AS created');
    }
}