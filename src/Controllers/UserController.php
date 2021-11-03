<?php

namespace App\Controllers;

use App\App;
use App\Controllers\Helpers\UserHelper;
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
use App\Tools\Validator;
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
        $this->adminManager = ModelFactory::getInstance(App::getDbConfigs())->getManager('Admin');
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
            $crud = new UserCrudAction($this->manager, $this->flash, new Validator($data));
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

    /**
     * @param ServerRequestInterface $request
     * 
     * @return RedirectResponse|string
     */
    public function register(ServerRequestInterface $request)
    {
        $errors = [];
        $data = [];
        if($request->getMethod() === 'POST') {
            $data = $request->getParsedBody();
            $crud = new UserCrudAction($this->manager, $this->flash, $this->validator);
            $id = $crud->register($data);
            if($id === null) {
                $errors = $crud->getValidator()->getErrors();
            } else {
                $this->auth->session($id, false);
                return new RedirectResponse($this->router->url('profil'));
            }
        }
        $form = new Form($errors, $data);
        return $this->render(
            'register', 
            ['title' => WEBSITE_NAME . " | Inscription", 'flash' => $this->flash, 'form' => $form]
        );
    }

    /**
     * @param ServerRequestInterface $request
     * 
     * @return RedirectResponse
     */
    public function logout(ServerRequestInterface $request): RedirectResponse
    {
        if($this->auth->logged()) {
            $this->auth->logout();
        }
        return new RedirectResponse($this->router->url('home'));
    }

    /**
     * Show profil page and edit informations
     * 
     * @param ServerRequestInterface $request
     * @return string
     */
    public function profil(ServerRequestInterface $request): string
    {
        $user = UserHelper::findLoggedUser($this->auth, $this->manager);
        $errors = [];
        if($request->getMethod() === 'POST') {
            $data = array_merge($request->getParsedBody(), ['id' => $user->getId()]);
            $validator = new Validator($data);
            $errors = UserHelper::edit($data, $validator, $this->manager);
            if(empty($errors)) {
                $this->flash->success("Le profil a été mis à jour");
                if(isset($data['email'])) {
                    $user->setEmail($data['email']);
                }
            } else {
                $this->flash->error("La mise à jour à échoué");
            }
        }
        $form = new Form($errors, ['email' => $user->getEmail()]);
        $params = ['title' => WEBSITE_NAME . " | Profil", 'user' => $user, 'form' => $form, 'flash' => $this->flash];
        return $this->render('profil', $params);
    }
}
