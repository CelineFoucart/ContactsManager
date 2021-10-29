<?php

namespace Tests;

use App\Tools\SendMail;
use PHPUnit\Framework\TestCase;

class SendMailTest extends TestCase
{
    public function testValidationData()
    {
        $data = [
            'pseudo' => "Ermina",
            'mail' => "ermina@domaine.com",
            'content' => "le contenu du message"
        ];
        $sendMail = new SendMail($data);
        if ($sendMail->validate("pseudo", "mail", "content")) {
            $sendMail->from("pseudo", "mail")->to('celine@mondomaine.fr')->body("content");
        }
        $errors = $sendMail->getErrors();        
        $this->assertTrue(empty($errors));
    }

    public function testInvalidDataWithValidation()
    {
        $data = [
            'pseudo' => "Ermina",
            'mail' => "ermina",
            'content' => "le contenu du message"
        ];
        $sendMail = new SendMail($data);
        if ($sendMail->validate("pseudo", "mail", "content")) {
            $sendMail->from("pseudo", "mail")->to('celine@mondomaine.fr')->body("content");
        }
        $errors = $sendMail->getErrors();

        $this->assertTrue(!empty($errors));
    }

    public function testValidWithSubject()
    {
        $data = [
            'pseudo' => "Ermina",
            'mail' => "ermina@domaine.com",
            'content' => "le contenu du message",
            'subject' => "J'ai un problème"
        ];
        $to = 'celine@mondomaine.fr';
        $sendMail = new SendMail($data);
        $sendMail->from("pseudo", "mail")->to($to)->subject("subject")->body("content");
        $header = $sendMail->getHeader();
        $this->assertEquals($data['subject'], $sendMail->getSubject());
        $this->assertStringContainsString($data['pseudo'], $header);
        $this->assertStringContainsString($data['mail'], $header);
        $this->assertEquals($to, $sendMail->getTo());
        $this->assertStringContainsString($data['content'], $sendMail->getBody());
    }
    
    public function testInvalid()
    {
        $data = [
            'pseudo' => "Ermina",
            'mail' => "ermina@domaine.com"
        ];
        $sendMail = new SendMail($data);
        $sendMail->from("pseudo", "mail")->to('celine@mondomaine.fr')->subject("subject")->body("content");
        try {
            $sendMail->getHeader();
            $sendMail->getSubject();
            $sendMail->getTo();
            $sendMail->getBody(); 
        } catch (\Exception $th) {
            $this->assertEquals("The body cannot be empty!", $th->getMessage());
        }
        $errors = $sendMail->getErrors();
        $this->assertTrue(!empty($errors));          
    }

    public function testInvalidMailTo()
    {
        $data = [
            'pseudo' => "Ermina",
            'mail' => "ermina@domaine.com",
            'content' => "le contenu du message"
        ];
        $sendMail = new SendMail($data);
        $to = "celine";
        $error = "";
        try {
            if ($sendMail->validate("pseudo", "mail", "content")) {
                $sendMail->from("pseudo", "mail")->to($to)->body("content");
            }
        } catch (\Exception $th) {
            $error = $th->getMessage();
        }
        $this->assertEquals("$to is not a valid email!", $error);
    }
}