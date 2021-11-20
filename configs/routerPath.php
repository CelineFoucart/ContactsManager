<?php 

define('HOME_PATH', '/');

return [
    ['get',  HOME_PATH, 'Public#index', 'home'],
    ['match', HOME_PATH . 'Connexion', 'User#login', 'login'],
    ['match', HOME_PATH . 'Logout', 'User#logout', 'logout'],
    ['match', HOME_PATH . 'Inscription', 'User#register', 'register'],
    ['match', HOME_PATH . 'Profil', 'User#profil', 'profil'],
    ['get', HOME_PATH . 'Contacts', 'Contact#index', 'contact.index'],
    ['match', HOME_PATH . 'Contacts/Ajouter', 'Contact#create', 'contact.create'],
    ['get', HOME_PATH . 'Contacts/[i:id]', 'Contact#show', 'contact.show'],
    ['match', HOME_PATH . 'Contacts/[i:id]/Editer', 'Contact#edit', 'contact.edit'],
    ['match', HOME_PATH . 'Contacts/[i:id]/Supprimer', 'Contact#delete', 'contact.delete'],
    ['match', HOME_PATH . 'Contactez-nous', 'Public#contact', 'contact'],
    ['get', HOME_PATH . 'A-propos', 'Public#about', 'about'],
    ['get', HOME_PATH . 'Admin', 'Admin#dashboard', 'admin.index'],
    ['get', HOME_PATH . 'Admin/Users', 'AdminUser#index', 'admin.users'],
    ['match', HOME_PATH . 'Admin/Users/[i:id]', 'AdminUser#edit', 'admin.users.edit'],
    ['match', HOME_PATH . 'Admin/Users/Create', 'AdminUser#create', 'admin.users.create'],
    ['match', HOME_PATH . 'Admin/Users/[i:id]/Delete', 'AdminUser#delete', 'admin.users.delete'],
    ['get', HOME_PATH . 'Admin/Contacts', 'AdminContact#index', 'admin.contacts'],
    ['match', HOME_PATH . 'Admin/Contacts/Create', 'AdminContact#create', 'admin.contacts.create'],
    ['match', HOME_PATH . 'Admin/Contacts/[i:id]', 'AdminContact#edit', 'admin.contacts.edit'],
    ['match', HOME_PATH . 'Admin/Contacts/[i:id]/Delete', 'AdminContact#delete', 'admin.contacts.delete']
];