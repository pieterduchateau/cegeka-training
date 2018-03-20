mysql -u homestead -e "CREATE USER 'travel'@'travel' IDENTIFIED BY 'travel';"
mysql -u homestead -e "GRANT ALL PRIVILEGES ON travel.* TO 'travel'@'localhost';"
mysql -u homestead -e "FLUSH PRIVILEGES;"

