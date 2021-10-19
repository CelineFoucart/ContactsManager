<?php

namespace Tests;

use App\Tools\Validator;
use PHPUnit\Framework\TestCase;


class ValidatorTest extends TestCase
{
    private Validator $validator;
    
    protected function setUp(): void
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john.doe@email.com',
            'password' => '123456admin',
            'confirm' => '123456admin',
            'invalidConfirm' => '12345789aze',
            'content' => 'lorem ipsum sit amet. Dolorem nihil autem ut et'
        ];
        $this->validator = new Validator($data);
    }

    public function testValidData()
    {
        $this->validator->required('name', 'email', 'password', 'confirm', 'content')
            ->length('content', 5)
            ->password('password')
            ->confirmPassword('password', 'confirm')
            ->email('email');
        
        $this->assertTrue($this->validator->valid());
        $this->assertArrayNotHasKey('content', $this->validator->getErrors());
    }

    public function testInValidData()
    {
        $this->validator->required('name', 'loremipsum', 'password', 'confirm', 'invalidConfirm')
            ->password('password')
            ->confirmPassword('password', 'invalidConfirm')
            ->email('email');
        $errors = $this->validator->getErrors();
        $this->assertFalse($this->validator->valid());
        $this->assertArrayHasKey('loremipsum', $errors);
        $this->assertArrayHasKey('invalidConfirm', $errors);
    }
    
}