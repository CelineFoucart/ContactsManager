<?php

namespace App\Tools;

use \PDO;

class Paging {

    private ?int $current = null;
    private ?int $totalPage = null;
    private ?int $numberItems = null;
    private ?string $error = null;
    private int $perPage = 3;

    public function __construct(?int $perPage = null, ?int $count = null, ?int $current = null)
    {            
        if($perPage !== null) {
            $this->perPage($perPage);
        }
        if($count !== null) {
            $this->total($count);
        }
        if($perPage !== null && $count !== null && $current !== null) {
            $this->definePagination($current);
        }
    }

    public function perPage(int $perPage = 3): self
    {
        $this->perPage = $perPage;
        return $this;
    }

    public function total(int $count): self
    {
        $this->numberItems = $count;
        $this->setTotal();
        return $this;
    }

    public function countTotal(string $table, PDO $pdo): self
    {
        $this->numberItems = $pdo->query("SELECT COUNT(*) FROM {$table}")->fetch(\PDO::FETCH_NUM)[0];
        $this->setTotal();
        return $this;
    }

    /**
     * Set the value of the current page
     * 
     * @param mixed $current
     * @return int
     */
    public function definePagination(int $current = 1): self
    {
        if ($current <= 0) {
            $this->current = 1;
        } elseif ($current > $this->totalPage) {
            $this->error = "Numéro de page non valide";
            $this->current = $this->totalPage;
        } else {
            $this->current = $current;
        }
        return $this;
    }

    /**
     * @param string  $link
     * @param array   $params
     * 
     * @return string|null
     */
    public function previousLink(string $link, array $params = [], string $className = "action_link"): ?string
    {
        $this->throwError();
        $currentPage = $this->current;
        if ($currentPage <= 1) {
            return null;
        }
        $params['page'] = $currentPage - 1;
        $link .= '?' . http_build_query($params);
        return <<<HTML
        <a href="{$link}" class="{$className}">&laquo; Précédente</a>
HTML;
    }

    /**
     * @param string $link
     * @param string $class
     * 
     * @return string|null
     */
    public function nextLink(string $link, array $params = [], string $className= "action_link"): ?string
    {
        $this->throwError();
        $currentPage = $this->current;
        if ($currentPage >= $this->totalPage) {
            return null;
        }
        $params['page'] = $currentPage + 1;
        $link .= '?' . http_build_query($params);

        return <<<HTML
        <a href="{$link}" class="{$className}">Suivant &raquo;</a>
HTML;
    }

    /**
     * Get pages link
     * 
     * @param string $link
     * @param array $params
     * @return array
     */
    public function getPages(string $link, array $params = [], string $className = "action_link"): array
    {
        $this->throwError();
        $total = $this->totalPage;
        $current = $this->current;
        if ($current > $total) {
            return [];
        } elseif($total <= 7) {
            $selectedPages = [];
            for ($i=1; $i <= $total; $i++) {
                $selectedPages[] = $i;
            }
        } elseif($current < 5) {
            $selectedPages = [1,2,3,4,5, $total];
        } elseif($total - 4 < $current && $current <= $total) {
            $selectedPages = [1, $total - 4, $total - 3, $total - 2, $total - 1, $total];
        } else {
            $selectedPages = [1, $current - 2, $current -1, $current, $current + 1, $current + 2, $total];
        }
        $pages = [];
        foreach ($selectedPages as $page) {
            if ($page === $current) {
                $pages[] = "<strong>{$page}</strong>";
            } else {
                $params['page'] = $page;
                $pageLink = $link;
                $pageLink .= '?' . http_build_query($params);
                $pages[] = "<a href=\"{$pageLink}\" class=\"{$className}\">{$page}</a>";
            }
        }
        return $pages;
    }

    /**
     * Get offset
     * 
     * @return int
     */
    public function getOffset(): int
    {
        $this->throwError();
        return $this->perPage * ($this->current - 1);
    }

    /**
     * Get the value of perPage
     */ 
    public function getPerPage(): int
    {
        return $this->perPage;
    }

    /**
     * Get the value of numberItems
     */ 
    public function getNumberItems(): int
    {
        return $this->numberItems;
    }

    /**
     * Get errors of null
     * 
     * @return string|null
     */
    public function getError(): ?string
    {
        return $this->error;
    }

    private function setTotal(): void
    {
        $this->totalPage = ceil($this->numberItems / $this->perPage);
    }

    private function throwError(): void
    {
        if ($this->totalPage === null || $this->current === null) {
            throw new \Exception('La pagination n\'est pas définie !');
        }
    }
}