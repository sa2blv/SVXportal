# SVXportal

![Test Image 1](https://svxportal.sm2ampr.net/portalimage_git.png)

A web portal for SVXlink reflector server
for demo se https://svxportal.sm2ampr.net/


## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites


```
* php7.4 or larger
* MySQL
* Apache2 or ngnx
* crontab
* screen
* PHP-FPM Recomended



```

### for installing check this guide 


https://www.granudden.info/Ham/Repeatrar/Dokument/SvxPortal2.5.pdf

https://sk7rfl.se/doc/SvxPortal2.5.pdf



### Installing advance



4. Open your browser ang go to 
http://yoururl/install.php and follow the instructions.
if yo want to upgrade from an previus version use
http://yoururl/update.php



##New cronjob in verson 2.6
#add to /etc/crontab
16 0 * * *       user    php /var/www/Preprosess/calculate_date.php
20 0 * * *       user    php /var/www/Preprosess/calculate_mounth.php
#optional
#16 0 * * *       user    /var/www/Preprosess/run_calulation.sh





```

## License




