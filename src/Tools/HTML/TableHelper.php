<?php

namespace App\Tools\HTML;

use App\Router\Router;

class TableHelper
{
    private static array $user = [ 
        'colspan' => 4, 
        'methods' => ['id', 'username', 'email']
    ];
    private static array $contact = [
        'colspan' => 5,
        'methods' => ['id', 'firstname', 'lastname', 'username']
    ];

    /**
     * @param array  $items     array of entities UserEntity|ContactEntity
     * @param Router $router
     * @param string $loopFor   type of entities  users|contacts
     * 
     * @return string
     */
    public static function loop(array $items, Router $router, string $loopFor): string
    {
        $params = self::getParamsForLoop($loopFor);  
        $colspan = $params['colspan'];     

        $html = "";
        if(empty($items)) {
            $html .= "<tr><td colspan=\"{$colspan}\">Le tableau est vide</td></tr>";
        } else {
            foreach ($items as $item) {
                $html .= "<tr>";
                $html .= self::getValues($params['methods'], $item);
                $urlEdit = $router->url("admin.{$loopFor}.edit", ['id' => $item->id]);
                $urlDelete = $router->url("admin.{$loopFor}.delete", ['id' => $item->id]);
                $html .= "<td><a href=\"$urlEdit\" class=\"btn btn-success\">Editer</a>";
                $html .= "<a href=\"$urlDelete\" class=\"btn btn-danger\">Supprimer</a></td>";
                $html .= "</tr>";
            }
        }
        
        return $html;
    }

    private static function getValues(array $methods, $entity)
    {
        $html = '';
        foreach ($methods as $method) {
            $html .= "<td>{$entity->$method}</td>";
        }
        return $html;
    }

    /**
     * Valid item type for the loop
     * 
     * @param string $loopFor
     * 
     * @return array
     */
    private static function getParamsForLoop(string $loopFor): array
    {
        switch (strtolower($loopFor)) {
            case 'users':
                $params = self::$user;
                break;
            case 'contacts':
                $params = self::$contact;
                break;
            default:
                throw new \Exception("Invalid parameter loopFor: must be user or contact");
                break;
        }
        return $params;
    }
}
