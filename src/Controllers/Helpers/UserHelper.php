<?php

namespace App\Controllers\Helpers;

use App\Entity\UserEntity;
use App\Exceptions\NotFoundException;
use App\Exceptions\NotLoggedException;
use App\Model\Manager\UserManager;
use App\Session\Auth;
use App\Tools\Validator;

class UserHelper
{
    /**
     * Return logged user informations
     * 
     * @param Auth $auth
     * @param UserManager $manager
     * 
     * @return UserEntity
     */
    public static function findLoggedUser(Auth $auth, UserManager $manager): UserEntity
    {
        self::isLogged($auth);
        $id = (int)$auth->getUserId();
        $user = $manager->findById($id);
        if ($user === null) {
            throw new NotFoundException("Cet utilisateur n'existe pas");
        }
        return $user;
    }

    /**
     * Edit a user
     * 
     * @param array       $data
     * @param Validator   $validator
     * @param UserManager $manager
     * @param int $id
     * 
     * @return array
     */
    public static function edit(array $data, Validator $validator, UserManager $manager): array
    {
        if (!empty($data['email'])) {
            $validator->email('email');
        } elseif (!empty($data['password'])) {
            $validator->password('password')->confirmPassword('password', 'confirm');
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        $errors = $validator->getErrors();
        if(empty($errors)) {
            $manager->update($data, ['id', 'confirm']);
        }
        return $errors;
    }

    public static function isLogged(Auth $auth): void
    {
        if (!$auth->logged()) {
            throw new NotLoggedException();
        }
    }
}
