<?php

namespace App\Controllers;

use App\Exception\ConfigException;
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
        // créer class form
        // générer le formulaire
        // class send mail
        // vérification données
        // envoi du message ou affichage message d'erreur
        return "Page de contact";
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
