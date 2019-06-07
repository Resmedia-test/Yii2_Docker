# WIP! - work in progress

## This is docker environment with Yii2-advanced in it 

### Inside: 

Image name | Version
------------ | -------------
Nginx | nginx:stable-alpine
PHP-FPM | merorafael/php:7.1-fpm
MySql | mysql:5.7
Redis | bitnami/redis:latest
PhpMyAdmin | phpmyadmin/phpmyadmin

### Yii2-advanced

After start you will get divided by domains frontend and backend side, as well as ready-made sections with minimal functions: Users, Settings, Pages, Library, Menu, Content has a form of filling meta tags. All in Russian lang

### Start

1 - Add to your local machine in hosts file:

```bash
127.0.0.1 testsite.docker        //Frontend
127.0.0.1 office.testsite.docker //Backend
```

2 - Add to docker (file sharing) in settings your project folder

![Image of Docker](https://image.prntscr.com/image/C5r_SEtQS5_XaMBe6tDtyQ.png)

### Settings:

If you want to set all your settings then go to `/src/environments` for example `/prod/common/config/main-local.php`, but in this case you need to change all server settings
### Commands:
```bash
docker-compose up -d  // To start without log
docker-compose up     // To start with log

docker-compose down   // To stop
```

GO: [http://office.testsite.docker](http://office.testsite.docker)

Login: test@test.ru
Password: 1234567890

### PhpMyAdmin

Login: root
Password: toor

[http://testsite.docker:8080](http://testsite.docker:8080)

--------------------------------------------------------------------
### Mistakes that can be

If errors with MYSQL:
```bash
docker-compose down                                              // Stop all containers
sudo docker volume rm $(sudo docker volume ls -qf dangling=true) // Clen all old volume
```

If errors with packages:
```bash
composer global require "fxp/composer-asset-plugin:@dev"
```

If can't start nginx and error is like `Bind for 0.0.0.0:80: unexpected error (Failure EADDRINUSE)` on mac os 
```
sudo lsof -iTCP -sTCP:LISTEN -n -P
sudo killall httpd // maybe :-)
Or you can change port in settings
```
*TODO*

- [ ]  Refactoring Yii2 code
