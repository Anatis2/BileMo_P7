# BileMo_P7

Bilemo est un Web service à destination des plateformes de vente de téléphonie mobile.

Son principal objectif est de permettre à toute plateforme qui le souhaite d'avoir accès au catalogue de l'entreprise BileMo.

Il correspond au projet 7 du parcours de développeur d'applications PHP/Symfony de OpenClassrooms.

Etapes d'installation
========================

1) Installez les librairies, en tapant la commande "composer install"

2) Si besoin, modifiez les données de configuration du .env (notamment le nom d'utilisateur et le mot de passe, dans DATABASE_URL)

3) Créez la base de données, en tapant la commande "php bin/console doctrine:database:create"

4) Créez le schéma de la base de données, grâce à la commande "php bin/console doctrine:schema:update --force"

5) Insérez les données de test, avec la commande "php bin/console doctrine:fixtures:load"

Utilisation
============

Toutes les routes doivent être préfixées de la manière suivante : http://localhost:8000/api

Accéder à la documentation
----------------------------

GET /doc

Inscription
--------------

POST /register

Les détails de l’inscription devront être envoyés en format JSON. 
Les champs email et password sont obligatoires.

Exemple :

{
    "name": "Société 1",
    "email": "test.societe01@gmail.com",
    "password": "lkjf2dmlskj"
}

Authentification
-------------------------

L’authentification est obligatoire pour accéder à l’ensemble des fonctionnalités de l’API

Pour s’authentifier :

POST /login_check

Les détails du login devront être envoyés en format JSON. 
L’ensemble des champs (username, password) est obligatoire.
L’username correspond en fait à l’email du client.

Exemple :

{
    "username": "test2@gmail.com",
    "password": "test2"
}

L’authentification nous permet de récupérer un token, qu’il faudra transmettre à chaque requête, en type Bearer Token.

Lister l’ensemble des produits de Bilemo
-------------------------------------------

GET /phones

Utilisation de la pagination pour le catalogue de téléphones
---------------------------------------------------------------

GET /phones?page={numero}

Exemple : GET /phones?page=2

Consulter les détails d’un produit BileMo
---------------------------------------------------------------

GET /phones/{id}

Exemple : GET /phones/17

Consulter la liste des utilisateurs
---------------------------------------------------------------

GET /users

Utilisation de la pagination pour la liste d'utilisateurs
---------------------------------------------------------------

GET /users?page={numero}

Exemple : GET /users?page=2

Consulter les détails d’un utilisateur
---------------------------------------------------------------

GET /users/{id}

Exemple : GET /users/12

Ajouter un nouvel utilisateur
---------------------------------------------------------------

POST /users

Les détails des utilisateurs devront être envoyés en format JSON.

Seuls les champs surname et email sont obligatoires.

Exemple :

{
     "surname": "Test",
     "firstname": "Utilisateur",
     "email": "test.utilisateur@gmail.com"
}

Supprimer un utilisateur
-----------------------------

DELETE /users/{id}

Exemple : DELETE /users/12