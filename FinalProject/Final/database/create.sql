create database travel;

create table travel_image (
    pid int not null AUTO_INCREMENT Primary Key,
    country varchar(15),
    province varchar(30),
    city varchar(30),
    sub_city varchar(30),
    orig_name varchar(30),
    orig_link varchar(200),
    comp_link varchar(200),
    descr varchar(300),
    prio int,
    tag1 varchar(20),
    tag2 varchar(20),
    tag3 varchar(20),
    tag4 varchar(20),
    tag5 varchar(20)
);

/*
Testing

INSERT INTO travel_image (country, province, city, sub_city, orig_link, comp_link, descr) 
VALUES ('United State', 'Alaska', 'Palmer', '', 
'https://taotravelbucket.s3.us-east-2.amazonaws.com/orig/us/alaska/palmer/Palmer.heic', 
'https://taotravelbucket.s3.us-east-2.amazonaws.com/comp/us/alaska/palmer/Palmer.jpeg', 
'Glacier hiking')
*/

