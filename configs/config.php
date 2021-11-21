<?php

/** the name of the website */
if(!defined('WEBSITE_NAME')) {
    define('WEBSITE_NAME', 'Mes contacts');
}

/** the purpose of the website */
if (!defined('WEBSITE_LEGEND')) {
    define('WEBSITE_LEGEND', 'Bienvenue sur Mes contacts, le site pour gérer ses contacts');
}

/** SEO description */
if (!defined('SEO_DESCRIPTION')) {
    define('SEO_DESCRIPTION', 'Mes contacts est un site pour gérer ses contacts');
}

/** Administrator mail */
if (!defined('ADMIN_MAIL')) {
    define('ADMIN_MAIL', 'celinefoucart@yahoo.fr');
}

/** database informations */
if(!defined('DB_SETTINGS')) {
    define('DB_SETTINGS', [
        'dbname' => 'mycontacts',
        'user' => 'root',
        'password' => 'root',
        'server' => 'localhost'
    ]);
}
