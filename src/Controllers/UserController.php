<?php

namespace App\Controllers;

use App\App;
use App\Router\Router;
use App\Session\FlashService;
use App\Session\SessionFactory;
use App\Tools\Form;
use App\Model\Manager\UserManager;
use App\Model\ModelFactory;
use Psr\Http\Message\ServerRequestInterface;

class UserController extends Controller
{
    protected string $viewPath = "../views/user/";
    protected FlashService $flash;
    protected UserManager $manager;

    public function __construct(Router $router)
    {
        $this->flash = SessionFactory::getFlash();
        $this->manager = ModelFactory::getInstance(App::getDbConfigs())->getManager('User');
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
            // vérifier données
            // sécuriser name et rechercher dans la db
            // comparer les mots de passe
            // si OK, connexion et redirection vers profil
            // SINON affichage du message d'erreur avec flash
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
}
