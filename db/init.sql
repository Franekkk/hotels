DROP SCHEMA IF EXISTS `main`;
CREATE DATABASE `main` DEFAULT CHARACTER SET latin1 COLLATE latin1_bin;
DROP SCHEMA IF EXISTS `hotel_krakow`;
CREATE DATABASE `hotel_krakow` DEFAULT CHARACTER SET latin1 COLLATE latin1_bin;
DROP SCHEMA IF EXISTS `hotel_wroclaw`;
CREATE DATABASE `hotel_wroclaw` DEFAULT CHARACTER SET latin1 COLLATE latin1_bin;
DROP SCHEMA IF EXISTS `hotel_gdansk`;
CREATE DATABASE `hotel_gdansk` DEFAULT CHARACTER SET latin1 COLLATE latin1_bin;

CREATE USER 'hotel'@'%' IDENTIFIED BY 'hotel';
GRANT ALL ON *.* TO 'root'@'%';
GRANT ALL ON *.* TO 'hotel'@'%';
FLUSH PRIVILEGES;