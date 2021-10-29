<?php

namespace App\Session;

class SessionArray implements SessionInterface
{
    private array $data = [];

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function get(string $key, $default = null)
    {
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }
        return $default;
    }

    public function set(string $key, $value): self
    {
        $this->data[$key] = $value;
        return $this;
    }

    public function exists(string $key): bool
    {
        return isset($this->data[$key]);
    }

    public function delete(string $key): void
    {
        unset($this->data[$key]);
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function end(): self
    {
        unset($this->data);
        return $this;
    }
}