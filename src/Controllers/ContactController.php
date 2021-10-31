<?php 

namespace App\Controllers;

use App\App;
use App\Controllers\Helpers\UserHelper;
use App\Entity\ContactEntity;
use App\Exceptions\NotFoundException;
use App\Model\Manager\ContactManager;
use App\Model\ModelFactory;
use App\Router\Router;
use App\Session\Auth;
use App\Session\FlashService;
use App\Session\SessionFactory;
use App\Tools\Form;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;

class ContactController extends Controller
{
    protected string $viewPath = "../views/contact/";
    protected FlashService $flash;
    protected Auth $auth;
    protected ContactManager $manager;

    public function __construct(Router $router)
    {
        $this->auth = SessionFactory::getAuth();
        UserHelper::isLogged($this->auth);
        $this->flash = SessionFactory::getFlash();
        $this->manager = ModelFactory::getInstance(App::getDbConfigs())->getManager('Contact');
        parent::__construct($router);
    }

    /**
     * Return a list of contacts
     * 
     * @param ServerRequestInterface $request
     * 
     * @return Response
     */
    public function list(ServerRequestInterface $request): Response
    {
        $id = (int)$this->auth->getUserId();
        $contacts = $this->manager->find('user_id = ?', [$id]);
        $title = "Voir mes contacts";
        return new Response(200, [], $this->render('list', compact('contacts', 'title')));
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
        $contact = $this->getContact($request);
        $title = "Voir un contact";
        return new Response(200, [], $this->render('show', compact('contact', 'title')));
    }

    public function edit(ServerRequestInterface $request): Response
    {
        $contact = $this->getContact($request);
        $title = "Editer un contact";
        $errors = [];
        if($request->getMethod() === 'POST') {
            // edition du contact
        }
        $form = new Form($errors, $contact);
        return new Response(200, [], $this->render('edit', compact('contact', 'title', 'form')));
    }

    public function delete(ServerRequestInterface $request): Response
    {
        $contact = $this->getContact($request);
        $title = "Supprimer un contact";
        if ($request->getMethod() === 'POST') {
            // edition du contact
        }
        return new Response(200, [], $this->render('delete', compact('contact', 'title')));
    }

    /**
     * @param ServerRequestInterface $request
     * 
     * @return ContactEntity
     */
    private function getContact(ServerRequestInterface $request): ContactEntity
    {
        $userId = (int)$this->auth->getUserId();
        $id = (int)$request->getAttribute('id');
        $contact = $this->manager->find("user_id = :user AND id = :id", ['user' => $userId, 'id' => $id], true);
        if ($contact === null) {
            throw new NotFoundException("Ce contact n'existe pas !");
        }
        return $contact;
    }
}