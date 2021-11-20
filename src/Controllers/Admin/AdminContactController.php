<?php

namespace App\Controllers\Admin;

use App\App;
use App\Controllers\ContactController;
use App\Controllers\Helpers\ValidatorHelper;
use App\Entity\ContactEntity;
use App\Exceptions\NotFoundException;
use App\Model\Manager\ContactManager;
use App\Model\Manager\UserManager;
use App\Model\ModelFactory;
use App\Tools\Validator;

class AdminContactController extends AdminController
{
    protected string $viewPath = "../views/admin/contacts/";
    protected string $template = "../views/admin_layout";
    protected string $className = ContactEntity::class;
    protected ?string $urlPrefix = "admin.contacts";

    protected function getManager(): ContactManager
    {
        return ModelFactory::getInstance(App::getDbConfigs())->getManager('Contact');
    }

    protected function validate(array $data = [], ?int $id = null): array
    {
        $validator = ValidatorHelper::getValidatorForContact($data);
        return $validator->getErrors();
    }

    /**
     * @param int|null $id
     * 
     * @return UserEntity|ContactEntity|Entity
     */
    protected function getItem(int $id)
    {
        $item = $this->manager->find("c.id = :id", ['id' => $id], true);
        if ($item === null) {
            throw new NotFoundException("Cet élément n'existe pas !");
        }
        return $item;
    }
}
