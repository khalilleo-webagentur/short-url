#!/bin/bash

php bin/console cache:clear

echo '----------------------------------------'

composer cc && composer upgrade -o

echo '----------------------------------------'

pwd 

echo '----------------------------------------'

echo 'http://localhost:8080/login'

# echo '----------------------------------------'

# php bin/console app:new-admin

echo '----------------------------------------'

php -S localhost:8080 -t public
