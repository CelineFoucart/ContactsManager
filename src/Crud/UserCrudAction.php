<?php

namespace App\Crud;

use App\Tools\Validator;

class UserCrudAction extends CrudAction
{

    public function login(array $data): ?int
    {
        $errors = $this->validator->getErrors();
        if(empty($errors)) {
            $user = $this->manager->find("username = ?", [htmlspecialchars($data['username'])], true);
            if($user !== null) {
                if(password_verify($data['password'], $user->getPassword())) {
                    return (int)$user->getId();
                }
            }
        }
        $this->flash("Le mot de passe ou le pseudo est faux", "error");
        return null;
    }

    public function register(array $data): ?int
    {
        $this->validator
            ->email('email')
            ->password('password')
            ->confirmPassword('password', 'confirm')
            ->length('username', 3)
            ->unique('username', 'username', $this->manager)
            ->unique('email', 'email', $this->manager);
        array_pop($data);
        return $this->make($data, "insert");
    }

    public function setValidator(?Validator $validator = null): self
    {
        $validator->required('username', 'password');
        parent::setValidator($validator);
        return $this;
    }
}