<?php

namespace App\Model\Manager;

use App\Entity\ContactEntity;
use App\Model\Builder\QueryBuilder;

class ContactManager extends Manager
{
    protected ?string $class = ContactEntity::class;
    protected ?string $table = "contacts";

    protected function getPublicQuery(): QueryBuilder
    {
        return $this->makeQuery()
            ->select('c.id AS id', 'firstname', 'lastname', 'c.email AS email', 'number_phone AS numberPhone', 'address')
            ->select("city", "country", "user_id AS userId")
            ->select("u.username")
            ->join("users AS u", "user_id = u.id")
            ->orderBy("firstname");
    }
}
