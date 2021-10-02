<?php 

define('DOMAINE', 'ContactsManager');

return [
    ['get', '/' . DOMAINE . '/', 'Public#index', 'home'],
    ['match', '/' . DOMAINE . '/Connexion', 'User#login', 'login'],
    ['match', '/' . DOMAINE . '/Inscription', 'User#register', 'register'],
    ['get', '/' . DOMAINE . '/Profil', 'User#profil', 'profil'],
    ['get', '/' . DOMAINE . '/Contacts/[i:id]', 'Contact#show', 'contactPage'],
    ['match', '/' . DOMAINE . '/Contactez-nous', 'Public#contact', 'contact'],
    ['get', '/' . DOMAINE . '/A-propos', 'Public#about', 'about']
];