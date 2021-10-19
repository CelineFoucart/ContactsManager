<?php

namespace App\Tools;

class SendMail {

    private array $data = [];
    private array $errors = [];
    private ?string $header;
    private ?string $to;
    private ?string $body;

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

        $this->body = '<html><head></head><body>' . nl2br($this->data[$key]) . '</body></html>';
        return $this;
    }
    
    /**
     * Send the e-mail
     *
     * @param  mixed $subject
     * @return bool
     */
    public function send(string $subject = ""): bool
    {
       return mail($this->to, $subject, $this->body, $this->header); 
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
}