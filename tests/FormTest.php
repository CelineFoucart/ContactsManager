<?php

namespace Tests;

use App\Tools\Form;
use PHPUnit\Framework\TestCase;

class FormTest extends TestCase
{
    public function testInput()
    {
        $form = new Form([]);
        $input = $form->input('pseudo', 'Nom <sup>*</sup>', ['placeholder' => "Votre nom"]);

        $this->assertStringContainsString('<label for="pseudo">Nom <sup>*</sup></label>', $input);
        $this->assertStringContainsString('<input type="text"', $input);
        $this->assertStringContainsString('<div class="form-group">', $input);
    }

    public function testInputWithData()
    {
        $errors = [];
        $data = ['pseudo' => 'Ermina'];
        $form = new Form($errors, $data);
        $input = $form->input('pseudo', 'Nom <sup>*</sup>', ['placeholder' => "Votre nom"]);
        $this->assertStringContainsString('Ermina', $input);
    }

    public function testInputWithError()
    {
        $errors = ['pseudo' => 'Invalid name'];
        $form = new Form($errors);
        $input = $form->input('pseudo', 'Nom <sup>*</sup>', ['placeholder' => "Votre nom"]);
        $this->assertStringContainsString('Invalid name', $input);
    }

    public function testTextarea()
    {
        $errors = [];
        $form = new Form($errors);
        $textarea = $form->textarea('content', 'Commentaire <sup>*</sup>', ['placeholder' => "votre commentaire..."]);
        $this->assertStringContainsString('<textarea', $textarea);
        $this->assertStringContainsString('votre commentaire...', $textarea);
    }

    public function testTextareaWithData()
    {
        $errors = [];
        $data = ['content' => 'Hello world'];
        $form = new Form($errors, $data);
        $textarea = $form->textarea('content', 'Commentaire <sup>*</sup>', ['placeholder' => "votre commentaire..."]);
        $this->assertStringContainsString('Hello world', $textarea);
    }

    public function testTextareaWithError()
    {
        $errors = ['content' => 'invalid field'];
        $data = ['content' => 'Hello world'];
        $form = new Form($errors);
        $textarea = $form->textarea('content', 'Commentaire <sup>*</sup>', ['placeholder' => "votre commentaire..."]);
        $this->assertStringContainsString('invalid field', $textarea);
    }
}