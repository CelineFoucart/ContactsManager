<?php

namespace App\Session;

class Auth
{
    
    protected SessionInterface $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * Hydrate $session
     *
     * @param  int   $id
     * @param  bool  $admin
     * @return self
     */
    public function session(int $id, bool $admin): self
    {
        $this->session->set('id', $id)->set('auth', $admin);
        return $this;
    }
    
    /**
     * Check the password
     *
     * @param  mixed $hash
     * @param  mixed $password
     * @return bool
     */
    public function checkPassword(string $hash, string $password): bool
    {
        return password_verify($password, $hash);
    }
    
    /**
     * Check if a user is logged
     *
     * @return bool
     */
    public function logged(): bool
    {
        return $this->session->exists('id');
    }
    
    /**
     * Check if a user is admin
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        if(!$this->session->exists('auth')) {
            return false;
        }
        return $this->session->get('auth');
    }
    
    /**
     * Logout the user
     *
     * @return self
     */
    public function logout(): self
    {
       $this->session->end();
       return $this;
    }
    
    /**
     * Return user id
     *
     * @return null|int
     */
    public function getUserId(): ?int
    {
        if ($this->logged()) {
            return $this->session->get('id');
        }
        return null;
    }

    /**
     * Get the value of session
     */ 
    public function getSession(): SessionInterface
    {
        return $this->session;
    }
}