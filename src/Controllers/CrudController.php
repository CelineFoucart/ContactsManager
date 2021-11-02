<?php

namespace App\Controllers;

use App\Controllers\Helpers\Paginator;
use App\Entity\Entity;
use App\Entity\Hydrator;
use App\Exceptions\NotFoundException;
use App\Model\Manager\{UserManager, StatsManager, ContactManager};
use App\Responses\RedirectResponse;
use App\Router\Router;
use App\Session\FlashService;
use App\Session\SessionFactory;
use App\Tools\Csrf\CsrfManager;
use App\Tools\Form;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;

abstract class CrudController extends Controller
{
    /**
     * @var UserManager|StatsManager|ContactManager
     */
    protected $manager;
    protected FlashService $flash;
    protected ?string $urlPrefix = null;
    protected CsrfManager $csrf;
    protected ?string $prefixTitle = null;
    protected string $className;

    public function __construct(Router $router)
    {
        parent::__construct($router);
        $this->manager = $this->getManager();
        $this->flash = SessionFactory::getFlash();
        $this->csrf = new CsrfManager(SessionFactory::getSession());
    }

    public function index(ServerRequestInterface $request): Response
    {
        [$items, $pagination] = Paginator::getPaginatedItems($request, $this->manager, "admin.users");
        $title = "Administration des membres";
        $flash = $this->flash;
        return new Response(200, [], $this->render('index', compact('items', 'title', "pagination", 'flash')));
    }

    /**
     * Edit an item
     * 
     * @param ServerRequestInterface $request
     * 
     * @return Response
     */
    public function edit(ServerRequestInterface $request): Response
    {
        return $this->alter($request, true);
    }

    /**
     * Delete an item
     * 
     * @param ServerRequestInterface $request
     * 
     * @return Response
     */
    public function delete(ServerRequestInterface $request): Response
    {
        $item = $this->getItem((int)$request->getAttribute('id'));
        $title = ($this->prefixTitle === null) ? "Supprimer" : "{$this->prefixTitle} | Supprimer";
        if ($request->getMethod() === 'POST') {
            $this->csrf->process($request);
            if ($this->manager->delete($item->getId())) {
                $this->flash->success("L'élément a bien été supprimé.");
                return new RedirectResponse($this->router->url($this->urlPrefix . ".index"));
            } else {
                $this->flash->error("La suppression a échoué");
            }
        }
        $token = $this->csrf->generateToken();
        $flash = $this->flash;
        return new Response(200, [], $this->render('delete', compact('item', 'title', 'token', 'flash')));
    }

    /**
     * Make an update or an insert of an item
     * 
     * @param ServerRequestInterface $request
     * @param bool $update
     * 
     * @return Response
     */
    protected function alter(ServerRequestInterface $request, bool $update = true, string $redirect = "edit"): Response
    {
        $id = $request->getAttribute('id', null);
        $item = $this->getItem($id);
        $errors = [];
        if ($request->getMethod() === 'POST') {
            $this->csrf->process($request);
            $data = $request->getParsedBody();
            $errors = $this->validate($data);
            if (empty($errors)) {
                $this->flash->success("La mise à bien fonctionné.");
                if ($update) {
                    $data['id'] = $item->getId();
                    $this->manager->update($data);
                } else {
                    $id = $this->manager->insert($data);
                    return new RedirectResponse($this->router->url($this->urlPrefix . "." . $redirect, ['id' => $id]));
                }                
            } else {
                $this->flash->error("Il y a eu une erreur");
            }
            $item = Hydrator::hydrate($item, $data);
        }
        $title = ($this->prefixTitle === null) ? "Page d'édition" : $this->prefixTitle . " | Page d'édition";
        $params = [
            'flash' => $this->flash, 
            'token' => $this->csrf->generateToken(), 
            'form' => new Form($errors, $item),
            'title' => $title
        ];
        return new Response(200, [], $this->render('edit', $params));
    }

    /**
     * @param int|null $id
     * 
     * @return UserEntity|ContactEntity|Entity
     */
    protected function getItem(?int $id = null)
    {
        if($id === null) {
            $className = $this->className;
            return new $className();
        }
        $item = $this->manager->find("id = :id", ['id' => $id], true);
        if ($item === null) {
            throw new NotFoundException("Cet élément n'existe pas !");
        }
        return $item;
    }

    /**
     * @return UserManager|StatsManager|ContactManager
    */
    protected abstract function getManager();

    protected abstract function validate(array $data = []): array;
}