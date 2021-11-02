<?php

namespace App\Entity;

class Hydrator
{
    /**
     * Hydrate an entity
     * 
     * @param Entity $entity
     * @param array $data
     * 
     * @return Entity
     */
    public static function hydrate(Entity $entity, array $data = []): Entity
    {
        foreach ($data as $key => $value) {
            $method = "set" . str_replace(" ", "", ucwords(str_replace("_", " ", $key)));
            if(method_exists($entity, $method)) {
                $entity->$method($value);
            }
        }
        return $entity;
    }
}