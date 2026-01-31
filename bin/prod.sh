#!/bin/bash

clear
rm storage/logs/*.log
rm storage/mailer/*.html
composer dump-env prod
composer cc
composer install --no-dev --optimize-autoloader
APP_ENV=prod APP_DEBUG=0 php bin/console cache:clear

rm -rf var

zip -r App.zip . -x '.git/*' -x '.idea/*' -x 'src/Command/*' -x 'App.zip' -x 'storage/sql/*.sql' -x '.env' -x '.env.dev' -x '.gitignore' -x '.editorconfig' -x 'CHANGELOG.md' -x 'bin/*.sh' -x 'LICENSE' -x 'README.md' -x 'SECURITY.md' -x 'todo.md'

echo 'Done!'
