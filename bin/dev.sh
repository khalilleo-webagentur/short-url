#!/bin/bash

rm .env.local.php
rm App.zip
touch storage/mailer/mailer.html
rm storage/logs/*.log
php bin/console cache:clear
composer cc && composer upgrade -o

symfony local:server:start --no-tls
