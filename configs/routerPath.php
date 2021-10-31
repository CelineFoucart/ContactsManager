<?php 

define('HOME_PATH', '/ContactsManager/');

return [
    ['get',  HOME_PATH, 'Public#index', 'home'],
    ['match', HOME_PATH . 'Connexion', 'User#login', 'login'],
    ['match', HOME_PATH . 'Inscription', 'User#register', 'register'],
    ['get', HOME_PATH . 'Profil', 'User#profil', 'profil'],
    ['get', HOME_PATH . 'Contacts', 'Contact#list', 'contactList'],
    ['get', HOME_PATH . 'Contacts/[i:id]', 'Contact#show', 'contactShow'],
    ['match', HOME_PATH . 'Contacts/[i:id]/Editer', 'Contact#edit', 'contactEdit'],
    ['match', HOME_PATH . 'Contacts/[i:id]/Supprimer', 'Contact#delete', 'contactDelete'],
    ['match', HOME_PATH . 'Contactez-nous', 'Public#contact', 'contact'],
    ['get', HOME_PATH . 'A-propos', 'Public#about', 'about']
];