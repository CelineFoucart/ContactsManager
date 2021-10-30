<?php

namespace Core\Tools;

class Paging {

    private int $current;
    private int $totalPage;
    private int $numberItems;
    private ?string $error = null;
    private $pdo;
    private int $perPage;

    public function __construct(int $current, string $table, $pdo, int $perPage = 3)
    {
        $this->pdo = $pdo;
        $this->perPage = $perPage;
        $this->numberItems = $this->count($table);       
        $this->totalPage = ceil($this->numberItems / $this->perPage);
        $this->current = $this->setCurrent((int)$current);
    }

    public function count(string $table): int
    {
        return $this->pdo->query("SELECT COUNT(*) FROM {$table}")->fetch(\PDO::FETCH_NUM)[0];
    }

    /**
     * Set the value of the current page
     * 
     * @param mixed $current
     * @return int
     */
    private function setCurrent($current): int 
    {
        if($current <= 0) {
            return 1;
        } elseif($current > $this->totalPage) {
            $this->error = "Numéro de page non valide";
            return $this->totalPage;
        } else {
            return $current;
        }
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

    /**
     * Get offset
     * 
     * @return int
     */
    public function getOffset(): int
    {  
        return $this->perPage * ($this->current - 1);
    }

    /**
     * @param string  $link
     * @param array   $params
     * 
     * @return string|null
     */
    public function previousLink(string $link, array $params = []): ?string
    {
        $currentPage = $this->current;
        if ($currentPage <= 1) {
            return null;
        }
        $params['page'] = $currentPage - 1;
        $link .= '?' . http_build_query($params);
        return <<<HTML
        <a href="{$link}">&laquo; Précédente</a>
HTML;
    }

    /**
     * @param string $link
     * @param string $class
     * 
     * @return string|null
     */
    public function nextLink(string $link, array $params = []): ?string
    {
        $currentPage = $this->current;
        if ($currentPage >= $this->totalPage) {
            return null;
        }
        $params['page'] = $currentPage + 1;
        $link .= '?' . http_build_query($params);

        return <<<HTML
        <a href="{$link}">Suivant &raquo;</a>
HTML;
    }

    /**
     * Get pages link
     * 
     * @param string $link
     * @param array $params
     * @return array
     */
    public function getPages(string $link, array $params = []): array
    {
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
                $pages[] = "<a href=\"{$pageLink}\">{$page}</a>";
            }
        }
        return $pages;        
    }

    /**
     * Get the value of perPage
     */ 
    public function getPerPage(): int
    {
        return $this->perPage;
    }

    /**
     * Set the value of numberItems
     *
     * @return  self
     */ 
    public function setNumberItems(int $numberItems)
    {
        $this->numberItems = $numberItems;
        $this->totalPage = ceil($this->numberItems / $this->perPage);

        return $this;
    }
}