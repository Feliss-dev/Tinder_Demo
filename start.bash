#!/bin/bash

php artisan config:clear
php artisan storage:link

php artisan serve --host 0.0.0.0
