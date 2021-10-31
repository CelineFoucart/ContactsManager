<?php

namespace App\Controllers;

use App\Exceptions\ConfigException;
use App\Session\SessionFactory;
use App\Tools\Form;
use App\Tools\SendMail;
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
        $flash = SessionFactory::getFlash();
        if($request->getMethod() === 'POST') {
            $data = $request->getParsedBody();
            $validator = new Validator($data);
            $validator->required("name","mail","subject","content")
                ->length("name",2)
                ->length("subject", 5)
                ->length("content", 15)
                ->email('mail');
            if($validator->valid()) {
                $mail = [
                    'name' => htmlspecialchars($data['name']),
                    'mail' => htmlspecialchars($data['mail']),
                    'subject' => htmlspecialchars($data['subject']),
                    'content' => htmlspecialchars($data['content'])
                ];
                $sendMail = new SendMail($mail);
                $sendMail->from("name", "mail")->to(ADMIN_MAIL)->subject("subject")->body("content")->send();
                $flash->success("Le message a bien été envoyé.");
            } else {
                $errors = $validator->getErrors();
                $flash->error("Le message n'a pas pu être envoyé.");
            }
        }
        $form = new Form($errors, $data);      
        return $this->render('contact', compact('title', 'form', 'flash'));
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
