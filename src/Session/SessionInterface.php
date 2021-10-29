<?php

namespace App\Session;

interface SessionInterface
{
    /**
     * Get an information in session
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     * Add an information in session
     * 
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $key, $value): self;

    /**
     * Check if a key exists in session
     *
     * @return bool
     */
    public function exists(string $key): bool;

    /**
     * Delete a key in session
     * 
     * @param string $key
     * @return void
     */
    public function delete(string $key): void;

    /**
     * Destroy the session
     *
     * @return self
     */
    public function end(): self;
}