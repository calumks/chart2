/* localhost does not within a docker container. */
RENAME USER 'chartUser'@'localhost' TO 'chartUser'@'%';
RENAME USER 'makeUpAUserName'@'localhost' TO 'makeUpAUserName'@'%';
FLUSH PRIVILEGES;