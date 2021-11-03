<?php

namespace App\Controllers\Admin;

use App\App;
use App\Entity\UserEntity;
use App\Model\Manager\UserManager;
use App\Model\ModelFactory;
use App\Tools\Validator;

class AdminUserController extends AdminController
{
    protected string $viewPath = "../views/admin/users/";
    protected string $template = "../views/admin_layout";
    protected string $className = UserEntity::class;
    protected ?string $urlPrefix = "admin.users";

    protected function getManager(): UserManager
    {
        return ModelFactory::getInstance(App::getDbConfigs())->getManager('User');
    }

    protected function validate(array $data = [], ?int $id = null): array
    {
        $validator = new Validator($data);
        $validator->required("username", "email")->email('email')->length("username", 3);
        if(array_key_exists('password', $data)) {
            $validator
                ->password('password')
                ->confirmPassword('password', 'confirm')
                ->unique('username', 'username', $this->manager, $id)
                ->unique('email', 'email', $this->manager, $id);
        }      
        return $validator->getErrors();
    }
}