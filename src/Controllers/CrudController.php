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
use Exception;
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
        [$items, $pagination] = Paginator::getPaginatedItems($request, $this->manager, $this->router->url($this->urlPrefix));
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
        $result = $this->alter($request, true);
        if($result instanceof Response) {
            return $result;
        } elseif(is_array($result)) {
            return new Response(200, [], $this->render('edit', $result));
        } else {
            throw new Exception("Invalid return of method alter");
        }
    }

    /**
     * Create an item
     * 
     * @param ServerRequestInterface $request
     * 
     * @return Response
     */
    public function create(ServerRequestInterface $request): Response
    {
        $result = $this->alter($request, false);
        if ($result instanceof Response) {
            return $result;
        } elseif (is_array($result)) {
            return new Response(200, [], $this->render('create', $result));
        } else {
            throw new Exception("Invalid return of method alter");
        }
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
                return new RedirectResponse($this->router->url($this->urlPrefix));
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
     * @return Response|array
     */
    protected function alter(ServerRequestInterface $request, bool $update = true, string $redirect = "edit")
    {
        $id = $request->getAttribute('id', null);
        $item = $this->getItem($id);
        $errors = [];
        if ($request->getMethod() === 'POST') {
            $this->csrf->process($request);
            $data = $request->getParsedBody();
            $errors = $this->validate($data, $id);
            if (empty($errors)) {
                $this->flash->success("La mise à bien fonctionné.");
                $data = $this->hydrateDataForAlter($data, $item);
                if ($update) {
                    $this->manager->update($data, ['id', '_csrf', 'confirm']);
                } else {
                    $id = $this->manager->insert($data, ['_csrf', 'confirm']);
                    return new RedirectResponse($this->router->url($this->urlPrefix . "." . $redirect, ['id' => $id]));
                }                
            } else {
                $this->flash->error("Il y a eu une erreur");
            }
            $item = Hydrator::hydrate($item, $data);
        }
        $title = ($this->prefixTitle === null) ? "Page d'édition" : $this->prefixTitle . " | Page d'édition";
        return [
            'flash' => $this->flash, 
            'token' => $this->csrf->generateToken(), 
            'form' => new Form($errors, $item),
            'title' => $title
        ];
    }

    protected function hydrateDataForAlter(array $data, $item): array
    {
        $id = $item->getId();
        if ($id !== null) {
            $data['id'] = $item->getId();
        }
        return $data;
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

    protected abstract function validate(array $data = [], ?int $id = null): array;
}