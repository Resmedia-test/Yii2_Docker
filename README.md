# WIP! - work in progress

## This is docker core for Yii2 projects 

### Inside: 

Image name | Version
------------ | -------------
Nginx | nginx:stable-alpine
PHP-FPM | merorafael/php:7.1-fpm
MySql | mysql:5.7
Redis | bitnami/redis:latest
PhpMyAdmin | phpmyadmin/phpmyadmin

1. add to hosts file 127.0.0.1 testsite.docker // TODO Add to script

2. Add to docker (file sharing) in settings your project folder

### Commands:
```bash
cd src            // TODO put it to .sh 
php init          // TODO put it to .sh 
composer install  // TODO put it to .sh 

docker-compose up -d  // To start without log
docker-compose up     // To start with log

docker-compose down   // To stop
```
###
If errors with MYSQL:
```bash
docker-compose down
sudo docker volume rm $(sudo docker volume ls -qf dangling=true)
```

If errors with packages:
```bash
composer global require "fxp/composer-asset-plugin:@dev"
```

If can't start nginx and error is like `Bind for 0.0.0.0:80: unexpected error (Failure EADDRINUSE)` on mac os 
```
sudo killall httpd
```
*TODO*

- [ ]  Rebuild config file of nginx

- [ ]  Add ```./yii ``` commands to init.sh script
