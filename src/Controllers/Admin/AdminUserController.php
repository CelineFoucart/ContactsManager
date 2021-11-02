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

    protected function getManager(): UserManager
    {
        return ModelFactory::getInstance(App::getDbConfigs())->getManager('Users');
    }

    protected function validate(array $data = []): array
    {
        $validator = new Validator($data);
        $validator->required("username", "email")->email('email')->length("username", 3);
        return $validator->getErrors();
    }
}