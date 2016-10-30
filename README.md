fudcom
======

A Symfony project created on October 9, 2016, 9:33 am.

Commandes installation :
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --dump-sql
php bin/console doctrine:schema:update --force
instllation composer .exe pour Windows téléchargeable
composer require --dev doctrine/doctrine-fixtures-bundle
php bin/console doctrine:fixtures:load
php bin/console cache:clear
php bin/console cache:clear --env=prod
heroku config:set SYMFONY_ENV=prod