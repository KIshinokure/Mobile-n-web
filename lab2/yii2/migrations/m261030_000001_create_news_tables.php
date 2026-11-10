<?php

use yii\db\Migration;

class m261030_000001_create_news_tables extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%news}}', [
            'id'          => $this->primaryKey()->unsigned(),
            'title'       => $this->string(255)->notNull(),
            'body'        => $this->text()->notNull(),
            'author'      => $this->string(100)->notNull()->defaultValue('Редакция'),
            'created_at'  => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'likes_count' => $this->integer()->unsigned()->notNull()->defaultValue(0),
        ], 'CHARACTER SET utf8mb4 ENGINE=InnoDB');

        $this->createTable('{{%news_likes}}', [
            'news_id'    => $this->integer()->unsigned()->notNull(),
            'visitor_id' => $this->char(32)->notNull(),
            'liked_at'   => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ], 'CHARACTER SET utf8mb4 ENGINE=InnoDB');
        $this->addPrimaryKey('pk_news_likes', '{{%news_likes}}', ['news_id', 'visitor_id']);
        $this->addForeignKey('fk_news_likes_news', '{{%news_likes}}', 'news_id', '{{%news}}', 'id', 'CASCADE');

        $this->batchInsert('{{%news}}', ['title', 'body', 'author', 'created_at'], [
            ['Yii2: маршруты в urlManager', 'Правила вида «news/<id:\d+>» => «news/view» связывают URL и действия контроллера.', 'Кафедра ИТ', '2026-10-30 09:10:00'],
            ['Хакатон по PHP-фреймворкам пройдёт в ноябре', 'Команды за 24 часа разработают сервис на Laravel или Yii2.', 'Студсовет', '2026-10-28 14:30:00'],
            ['Вышла новая версия MariaDB', 'Улучшена производительность InnoDB.', 'IT-новости', '2026-10-29 09:00:00'],
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%news_likes}}');
        $this->dropTable('{{%news}}');
    }
}
