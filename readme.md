# Projet ECF pour Studi

## Aplications et outils

Les applications et outils utilisés pour le développement de cette application sont listés ici avec leur lien de téléchargement ou la ligne de commande à effectuer :

- Visual Studio Code. [Peut se télécharger ici]([https://](https://code.visualstudio.com/download))
- Scoop

  ``` PS
  Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
  irm get.scoop.sh | iex
  ```

- Symfony

  ``` PS
  scoop install symfony-cli
  ```

- Composer
  
  ``` PS
  sudo mv composer.phar /usr/local/bin/composer
  ```

- Git

  ``` PS
  winget install --id Git.Git -e --source winget
  ```

- Xampp. [Lien](https://www.apachefriends.org/fr/index.html)
  - Il est nécessaire d'installer Apache et MySQL pour le bon fonctionnement de l'application

## Installation en local

Une fois tous les logiciels et outils téléchargés il faut suivre la procédure suivante :

- Lancer l'exécution de Xampp et vérifier que Apache ainsi que mySQL sont en cours d'exécution
- Création d'un nouveau dossier à l'endroit souhaité
- Ouverture du dossier grâce à Visual Studio Code
- Exécution des commandes suivantes via le terminal de Visual Studio Code

  ``` cmd
  // Récupération des fichiers du dépot Git
  git remote https://github.com/NicolasBegin91/Studi_ECF.git .

  // Mise à jour des bibliothèques locales
  composer update

  // Création et mise à jour de la base de donnée locale.
  // Pour changer la BDD de destination il faut modifier le champ DATABASE du fichier .env et remplacer "ECF_Begin"
  php bin/console doctrine:database:create
  symfony console doctrine:migrations:migrate

  // Démarrage du serveur local
  symfony server:start -d
  ```

- L'accès au site local se fait généralement via l'url <http://127.0.0.1:8000> dans le cas contraire l'url utilisée sera affichée dans la console du terminal dans un encadré vert.

## Création du premier admin

Si aucun utilisateur n'existe dans la base de donnée le lien "Créer un admin" sera disponible en haut à droite ou à la fin du menu sur mobile.

Ce lien ouvrira le formulaire de création d'utilisateur, par la suite réservé aux admins.
