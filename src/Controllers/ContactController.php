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
use App\Tools\Paging;
use App\Tools\Validator;
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
        $get = $request->getQueryParams();
        $current = (isset($get['page'])) ? (int)$get['page'] : 1;
        $perPage = 15;
        $paging = new Paging($perPage);        
        $offset = $paging->total($this->manager->count('user_id = ?', [$id]))->definePagination($current);
        $contacts = $this->manager->findPaginated(null, $perPage, $offset, 'user_id = ?', [$id]);
        $url = $this->router->url("contactList");
        $pagination = [
            'previous' => $paging->previousLink($url),
            'pages' => $paging->getPages($url),
            'next' => $paging->nextLink($url)
        ];
        $title = "Voir mes contacts";
        return new Response(200, [], $this->render('list', compact('contacts', 'title', 'pagination')));
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

    public function create(ServerRequestInterface $request): Response
    {
        $title = "Créer un contact";
        $form = $this->alter($request, false);
        return new Response(200, [], $this->render('edit', compact('contact', 'title', 'form')));
    }

    public function edit(ServerRequestInterface $request): Response
    {
        $title = "Editer un contact";
        $form = $this->alter($request);
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

    /**
     * @param ServerRequestInterface $request
     * @param bool $update
     * 
     * @return Form
     */
    private function alter(ServerRequestInterface $request, bool $update = true): Form
    {
        $contact = $update ? $this->getContact($request) : new ContactEntity();
        $errors = [];
        if ($request->getMethod() === 'POST') {
            $data = $request->getParsedBody();
            $validator = new Validator($data);
            $validator->required("firstname", "lastname", "email","number_phone")
                ->required("address","city","country")
                ->length("firstname", 3)
                ->length("lastname", 3)
                ->length("address", 10)
                ->length("city", 2)
                ->length("country", 2)
                ->email("email");
            $errors = $validator->getErrors();
            if(empty($errors)) {
                $this->flash->success("La mise à bien fonctionné.");
                if($update) {
                    $data['id'] = $contact->getId();
                    $this->manager->update($data);
                } else {
                    $this->manager->insert($data);
                }
            } else {
                $this->flash->error("Il y a eu une erreur");
            }
            // faire hydratation
        }
        return new Form($errors, $contact);
    }
}