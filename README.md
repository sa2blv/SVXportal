# SVXportal

A web portal for SVXlink reflector

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites


```
php5 > 
mysql
apache2 or ngnx


```

Step 1: Update your system

sudo apt-get update
Step 2: Install Mysql

sudo apt-get install mysql-server mysql-client libmysqlclient-dev
Step 3: Install Apache server

sudo apt-get install apache2 apache2-doc apache2-npm-prefork apache2-utils libexpat1 ssl-cert
Step 4: Install PHP (php7.0 latest version of PHP)

sudo apt-get install libapache2-mod-php7.0 php7.0 php7.0-common php7.0-curl php7.0-dev php7.0-gd php-pear php-imagick php7.0-mcrypt php7.0-mysql php7.0-ps php7.0-xsl
Step 5: Install Phpmyadmin(for database)

sudo apt-get install phpmyadmin

```

### Installing

Login to phpmyadmin and create user for svxreflektor with a database
install the sql file in the sql folder
and add username and password to config.php

add adress to svxrefector proxy to config.php

edit the $url parameter in the reflektorproxy/index.php director to match the 
SVXreflector server.





## License


