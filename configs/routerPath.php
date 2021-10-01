<?php 

define('DOMAINE', 'ContactsManager');

return [
    ['get', '/' . DOMAINE . '/', 'Public#index', 'home'],
    ['match', '/' . DOMAINE . '/Login', 'User#login', 'login'],
    ['get', '/' . DOMAINE . '/Contact/[i:id]', 'Contact#show', 'contact']
];