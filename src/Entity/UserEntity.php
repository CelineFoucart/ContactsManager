<?php

namespace App\Entity;

class UserEntity extends Entity
{
    private ?string $username = null;

    private ?string $email = null;
    
    private ?string $password = null;
    
    private $created = null;

    public function __construct()
    {
        if ($this->created) {
            $this->created = new \DateTime($this->created);
        } 
    }

    /**
     * Get the formated value of created
     * 
     * @return string
     */ 
    public function getCreated(string $format = "d/m/Y"): string
    {
        return $this->created->format($format);
    }

    /**
     * Set the value of created
     *
     * @return  self
     */ 
    public function setCreated($created): self
    {
        if($created instanceof \DateTime) {
            $this->created = $created;
        } else {
            $this->created = new \DateTime($created);
        }
        return $this;
    }

    /**
     * Get the value of username
     */ 
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * Set the value of username
     *
     * @return  self
     */ 
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the value of email
     */ 
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail(string $email): self
    {
        if (preg_match("#^[a-z0-9-_.]+@[a-z0-9-_.]{2,}\.[a-z]{2,4}$#", $email)) {
            $this->email = $email;
        } else {
            throw new \Exception("{$email} is not a valid email");
        }      
        return $this;
    }

    /**
     * Get the value of password
     */ 
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
}