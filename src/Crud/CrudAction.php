<?php
namespace App\Crud;

use App\Model\Manager\Manager;
use App\Session\FlashService;
use App\Tools\Validator;

class CrudAction implements CrudInterface
{
    protected Manager $manager;

    protected ?FlashService $flash = null;

    protected ?Validator $validator = null;

    public function __construct(Manager $manager, ?FlashService $flash = null, ?Validator $validator = null)
    {
        $this->manager = $manager;
        $this->flash = $flash;
        $this->setValidator($validator);
    }

    /**
     * Return a paginated list of items
     * 
     * @param array $params
     * 
     * @return array
     */
    public function list(array $params = []): array
    {
        if(empty($params['limit'])) {
            $params['limit'] = 30;
        }
        if(empty($params['offset'])) {
            $params['offset'] = 0;
        }
        if (empty($params['orderBy'])) {
            $params['orderBy'] = null;
        }        
        return $this->manager->findPaginated($params['orderBy'], $params['limit'], $params['offset']);
    }

    /**
     * Insert an item
     * 
     * @param array $data
     * 
     * @return int|null
     */
    public function insert(array $data = []): ?int
    {
        return $this->make($data, "insert");
    }

    /**
     * Update an item
     * 
     * @param array $data
     * 
     * @return int|null
     */
    public function update(array $data = []): ?int
    {
        return $this->make($data, "update");
    }

    /**
     * Delete an item
     * 
     * @param int $id
     * 
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->manager->delete($id);
    }

    /**
     * Set the value of validator
     * 
     * @param Validator|null $validator
     * 
     * @return  self
     */ 
    public function setValidator(?Validator $validator = null): self
    {
        $this->validator = $validator;
        return $this;
    }

    /**
     * Get the value of validator
     */ 
    public function getValidator(): Validator
    {
        return $this->validator;
    }

    /**
     * Update or insert a data if it is valid
     * 
     * @param array $data
     * @param string $method
     * 
     * @return int|null
     */
    protected function make(array $data, string $method): ?int
    {
        $success = $this->validate();
        if ($success) {
            $id = $this->manager->$method($data);
            return (int)$id;
        } else {
            return null;
        }
    }

    /**
     * Valid data and save the flash message
     * 
     * @return bool
     */
    protected function validate(): bool
    {
        if($this->validator === null) {
            return true;
        }
        $errors = $this->validator->getErrors();
        $success = empty($errors);
        if($success) {
            $this->flash("L'élément a bien été enregistré.", "success");
        } else {
            $this->flash("Il y a eu une erreur, car le formulaire est mal rempli.", "error");
        }
        return $success;
    }

    /**
     * Create a flash message
     * 
     * @param string $message
     * @param string $method  the flash method name: success or error
     * 
     * @return void
     */
    protected function flash(string $message, string $method = "success"): void
    {
        if ($this->flash !== null) {
            $this->flash->$method($message);
        }
    }
}