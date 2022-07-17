CREATE DATABASE IF NOT EXISTS my_website;
FLUSH PRIVILEGES;
USE my_website;
create table comments (
	comment varchar(1000) not null,
	day_posted varchar(12) not null,
	name varchar(20) not null
);
