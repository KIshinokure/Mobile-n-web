-- ЛР 2. Вариант 5: страница с новостями и счётчиком «Like»
-- Схема базы данных для MySQL / MariaDB

CREATE DATABASE IF NOT EXISTS news_portal
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE news_portal;

DROP TABLE IF EXISTS news_likes;
DROP TABLE IF EXISTS news;

-- Новости. likes_count — денормализованный счётчик лайков,
-- чтобы не считать COUNT(*) при каждом показе страницы.
CREATE TABLE news (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(255)  NOT NULL,
    body        TEXT          NOT NULL,
    author      VARCHAR(100)  NOT NULL DEFAULT 'Редакция',
    created_at  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    likes_count INT UNSIGNED  NOT NULL DEFAULT 0
) ENGINE=InnoDB;

-- Кто какую новость лайкнул. visitor_id хранится в cookie посетителя,
-- составной первичный ключ не даёт лайкнуть одну новость дважды.
CREATE TABLE news_likes (
    news_id    INT UNSIGNED NOT NULL,
    visitor_id CHAR(32)     NOT NULL,
    liked_at   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (news_id, visitor_id),
    CONSTRAINT fk_likes_news FOREIGN KEY (news_id)
        REFERENCES news (id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO news (title, body, author, created_at) VALUES
('В университете открылась новая лаборатория веб-разработки',
 'Студенты направления «Мобильные и веб-технологии» получили доступ к новым рабочим местам с предустановленными MySQL, PHP и Node.js.',
 'Пресс-служба', '2026-10-26 10:15:00'),
('Хакатон по PHP-фреймворкам пройдёт в ноябре',
 'Команды из трёх человек за 24 часа разработают сервис на Laravel или Yii2. Регистрация открыта до 10 ноября.',
 'Студсовет', '2026-10-28 14:30:00'),
('Вышла новая версия MariaDB',
 'В релизе улучшена производительность InnoDB и расширена поддержка JSON-функций. Обновление уже доступно в репозиториях.',
 'IT-новости', '2026-10-29 09:00:00'),
('AJAX: обновляем страницу без перезагрузки',
 'Технология позволяет отправлять запросы к серверу в фоне и менять только нужную часть страницы — например, счётчик лайков под новостью.',
 'Кафедра ИТ', '2026-10-30 08:45:00');

-- Пользователь приложения с минимально необходимыми правами
CREATE USER IF NOT EXISTS 'news_app'@'localhost' IDENTIFIED BY 'news_app_pass';
GRANT SELECT, INSERT, UPDATE, DELETE ON news_portal.* TO 'news_app'@'localhost';
FLUSH PRIVILEGES;
