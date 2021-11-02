<?php

namespace App\Tools\Csrf;

use App\Session\SessionInterface;
use Psr\Http\Message\ServerRequestInterface;

class CsrfManager
{
    private string           $formKey;
    private string           $sessionKey;
    private SessionInterface $session;
    private int              $limit;
    
    public function __construct (SessionInterface $session, int $limit = 50, string $formKey = '_csrf', string $sessionKey = 'csrf')
    {
        $this->session = $session;
        $this->formKey = $formKey;
        $this->limit = $limit;
        $this->sessionKey = $sessionKey;
    }

    /**
     * @param ServerRequestInterface $request
     * @return bool
     */
    public function process(ServerRequestInterface $request): bool
    {
        if (in_array($request->getMethod(), ['POST', 'PUT', 'DELETE'])) {
            $params = $request->getParsedBody() ?: [];
            if (!array_key_exists($this->formKey, $params)) {
                $this->reject();
            } else {
                $csrfList = $this->session->get($this->sessionKey) ?? [];
                if (in_array($params[$this->formKey], $csrfList)) {
                    $this->useToken($params[$this->formKey]);
                    return true;
                } else {
                    $this->reject();
                }
            }
        }
        return true;
    }

    /**
     * Generate a csrf token
     * @return string
     */
    public function generateToken(): string
    {
        $token = bin2hex(random_bytes(16));
        $csrfList = $this->session->get($this->sessionKey) ?? [];
        $csrfList[] = $token;
        $this->session->set($this->sessionKey, $csrfList);
        $this->limitTokens();
        return $token;
    }

    /**
     * Get the value of formKey
     */
    public function getFormKey(): string
    {
        return $this->formKey;
    }

    /**
     * Limit the number of tokens
     * @return void
     */
    private function limitTokens(): void
    {
        $tokens = $this->session->get($this->sessionKey) ?? [];
        if (count($tokens) > $this->limit) {
            array_shift($tokens);
        }
        $this->session->set($this->sessionKey, $tokens);
    }

    /**
     * Use a token and unset it in the array
     * @param string $token
     * @return void
     */
    private function useToken(string $token): void
    {
        $tokens = array_filter($this->session->get($this->sessionKey), function ($t) use ($token) {
            return $token !== $t;
        });
        $this->session->set($this->sessionKey, $tokens);
    }

    /**
     * Reject the csrf
     * @return void
     */
    private function reject(): void
    {
        throw new CsrfInvalidException("Invalid csrf");
    }
}