Pour ce projet , il faut installer symfony 

Prérequis
Avant de commencer l'installation, assurez-vous d'avoir Apache, php , mysql et composer d'installer.

Sur windows :
Il suffit d'installer wamp : https://www.wampserver.com/fr/
Aller dans php.ini et assurer d'avoir les extensions php activé :
curl et pdo_mysql ( le reste devrait déjà être activé )






Sur linux :
Il faut installer la stack LAMP
Puis s'assurer également d'avoir les extensions activé dans php.ini ainsi que d'avoir php 8.2.18

Il faut également installer symfony : suiviez la documentation ici
https://symfony.com/download

####################################################################################################
ATTENTION , PEUT ETRE IMPORTANT , AU MOINDRE BUG RENCONTRER , VERIFIER CECI EN PREMIER
Assurez-vous également d'utiliser php 8.2.18 ( la version php dans lequel le projet a été réalisé )
Vérifier que votre système peut installer symfony 7 ( le projet a été réaliser sous symfony 7)
####################################################################################################

Cloner le projet
une fois dans le répertoire du projet,
Ouvrez un terminal et executer "composer install".
Ensuite, copier coller le .env

Renommer ce fichier .env.local

Commenter la ligne : DATABASE_URL="postgresql://app:!ChangeMe!@127.0.0.1:5432/app?serverVersion=16&charset=utf8" (en ajoutant un # devant )

Décommenter la ligne ( en supprimant le # ) : # DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/app?serverVersion=10.11.2-MariaDB&charset=utf8mb4"

Modifier cette ligne avec votre mot de passe de base de donnée et votre nom d'utilisateur :

DATABASE_URL="mysql://utilisateur_base_de_donnee:mdp_base_de_donnee@127.0.0.1:3306/partiel-s2-back-theo?charset=utf8mb4"

Ensuite créer la base de donnée "partiel-s2-back-theo" en précisant ( par exemple si vous utilisez phpmyadmin ) utf8mb4_unicode_ci

POUR BASE DE DONNEE
####################################################################################################
Executez cette commande dans le terminal : php bin/console doctrine:migrations:execute DoctrineMigrations\Version20240828125954

####################################################################################################

Une fois cela fait, pour lancer le projet, executez toujours dans un terminal :
symfony server:start

Et voilà ! vous pouvez à présent naviguer sur le site.


