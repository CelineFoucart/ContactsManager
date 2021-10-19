<?php

namespace App\Tools;

class Validator
{
    protected array $data = [];

    protected array $errors = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @param string ...$keys
     * 
     * @return self
     */
    public function required(string ...$keys): self
    {
        foreach ($keys as $key) {
            if(!$this->exist($key)) {
                $this->errors[$key][] = "Ce champ n'existe pas !";
            }
        }
        return $this;
    }

    /**
     * @param string $key
     * @param int    $size
     * 
     * @return self
     */
    public function length(string $key, int $size): self
    {
        if (!$this->exist($key)) {
            $this->errors[$key][] = "Ce champ n'existe pas !";
            return $this;
        } else {
            if(!strlen($this->data[$key]) > $size) {
                $this->errors[$key][] = "Ce champ doit faire plus que $size caractères";
            }
            return $this;
        }
    }

    /**
     * @param string $key
     * 
     * @return self
     */
    public function password(string $key): self
    {
        if(!$this->exist($key)) {
            $this->errors[$key][] = "Ce champ n'existe pas !";
            return $this;
        } else {
            if (!preg_match("/^(?=.*[0-9])(?=.*[a-z]).{8,20}$/", $this->data[$key])) {
                $this->errors[$key][] = "Ce champ n'est pas un mot de passe valide";
            }
            return $this;
        }
    }

    /**
     * @param string $key
     * 
     * @return self
     */
    public function email(string $key): self
    {
        if (!$this->exist($key)) {
            $this->errors[$key][] = "Ce champ n'existe pas !";
            return $this;
        } else {
            if(!preg_match("#^[a-z0-9-_.]+@[a-z0-9-_.]{2,}\.[a-z]{2,4}$#", $this->data[$key])) {
                $this->errors[$key][] = "Ce champ n'est pas un mail";
            }
            return $this;
        }
    }

    /**
     * @param string $key
     * @param string $confirmKey
     * 
     * @return self
     */
    public function confirmPassword(string $key, string $confirmKey): self
    {
        if (!$this->exist($key) || !$this->exist($confirmKey)) {
            $this->errors[$key][] = "Ce mot de passe ne peut être confirmé !";
            return $this;
        } else {
            if($this->data[$key] !== $this->data[$confirmKey]) {
                $this->errors[$confirmKey][] = "Le mot de passe de confirmation est différent";
            }
            return $this;
        }
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function valid(): bool
    {
        return empty($this->errors);
    }

    /**
     * @param string $key
     * 
     * @return bool
     */
    private function exist(string $key): bool
    {
        return isset($this->data[$key]);
    }
}