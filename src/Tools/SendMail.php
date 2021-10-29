<?php

namespace App\Tools;

class SendMail {

    private array $data = [];
    private array $errors = [];
    private ?string $header = null;
    private ?string $subject = null;
    private ?string $to = null;
    private ?string $body = null;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function isMail($mail): bool
    {
        return preg_match("#^[a-z0-9-_.]+@[a-z0-9-_.]{2,}\.[a-z]{2,4}$#", $mail);
    }

    /**
     * Validate the data
     */
    public function validate(...$keys): bool
    {
        foreach ($keys as $key) {
            if (empty($this->data[$key])) {
                $this->errors[$key][] = "Ce champ n'est pas valide";
            }
        }
        return empty($this->errors);
    }
    
    /**
     * Hydrate the e-mail header
     *
     * @param  mixed $fromName
     * @param  mixed $fromMail
     * @return self
     */
    public function from(string $keyName, string $keyMail): self
    {
        $fromName = (empty($this->data[$keyName])) ? null : $this->data[$keyName];
        $fromMail = (empty($this->data[$keyMail]) || !$this->isMail($this->data[$keyMail])) ? null : $this->data[$keyMail];

        if ($fromName === null || $fromMail === null) {
            if ($fromName === null) {
                $this->errors[$keyName][] = "Ce champ n'est pas valide";
            } elseif ($fromMail === null) {
                $this->errors[$keyMail][] = "Ce champ n'est pas un mail valide";
            }
            return $this;
        }

        $this->header = "From: \"" . htmlspecialchars($fromName) . "\"<" . htmlspecialchars($fromMail) . ">" . "\r\n";
        $this->header .= "MIME-Version: 1.0" . "\r\n";
        $this->header .= "Content-Type: text/html; charset=\"UTF-8\"" . "\r\n";

        return $this;
    }
    
    /**
     * Hydrate the addressee email
     *
     * @param  string $key
     * @return self
     */
    public function to(string $to): self
    {
        if (!$this->isMail($to)) {
            throw new \Exception("$to is not a valid email!");
        }

        $this->to = htmlspecialchars($to);
        return $this;
    }
    
    /**
     * Hydrate the message of the e-mail
     *
     * @param  string $key
     * @return self
     */
    public function body(string $key = "content"): self
    {
        if (empty($this->data[$key])) {
            $this->errors[$key][] = "Ce champ n'est pas valide";
            return $this;
        }

        $this->body = '<html><head></head><body>' . nl2br(htmlspecialchars($this->data[$key])) . '</body></html>';
        return $this;
    }

    /**
     * Hydrate the subject
     * 
     * @param string $subject
     * @return self
     */
    public function subject(string $key): self
    {
        $subject = empty($this->data[$key]) ? null : $this->data[$key];
        $this->setSubject($subject);
        return $this;
    }
    
    /**
     * Send the e-mail
     *
     * @return bool
     */
    public function send(): bool
    {
        return mail($this->getTo(), $this->getSubject(), $this->getBody(), $this->getHeader()); 
    }
    
    /**
     * Get the value of $errors
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Get the value of header
     */ 
    public function getHeader(): string
    {
        if ($this->header === null) {
            throw new \Exception("The header cannot be empty!");
        }
        return $this->header;
    }

    /**
     * Get the value of subject
     */ 
    public function getSubject(): string
    {
        if($this->subject === null) {
            $this->setSubject();
        }
        return $this->subject;
    }

    /**
     * Get the value of to
     */ 
    public function getTo(): string
    {
        if (!$this->isMail($this->to)) {
            throw new \Exception("{$this->to} is not a valid email!");
        }
        return $this->to;
    }

    /**
     * Get the value of body
     */ 
    public function getBody(): string
    {
        if($this->body === null || mb_strlen($this->body) === 0) {
            throw new \Exception("The body cannot be empty!");
        }
        return $this->body;
    }

    private function setSubject(?string $subject = null): self
    {
        if ($subject === null || mb_strlen($subject) === 0) {
            $this->subject = "You have received a message from your website";
        } else {
            $this->subject = $subject;
        }
        return $this;
    }
}