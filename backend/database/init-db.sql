-- init-db.sql
CREATE DATABASE IF NOT EXISTS mini_ecom;
USE mini_ecom;

-- create mini_ecom user if not exists
CREATE USER IF NOT EXISTS 'mini_ecom'@'%' IDENTIFIED BY 'secret';

-- All permissions for the mini_ecom user
GRANT ALL PRIVILEGES ON mini_ecom.* TO 'mini_ecom'@'%';
GRANT CREATE, ALTER, DROP, INDEX, REFERENCES ON mini_ecom.* TO 'mini_ecom'@'%';

FLUSH PRIVILEGES;

SELECT 'Database mini_ecom created successfully!' as status;