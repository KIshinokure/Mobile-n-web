-- ЛР 4. Интерфейс СУБД MySQL. Предоставление доступа и привилегий.
-- Выполняется в консоли:  mysql -u root
-- \T включает протокол выполняемых команд (tee) в файл

\T lab4_protocol.txt

SELECT VERSION() AS version, CURRENT_USER() AS current_user_name;

-- Список баз данных на сервере
SHOW DATABASES;

-- Выбираем системную БД mysql с таблицами привилегий
USE mysql;
SHOW TABLES;

-- Структура таблиц привилегий user и db
SHOW COLUMNS FROM user;
SHOW COLUMNS FROM db;

-- Способ из методички (MySQL 5.x): прямая вставка в таблицу user.
-- В MySQL 8 / MariaDB 10.4+ user — представление (VIEW) над global_priv,
-- поэтому оператор завершится ошибкой; правильный способ — CREATE USER / GRANT.
INSERT INTO user (host, user, password, ssl_cipher, x509_issuer, x509_subject)
VALUES ('localhost', 'student5', PASSWORD('Stud5_pass'), '', '', '');

-- Регистрация своего пользователя и назначение ему прав
CREATE USER 'student5'@'localhost' IDENTIFIED BY 'Stud5_pass';
GRANT ALL PRIVILEGES ON *.* TO 'student5'@'localhost' WITH GRANT OPTION;
FLUSH PRIVILEGES;

SELECT host, user, password FROM user;

-- Привилегии на уровне конкретной БД через таблицу db
CREATE DATABASE IF NOT EXISTS library CHARACTER SET utf8mb4;
CREATE USER 'mary'@'localhost' IDENTIFIED BY 'Mary_pass1';
INSERT INTO db (host, user, db, select_priv, insert_priv, update_priv)
VALUES ('localhost', 'mary', 'library', 'Y', 'Y', 'Y');
FLUSH PRIVILEGES;

SELECT host, db, user, select_priv, insert_priv, update_priv, delete_priv, create_priv, drop_priv
  FROM db;

SHOW GRANTS FOR 'student5'@'localhost';
SHOW GRANTS FOR 'mary'@'localhost';

-- Небольшая таблица в library для демонстрации дампа и копирования БД
USE library;
CREATE TABLE books (
    id     INT AUTO_INCREMENT PRIMARY KEY,
    title  VARCHAR(200) NOT NULL,
    author VARCHAR(100) NOT NULL,
    year   SMALLINT
);
INSERT INTO books (title, author, year) VALUES
  ('Изучаем SQL', 'Алан Бьюли', 2021),
  ('MySQL. Оптимизация производительности', 'Бэрон Шварц', 2018),
  ('PHP и MySQL. Разработка веб-приложений', 'Дмитрий Котеров', 2023);
SELECT * FROM books;

\t
