<?php

namespace App\Controllers\Helpers;

use App\Model\Manager\Manager;
use App\Router\Router;
use App\Tools\Paging;
use Psr\Http\Message\ServerRequestInterface;

class Paginator
{
    public static function getPaginatedItems(
        ServerRequestInterface $request, 
        Manager $manager, 
        string $url,
        ?string $condition = null,
        array $params = []
    ): array
    {
        $get = $request->getQueryParams();
        $current = (isset($get['page'])) ? (int)$get['page'] : 1;
        $perPage = 15;
        $paging = new Paging($perPage);
        $offset = $paging->total($manager->count($condition, $params))->definePagination($current)->getOffset();
        $items = $manager->findPaginated(null, $perPage, $offset, $condition, $params);
        $pagination = [
            'previous' => $paging->previousLink($url),
            'pages' => $paging->getPages($url),
            'next' => $paging->nextLink($url)
        ];

        return [$items, $pagination];
    }
}