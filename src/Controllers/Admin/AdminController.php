<?php

namespace App\Controllers\Admin;

use App\App;
use App\Controllers\CrudController;
use App\Exceptions\ForbiddenException;
use App\Model\Manager\StatsManager;
use App\Model\ModelFactory;
use Psr\Http\Message\ServerRequestInterface;
use App\Router\Router;
use App\Session\SessionFactory;
use GuzzleHttp\Psr7\Response;

class AdminController extends CrudController
{
    protected string $viewPath = "../views/admin/";
    protected string $template = "../views/admin_layout";
    protected ?string $prefixTitle = "Administration";

    public function __construct(Router $router)
    {
        $this->auth = SessionFactory::getAuth();
        if (!$this->auth->isAdmin()) {
            throw new ForbiddenException("Vous n'avez pas accès à cette page");
        }
        parent::__construct($router);
    }

    /**
     * Get dashboard
     * 
     * @param ServerRequestInterface $request
     * 
     * @return Response
     */
    public function dashboard(ServerRequestInterface $request): Response
    {
        $title = "Administration | Général";
        $cards = $this->manager->getStats();
        return new Response(200, [], $this->render("dashboard", compact('title', 'cards')));
    }

    protected function validate(array $data = [], ?int $id = null): array
    {
        return [];
    }

    /**
     * @return StatsManager
     */
    protected function getManager()
    {
        return ModelFactory::getInstance(App::getDbConfigs())->getManager('Stats');
    }
}
