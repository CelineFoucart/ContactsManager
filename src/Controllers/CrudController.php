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
    protected array $exceptionForPersist = ['id', '_csrf', 'confirm'];

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
        $item = $this->getItem((int)$request->getAttribute('id'));
        if($this->isPostMethod($request)) {
            $data = $request->getParsedBody();
            $errors = $this->makeValidationAndFlash($data, $item->getId());
            if(empty($errors)) {
                $data = $this->hydrateDataAfterUpdate($data, $item);
                $this->manager->update($data, $this->exceptionForPersist);
            }
            $item = Hydrator::hydrate($item, $data);
            $form = new Form($errors, $item);
        } else {
            $form = new Form();
        }
        $title = $this->generatePrefixedTitle("Page d'édition");
        return $this->makeResponseAfterAlterData($form, $title);
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
        if($this->isPostMethod($request)) {
            $data = $request->getParsedBody();
            $errors = $this->makeValidationAndFlash($data);
            if (empty($errors)) {
                $id = $this->manager->insert($data, $this->exceptionForPersist);
                return $this->makeRedirectAfterCreate($id);
            }
            $form = new Form($errors, $data);
        } else {
            $form = new Form();
        }
        $title = $this->generatePrefixedTitle("Page de création");
        return $this->makeResponseAfterAlterData($form, $title);
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
        $title = $this->generatePrefixedTitle('Supprimer');
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

    protected function makeValidationAndFlash(array $data, ?int $id = null): array
    {
        $errors = $this->validate($data, $id); 
        if(empty($errors)) {
            $this->flash->success("La mise à bien fonctionné.");
        } else {
            $this->flash->error("Il y a eu une erreur");
        }
        return $errors;
    }

    protected function makeRedirectAfterCreate(int $id): RedirectResponse
    {
        return new RedirectResponse($this->router->url($this->urlPrefix . ".edit", ['id' => $id]));
    }

    protected function generatePrefixedTitle(string $title): string
    {
        return ($this->prefixTitle === null) ? $title : "{$this->prefixTitle} | {$title}";
    }

    protected function hydrateDataAfterUpdate(array $data, $item): array
    {
        $data['id'] = $item->getId();
        return $data;
    }

    /**
     * @param int $id
     * 
     * @return UserEntity|ContactEntity|Entity
     */
    protected function getItem(int $id)
    {
        $item = $this->manager->find("id = :id", ['id' => $id], true);
        if ($item === null) {
            throw new NotFoundException("Cet élément n'existe pas !");
        }
        return $item;
    }

    protected function makeResponseAfterAlterData(Form $form, string $title)
    {
        $token = $this->csrf->generateToken();
        $flash = $this->flash;
        return new Response(200, [], $this->render('edit', compact('form', 'token', 'title', 'flash')));
    }

    /**
     * @return UserManager|StatsManager|ContactManager
    */
    protected abstract function getManager();

    protected abstract function validate(array $data = [], ?int $id = null): array;
}