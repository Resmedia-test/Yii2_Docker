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

1. add to hosts file `127.0.0.1 testsite.docker` //Frontend

1. add to hosts file `127.0.0.1 office.testsite.docker` //Backend

2. Add to docker (file sharing) in settings your project folder

![Image of Docker](https://image.prntscr.com/image/C5r_SEtQS5_XaMBe6tDtyQ.png)

### Commands:
```bash
docker-compose up -d  // To start without log
docker-compose up     // To start with log

docker-compose down   // To stop
```

GO: [http://office.testsite.docker](http://office.testsite.docker)

Login: test@test.ru
Password: 1234567890

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
sudo lsof -iTCP -sTCP:LISTEN -n -P
sudo killall httpd // maybe :-)
```
*TODO*

- [ ]  Refactoring Yii2 code
