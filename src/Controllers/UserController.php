<?php

namespace App\Controllers;

use App\App;
use App\Crud\UserCrudAction;
use App\Model\Manager\AdminManager;
use App\Router\Router;
use App\Session\FlashService;
use App\Session\SessionFactory;
use App\Tools\Form;
use App\Model\Manager\UserManager;
use App\Model\ModelFactory;
use App\Responses\RedirectResponse;
use App\Session\Auth;
use Psr\Http\Message\ServerRequestInterface;

class UserController extends Controller
{
    protected string $viewPath = "../views/user/";
    protected FlashService $flash;
    protected UserManager $manager;
    protected Auth $auth;
    protected AdminManager $adminManager;

    public function __construct(Router $router)
    {
        $this->flash = SessionFactory::getFlash();
        $this->auth = SessionFactory::getAuth();
        $this->manager = ModelFactory::getInstance(App::getDbConfigs())->getManager('User');
        $this->adminManager = ModelFactory::getInstance(App::getDbConfigs())->getTable('Admin');

        parent::__construct($router);
    }

    /**
     * @param ServerRequestInterface $request
     * 
     * @return string|RedirectResponse
     */
    public function login(ServerRequestInterface $request)
    {
        $errors = [];
        if($request->getMethod() === 'POST') {
            $data = $request->getParsedBody();
            $crud = new UserCrudAction($this->manager, $this->flash, $this->validator);
            $id = $crud->login($data);
            if($id !== null) {
                $admin = $this->adminManager->isAdmin($id);
                $this->auth->session($id, $admin);
                return new RedirectResponse($this->router->url('profil'));
            } else {
                $errors = $crud->getValidator()->getErrors();
            }
        }
        $form = new Form($errors, []);
        return $this->render(
            'login', 
            ['title' => WEBSITE_NAME . " | Connexion", 'flash' => $this->flash, 'form' => $form]
        );
    }

    public function register(ServerRequestInterface $request)
    {
        $errors = [];
        $form = new Form($errors, []);
        return $this->render(
            'register', 
            ['title' => WEBSITE_NAME . " | Inscription", 'flash' => $this->flash, 'form' => $form]
        );
    }

    public function logout(ServerRequestInterface $request)
    {
    }

    public function profil(ServerRequestInterface $request)
    {
        
    }
}
