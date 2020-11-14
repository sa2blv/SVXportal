# SVXportal

A web portal for SVXlink reflector



## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites


```
* php5 and larger
* mysql
* apache2 or ngnx
* crontab
* screen 
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

Alternativly use the combined lamp server 

sudo tasksel install lamp-server

```

### Installing

1. clone or donload the files to your www location
   ex /var/www/html.

2. run the install.php from your browser
this requirer that you hav setup an database / user in mysql 

3. add this line to your corntab

@reboot sleep 60 && screen  -d -m bash -c  'cd /var/www/ ; watch -n 1  php logdeamon.php;'
@reboot sleep 60 && screen  -d -m bash -c  'cd /var/www/ ; watch -n 20  php station_heartbeat.php;'


please note that the folder /var/www/ shall be your instalation folder.

4. reboot or manualy start 

screen  -d -m bash -c  'cd /var/www/ ; watch -n 20  php station_heartbeat.php;'
screen  -d -m bash -c  'cd /var/www/ ; watch -n 1  php logdeamon.php;'







## License


