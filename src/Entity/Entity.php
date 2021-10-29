<?php

namespace App\Entity;

abstract class Entity
{
    /**
     * @var int|null
     */
    protected ?int $id = null;

    /**
     * Set the value of id
     *
     * @param  int|null  $id
     *
     * @return  self
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of id
     *
     * @return  int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function __get($name)
    {
        $method = "get" . str_replace(" ", "", ucwords(str_replace("_", " ", $name)));
        return $this->$method();
    }
}
