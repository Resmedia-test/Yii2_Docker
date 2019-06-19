#WIP - Yii code refactoring

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

After start you will get divided by domains frontend and backend side, as well as ready-made sections with minimal 
functions: Users, Settings, Pages, Menu, Content, Articles, Comments, Cabinet, Search all content has a form of filling meta tags. 
Without styles. All in Russian lang.

--------------------------------------------

### 1 Go to directory where your sites

RUN
```bash
git clone git@github.com:Resmedia/Yii2_Docker.git
```

### 2 Add project folder to file sharing of Docker settings 

![img](https://image.prntscr.com/image/C5r_SEtQS5_XaMBe6tDtyQ.png)

### 3 Start

```bash
docker-compose up     // Start with log
docker-compose up -d  // Start without log
docker-compose down   // Stop all containers
```

### 4 Look to host file

```bash
docker exec -it core_php /bin/bash
./yii rbac/init
```

### 5 Office http://office.testsite.docker

```bash
127.0.0.1 testsite.docker
127.0.0.1 office.testsite.docker
```
if there you don't find it, write themselves to the end of the file

-------------------------------

### Office 
```bash
http://office.testsite.docker
Login: test@testsite.docker
Password: 1234567890
```

### MySqlAdmin

```bash
http://testsite.docker:8080
Login: root
Pass: toor
```
### Migration

```bash
docker exec -it core_php /bin/bash
./yii migrate/create <migration_name>
```

### Errors 

If Errors with MYSQL
```bash
docker-compose down
sudo docker volume rm $(sudo docker volume ls -qf dangling=true)
```

if errors with packages
```bash
composer global require "fxp/composer-asset-plugin:@dev"
```

If can't start nginx and error is like Bind for 0.0.0.0:80: unexpected error (Failure EADDRINUSE) on mac os

```bash
sudo lsof -iTCP -sTCP:LISTEN -n -P
sudo killall ......
```
Or you can change port in settings

### TODO 

- [ ] Save information in cabinet
