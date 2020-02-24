# modula_test

 Site fait en symfony 4.4,
 ENVIRONNEMENT TECHNIQUE: 
 - SYMFONY 4.4
 - DOCTRINE
 - AJAX
 - BOOSTRAP
 - SQL/MYSL (phpMyAdmin)
 - MAMP
 
 Le projet comprend:
 - une page d'accueil/présentation
 - un formulaire de contact en requête AJAX avec google recaptcha (la requête SMTP n'a pas été géré pour le moment)
 - une partie administration pour afficher les messages reçus trier par ordre du plus récent au plus ancien
 
 
 Pour installer un projet symfony:
 - Faite un git clone
 - composer install

Le nom de la BDD est db_modula
Pour créer une base de donnée: php bin/console doctrine:database:create
Pour migrer une base de donnée: -php bin/console make:migration
                                -php bin/console doctrine:migrations:migrate
Pour envoyer les fixtures à la BDD: php bin/console doctrine:fixtures:load
Si cela ne fonctionne pas: doctrine:fixtures:load --append

Si vous avez des soucis pour installer le projet, n'hésitez pas à me contacter: lorenzato.christophe@gmail.com
                               
