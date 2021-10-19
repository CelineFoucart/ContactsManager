<?php

namespace App\Controllers;

use App\Exception\ConfigException;
use App\Tools\Form;
use App\Tools\Validator;
use Psr\Http\Message\ServerRequestInterface;

class PublicController extends Controller
{
    protected string $viewPath = "../views/public/";

    /**
     * Affichage de l'accueil du site
     * 
     * @param ServerRequestInterface $request
     * @return string
     */
    public function index(ServerRequestInterface $request): string
    {
        $title = WEBSITE_NAME . " | Gestion de mes contacts";
        return $this->render('home', compact('title'));
    }

    /**
     * Affichage de la page de contact
     * 
     * @param ServerRequestInterface $request
     * @return string
     */
    public function contact(ServerRequestInterface $request): string
    {
        $title = WEBSITE_NAME . " | Contact";
        if(!defined('ADMIN_MAIL')) {
            throw new ConfigException('The constant ADMIN_MAIL is not defined!');
        }

        $errors = [];
        $data = [];
        if($request->getMethod() === 'POST') {
            $data = $request->getParsedBody();
            $validator = new Validator($data);
            $validator->required("name","mail","subject","content")
                ->length("name",2)
                ->length("subject", 5)
                ->length("content", 15)
                ->email('mail');
            if($validator->valid()) {
                // class send mail
                // envoi du message
            } else {
                $errors = $validator->getErrors();
                // affichage message d'erreur
            }
        }
        $form = new Form($errors, $data);      
        return $this->render('contact', compact('title', 'form'));
    }

    /**
     * Affichage de la page à propos
     * 
     * @param ServerRequestInterface $request
     * @return string
     */
    public function about(ServerRequestInterface $request): string
    {
        $title = "A propos de " . WEBSITE_NAME;
        return $this->render('about', compact('title'));
    }

}
