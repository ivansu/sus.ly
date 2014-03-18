SUS: Simple URL Shortener
=========

Требования к системе
-----

Для того, чтобы всё корректно работало понадобится LAMP:

  * любой линукс;
  * apache2;
  * MySQL 5.1 и выше;
  * PHP 5.3 и выше.


Установка
-----------

1. Склонировать проект 
git clone https://github.com/ivansu/sus.ly

2. Перейти в папку проекта
cd sus.ly

3. Установить composer
curl -sS https://getcomposer.org/installer | php

4. Стянуть зависимости
php composer.phar update

5. Настроить DocRoot на www

6. Необязательно, но может понадобиться - настроить права на папки с ассетами и логами:
chmod 777 www/assets/
chmod 777 www/protected/runtime/

7. Создать MySQL БД, прописать параметры доступа к БД в конфиги:
www/protected/config/console.php
www/protected/config/main.php

8. Прогнать миграции (находясь в директории www/protected)
cd www/protected
./yiic migrate
