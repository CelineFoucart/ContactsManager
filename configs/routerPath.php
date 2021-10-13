<?php 

define('HOME_PATH', '/ContactsManager/');

return [
    ['get',  HOME_PATH, 'Public#index', 'home'],
    ['match', HOME_PATH . 'Connexion', 'User#login', 'login'],
    ['match', HOME_PATH . 'Inscription', 'User#register', 'register'],
    ['get', HOME_PATH . 'Profil', 'User#profil', 'profil'],
    ['get', HOME_PATH . 'Contacts/[i:id]', 'Contact#show', 'contactPage'],
    ['match', HOME_PATH . 'Contactez-nous', 'Public#contact', 'contact'],
    ['get', HOME_PATH . 'A-propos', 'Public#about', 'about']
];