#!/bin/bash

php artisan config:clear
php artisan serve --host 0.0.0.0
php artisan storage:link
