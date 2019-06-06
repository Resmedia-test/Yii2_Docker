#!/bin/bash

for i in "$@"
do
case $i in
    -e=*|--env=*)
    ENV="${i#*=}"
    shift
    ;;
    -b=*|--build=*)
    BUILD="${i#*=}"
    shift
    ;;
esac
done

#init default params
if ! [[ "$ENV" =~ ^(local|docker)$ ]]; then
    ENV=local
fi

#switch next operations
case "$ENV" in
    "local")
    ;;
    "docker")
        echo "$HOST_IP host.docker.internal" >> /etc/hosts
        wget https://raw.githubusercontent.com/composer/getcomposer.org/1b137f8bf6db3e79a38a5bc45324414a6b1f9df2/web/installer -O - -q | php -- --quiet  > /dev/null
        ./composer.phar install --no-interaction --prefer-dist --quiet
        ./init --env=Docker --overwrite=y
        ./yii migrate <<< "yes"
    ;;
esac