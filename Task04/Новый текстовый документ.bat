
#!/bin/bash
chcp 65001

sqlite3 movies_rating.db < db_init.sql

echo "1. Найти все пары пользователей, оценивших один и тот же фильм. Устранить дубликаты, проверить отсутствие пар с самим собой. Для каждой пары должны быть указаны имена пользователей и название фильма, который они оценили."
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo "SELECT u1.name, u2.name ,T2.title FROM (SELECT U1.user_id as ID1, U2.user_id as ID2, U1.movie_id as IDM FROM(SELECT user_id, movie_id FROM ratings ) AS U1 JOIN (SELECT user_id, movie_id FROM ratings ) AS U2 ON U1.movie_id = U2.movie_id WHERE U1.user_id < U2.user_id) as T1 INNER JOIN (SELECT id, title FROM movies m) as T2 on T1.IDM = T2.id INNER JOIN users u1 on ID1 = u1.id INNER JOIN users u2 on ID2 = u2.id"
echo " "

echo "2. Найти 10 самых свежих оценок от разных пользователей, вывести названия фильмов, имена пользователей, оценку, дату отзыва в формате ГГГГ-ММ-ДД."
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo "SELECT title, name, rating ,date from (SELECT movie_id, user_id, rating , DATE(r.\"timestamp\", 'unixepoch') AS date FROM ratings r) INNER JOIN movies m ON m.id = movie_id INNER JOIN users u ON u.id = user_id GROUP by name ORDER BY date DESC LIMIT 10"
echo " "

echo "3. Вывести в одном списке все фильмы с максимальным средним рейтингом и все фильмы с минимальным средним рейтингом. Общий список отсортировать по году выпуска и названию фильма. В зависимости от рейтинга в колонке \"Рекомендуем\" для фильмов должно быть написано \"Да\" или \"Нет\"."
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo "SELECT title, year, CASE WHEN  max_rating = avg_rating THEN \"Yes\" ELSE \"No\" END AS rec FROM (SELECT title, year, AVG(r.rating) AS avg_rating, MAX(AVG(r.rating)) OVER() AS max_rating, MIN(AVG(r.rating)) OVER() AS min_rating FROM ratings r JOIN movies m ON r.movie_id = m.id GROUP BY r.movie_id) t1 WHERE avg_rating = max_rating OR avg_rating = min_rating ORDER BY year, title"
echo " "

echo "4. Вычислить количество оценок и среднюю оценку, которую дали фильмам пользователи-женщины в период с 2010 по 2012 год."
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo "SELECT COUNT(), AVG(r.rating) FROM ratings r INNER JOIN users u ON u.id  = r.user_id WHERE DATE(r.\"timestamp\", 'unixepoch') BETWEEN \"2010\" and \"2012\" AND u.gender = \"female\""
echo " "

echo "5. Составить список фильмов с указанием их средней оценки и места в рейтинге по средней оценке. Полученный список отсортировать по году выпуска и названиям фильмов. В списке оставить первые 20 записей."
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo "SELECT title, year, ROUND(AVG(r.rating),1) AS a_rating, RANK() OVER(ORDER BY AVG(r.rating) DESC) as rating_pos FROM movies m JOIN ratings r ON m.id = r.movie_id GROUP BY m.id ORDER BY year, title LIMIT 20"
echo " "

echo "6. Вывести список из 10 последних зарегистрированных пользователей в формате \"Фамилия Имя|Дата регистрации\" (сначала фамилия, потом имя)."
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo "SELECT replace(u.name , rtrim(u.name , replace(u.name , ' ', '')), '')||\"|\"||replace(u.name , ltrim(u.name , replace(u.name , ' ', '')), '') as name, u.register_date from users u order by u.register_date DESC LIMIT 10"
echo " "

echo "7. С помощью рекурсивного CTE составить таблицу умножения для чисел от 1 до 10."
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo "WITH RECURSIVE multiplication_table(ans, cnt) AS (SELECT '1x1=1',1 UNION ALL SELECT CAST ((cnt / 10 + 1) AS text) ||'x'|| CAST((cnt % 10 + 1) AS text) ||'='|| CAST(((cnt/10 + 1) * (cnt%10 + 1)) AS text), cnt + 1 FROM multiplication_table LIMIT 100) SELECT ans FROM multiplication_table"
echo " "

echo "8. С помощью рекурсивного CTE выделить все жанры фильмов, имеющиеся в таблице movies (каждый жанр в отдельной строке)."
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo "WITH RECURSIVE get_genres(genres, str) AS (SELECT '', movies.genres || '|' FROM movies UNION ALL SELECT SUBSTRING(str, 0, INSTR(str, '|')), SUBSTRING(genres, INSTR(genres, '|') + 1) FROM get_genres WHERE str != '') SELECT genres FROM get_genres WHERE genres != '' GROUP BY genres"
echo " "