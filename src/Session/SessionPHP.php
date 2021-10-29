<?php

namespace App\Session;

class SessionPHP implements SessionInterface
{
    /**
     * Start a session
     *
     * @return self
     */
    public function start(): self
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return $this;
    }

    /**
     * Destroy the session
     *
     * @return self
     */
    public function end(): self
    {
        $this->start();
        unset($_SESSION);
        session_destroy();
        return $this;
    }

    /**
     * Get an information in session
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        $this->start();
        if (array_key_exists($key, $_SESSION)) {
            return $_SESSION[$key];
        }
        return $default;
    }

    /**
     * Add an information in session
     * 
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $key, $value): self
    {
        $this->start();
        $_SESSION[$key] = $value;
        return $this;
    }

    /**
     * Check if a key exists in session
     *
     * @return bool
     */
    public function exists(string $key): bool
    {
        $this->start();
        return isset($_SESSION[$key]);
    }

    /**
     * Delete a key in session
     * 
     * @param string $key
     * @return void
     */
    public function delete(string $key): void
    {
        $this->start();
        unset($_SESSION[$key]);
    }
}