<?php 

namespace App\Controllers;

use App\App;
use App\Controllers\Helpers\Paginator;
use App\Controllers\Helpers\UserHelper;
use App\Entity\ContactEntity;
use App\Entity\Hydrator;
use App\Exceptions\NotFoundException;
use App\Model\Manager\ContactManager;
use App\Model\ModelFactory;
use App\Responses\RedirectResponse;
use App\Router\Router;
use App\Session\Auth;
use App\Session\FlashService;
use App\Session\SessionFactory;
use App\Tools\Csrf\CsrfManager;
use App\Tools\Form;
use App\Tools\Validator;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;

class ContactController extends CrudController
{
    protected string $viewPath = "../views/contact/";
    protected FlashService $flash;
    protected Auth $auth;
    protected ContactManager $manager;
    protected CsrfManager $csrf;
    protected ?string $prefixTitle = "Contacts";
    protected string $className = ContactEntity::class;

    public function __construct(Router $router)
    {
        $this->auth = SessionFactory::getAuth();
        UserHelper::isLogged($this->auth);
        parent::__construct($router);
    }

    /**
     * Return a list of contacts
     * 
     * @param ServerRequestInterface $request
     * 
     * @return Response
     */
    public function index(ServerRequestInterface $request): Response
    {
        $id = (int)$this->auth->getUserId();
        [$contacts, $pagination] = Paginator::getPaginatedItems(
            $request, 
            $this->manager, 
            $this->router->url("contact.index"),
            'user_id = ?', [$id]
        );
        $title = "Voir mes contacts";
        $flash = $this->flash;
        return new Response(200, [], $this->render('list', compact('contacts', 'title', 'pagination', 'flash')));
    }

    /**
     * Return a contact
     * 
     * @param ServerRequestInterface $request
     * 
     * @return Response
     */
    public function show(ServerRequestInterface $request): Response
    {
        $contact = $this->getItem((int)$request->getAttribute('id'));
        $title = "Voir un contact";
        $flash = $this->flash;
        return new Response(200, [], $this->render('show', compact('contact', 'title', 'flash')));
    }

    /**
     * Create a contact
     * 
     * @param ServerRequestInterface $request
     * 
     * @return Response
     */
    public function create(ServerRequestInterface $request): Response
    {
        return $this->alter($request, false, "show");
    }

    /**
     * Return a contact by id
     * 
     * @param ServerRequestInterface $request
     * 
     * @return ContactEntity
     */
    protected function getItem(?int $id = null): ContactEntity
    {
        if ($id === null) {
            return new ContactEntity();
        }
        $userId = (int)$this->auth->getUserId();
        $item = $this->manager->find("user_id = :user AND id = :id", ['user' => $userId, 'id' => $id], true);
        if ($item === null) {
            throw new NotFoundException("Ce contact n'existe pas !");
        }
        return $item;
    }

    protected function validate(array $data = []): array
    {
        $validator = new Validator($data);
        $validator->required("firstname", "lastname", "email", "number_phone")
            ->required("address", "city", "country")
            ->length("firstname", 3)
            ->length("lastname", 3)
            ->length("address", 10)
            ->length("city", 2)
            ->length("country", 2)
            ->email("email");
        return $validator->getErrors();
    }

    protected function getManager(): ContactManager
    {
        return ModelFactory::getInstance(App::getDbConfigs())->getManager('Contact');
    }
}