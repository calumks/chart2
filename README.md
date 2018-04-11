# chart2
Enable user to get a pdf of sheet music relevant to their instrument, given pdf's that have music for many instruments.

Implementation is via PHP / MySQL.

Demo is at https://tsbchart.000webhostapp.com/

To get going on linux, assuming your github project is in ~/chart2 ...
   
    # based on https://www.digitalocean.com/community/tutorials/how-to-install-linux-apache-mysql-php-lamp-stack-on-ubuntu-16-04
    # install apache
    sudo apt-get update
    sudo apt-get install apache2
    sudo apache2ctl configtest

    # get your computer's IP address and add it to the apache configuration file
    sudo apt-get install curl
    curl http://icanhazip.com

    sudo nano /etc/apache2/apache2.conf
         # add ServerName at bottom using computer's IP
    sudo systemctl restart apache2

    # get mysql
    sudo apt-get install mysql-server
#         input root password (make one up)
    mysql_secure_installation
    mysql -u root -p   (then enter root password)
        source ~/chart2/sql/chartBlank.sql # where chartBlank.sql is tableview specifications for blank database called chart2;
	GRANT ALL PRIVILEGES ON *.* TO 'makeUpAUserName'@'localhost' IDENTIFIED BY 'makeUpASatisfactoryPassword';
#   edit mysql-cred.php to change $user to makeUpAUserName, $password to makeUpASatisfactoryPassword, $database to chart2
 
    # install php and restart apache
    sudo apt-get install php libapache2-mod-php php-mcrypt php-mysql
    sudo systemctl restart apache2

    # copy php files to localhost area
    sudo mkdir /var/www/html/tsb
    sudo cp -r ~/chart2/* /var/www/html/tsb/
#       edit hasValidCookie() and hasAdminCookie() to immediately return true so that you don't need a cookie on your local version
    sudo vi /var/www/html/tsb/authenticate.php
#       add "return true;" at top of hasValidCookie() and hasAdminCookie() 

    # run in say firefox
    firefox http:/localhost/tsb


    # you may have to change permissions of /var/www/html/tsb/pdf and /var/www/html/tsb/output (to make them writeable)

