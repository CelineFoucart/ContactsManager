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
            ->select('id', 'firstname', 'lastname', 'email', 'number_phone AS numberPhone', 'address')
            ->select("city", "country", "user_id AS userId")
            ->orderBy("firstname");
    }
}
