# Docker php (PHP7-FPM - NGINX - MySQL)

This gives you everything you need to develop a simple PHP web app with MySQL database.

This complete stack runs with docker and [docker-compose (1.7 or higher)](https://docs.docker.com/compose/).

## Installation

1. Create a `.env` from the `.env.dist` file

    ```bash
    cp .env.dist .env
    ```


2. Build/run containers with (with and without detached mode)

    ```bash
    $ docker-compose build
    $ docker-compose up -d
    ```

3. Update your system host file (add project.local)

    ```bash
    # UNIX only: get containers IP address and update host (replace IP according to your configuration) (on Windows, edit C:\Windows\System32\drivers\etc\hosts)
    $ sudo echo $(docker network inspect bridge | grep Gateway | grep -o -E '([0-9]{1,3}\.){3}[0-9]{1,3}') "project.local" >> /etc/hosts
    ```

    **Note:** For **OS X**, please take a look [here](https://docs.docker.com/docker-for-mac/networking/) and for **Windows** read [this](https://docs.docker.com/docker-for-windows/#/step-4-explore-the-application-and-run-examples) (4th step).

5. Create your test file
    ```bash
    $ touch project/public/index.php
    ```

    ```php
    $options = [
        \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        \PDO::ATTR_EMULATE_PREPARES => false,
    ];
    $databaseConnection = new \PDO('mysql:host=db;dbname=project;charset=utf8mb4', 'project', 'project', $options);
    
    $queryHandle = $databaseConnection->query('SELECT NOW() AS currentTime');
    $queryHandle->execute();
    $currentTime = $queryHandle->fetch(\PDO::FETCH_ASSOC);
    
    echo sprintf('<div class="center">Current time from the database server is: %1$s</div>', $currentTime['currentTime']);
    
    $queryHandle->closeCursor();
    
    phpinfo();
    ```

4. Enjoy :-)

## Usage

Just run `docker-compose up -d`, then:

* App: visit [project.local](http://project.local)  
* Logs (files location): logs/nginx

## How it works?

Have a look at the `docker-compose.yml` file, here are the `docker-compose` built images:

* `db`: This is the MySQL database container.
* `php`: This is the PHP-FPM container in which the application volume is mounted.
* `nginx`: This is the Nginx webserver container in which application volume is mounted too.

This results in the following running containers:

```bash
$ docker-compose ps
           Name                          Command               State              Ports            
--------------------------------------------------------------------------------------------------
web-project_db_1            /entrypoint.sh mysqld            Up      0.0.0.0:3306->3306/tcp      
web-project_nginx_1         nginx                            Up      443/tcp, 0.0.0.0:80->80/tcp
web-project_php_1           php-fpm                          Up      0.0.0.0:9000->9000/tcp      
```

## Useful commands

```bash
# bash commands
$ docker-compose exec php bash

# Composer (e.g. composer update)
$ docker-compose exec php composer update

# Same command by using alias
$ docker-compose exec php bash

# Retrieve an IP Address (here for the nginx container)
$ docker inspect --format '{{ .NetworkSettings.Networks.dockersymfony_default.IPAddress }}' $(docker ps -f name=nginx -q)
$ docker inspect $(docker ps -f name=nginx -q) | grep IPAddress

# MySQL commands
$ docker-compose exec db mysql -uroot -p"root"

# Check CPU consumption
$ docker stats $(docker inspect -f "{{ .Name }}" $(docker ps -q))

# Delete all containers
$ docker rm $(docker ps -aq)

# Delete all images
$ docker rmi $(docker images -q)
```

## FAQ

* Got this error: `ERROR: Couldn't connect to Docker daemon at http+docker://localunixsocket - is it running?
If it's at a non-standard location, specify the URL with the DOCKER_HOST environment variable.` ?  
Run `docker-compose up -d` instead.

* Permission problem? See [this doc (Setting up Permission)](http://symfony.com/doc/current/book/installation.html#checking-symfony-application-configuration-and-setup)

* How to config Xdebug?
Xdebug is configured out of the box!
Just config your IDE to connect port  `9001` and id key `PHPSTORM`