# SVXportal

![Test Image 1](https://svxportal.sm2ampr.net/portalimage_git.png)

A web portal for SVXlink reflector server
for demo se https://svxportal.sm2ampr.net/


## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites


```
* php5 or larger
* MySQL
* Apache2 or ngnx
* crontab
* screen```

Step 1: Update your system
```
sudo apt-get update
Step 2: Install Mysql

sudo apt-get install mysql-server mysql-client libmysqlclient-dev
Step 3: Install Apache server

sudo apt-get install apache2 apache2-doc apache2-npm-prefork apache2-utils libexpat1 ssl-cert
Step 4: Install PHP 


Step 5: Install Phpmyadmin(for database not requierd )


```

### for installing check this guide 


https://www.granudden.info/Ham/Repeatrar/Dokument/SvxPortal2.5.pdf



### Installing advance

1. clone or donload the files to your www location
   ex /var/www/html.

2. run the install.php from your browser
this requirer that you hav setup an database / user in mysql 

3. Add a user to mysql "if you self host" manualy you can use php myadmin
   
  mysql
   $ mysql> CREATE DATABASE Svxportal;
   $ mysql> CREATE USER 'Svxportal'@'localhost' IDENTIFIED WITH mysql_native_password BY 'Change me password';
   $ mysql>  GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, INDEX, DROP, ALTER, CREATE TEMPORARY TABLES, LOCK TABLES ON Svxportal.* TO 'Svxportal'@'localhost';

"this is an examle of Mysql user setup"

4. Open your browser ang go to 
http://yoururl/install.php and follow the instructions.
if yo want to upgrade from an previus version use
http://yoururl/update.php







5. reboot or manualy start 

screen  -d -m bash -c  'cd /var/www/ ; watch -n 20  php station_heartbeat.php;'
screen  -d -m bash -c  'cd /var/www/ ; watch -n 1  php logdeamon.php;'


```

## License




