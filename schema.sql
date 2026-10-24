--creates parkingdb if parkingdb doesn't allready exist
--CREATE DATABASE IF NOT EXISTS parkingdb;
--select database to add table
USE parkingdb;

CREATE TABLE IF NOT EXISTS parking (
car INT AUTO_INCREMENT PRIMARY KEY,
plate VARCHAR(100),
make VARCHAR(100),
color VARCHAR(100),
year INT,
lot INT
);
