<?php

namespace Tests;

use App\Tools\SendMail;
use PHPUnit\Framework\TestCase;

class SendMailTest extends TestCase
{
    public function testValidData()
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

    public function testInvalidData()
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