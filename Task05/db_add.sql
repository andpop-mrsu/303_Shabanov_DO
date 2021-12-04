PRAGMA foreign_keys = ON;

INSERT INTO users(name,lastname,email,register_date,gender,occupation_id)
values("Danila","Shabanov","shabanovdo2021@yandex.ru",DATE("now"),"male",(SELECT id FROM occupations as o WHERE o.title = "student"));

INSERT INTO users(name,lastname,email,register_date,gender,occupation_id)
values("Danila","Svetilnikov","student1@ya.ru",DATE("now"),"male",(SELECT id FROM occupations as o WHERE o.title = "student"));

INSERT INTO users(name,lastname,email,register_date,gender,occupation_id)
values("Nikita","Utkin","student2@ya.ru",DATE("now"),"male",(SELECT id FROM occupations as o WHERE o.title = "student"));

INSERT INTO users(name,lastname,email,register_date,gender,occupation_id)
values("Alina","Ruzaeva","student3@ya.ru",DATE("now"),"female",(SELECT id FROM occupations as o WHERE o.title = "student"));

INSERT INTO users(name,lastname,email,register_date,gender,occupation_id)
values("Alexandra","Chernova","student4@ya.ru",DATE("now"),"female",(SELECT id FROM occupations as o WHERE o.title = "student"));


INSERT INTO movies(title, year) 
values("Wrath of man", 2021);

INSERT INTO movies(title, year) 
values("Nobody", 2021);

INSERT INTO movies(title, year) 
values("Tom & Jerry", 2021);


INSERT INTO ratings(user_id, movie_id,rating,"timestamp")
values(
(SELECT id FROM users WHERE users.email = "shabanovdo2021@yandex.ru"), 
(SELECT id FROM movies WHERE movies.title = "Wrath of man" and movies.year = 2021),
4.5,
strftime('%s','now')); 

INSERT INTO ratings(user_id, movie_id,rating,"timestamp")
values(
(SELECT id FROM users WHERE users.email = "shabanovdo2021@yandex.ru"), 
(SELECT id FROM movies WHERE movies.title = "Nobody" and movies.year = 2021),
5,
strftime('%s','now')); 

INSERT INTO ratings(user_id, movie_id,rating,"timestamp")
values(
(SELECT id FROM users WHERE users.email = "shabanovdo2021@yandex.ru"), 
(SELECT id FROM movies WHERE movies.title = "Tom & Jerry" and movies.year = 2021),
4.8,
strftime('%s','now')); 