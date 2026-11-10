<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['В университете открылась новая лаборатория веб-разработки',
             'Студенты направления «Мобильные и веб-технологии» получили доступ к новым рабочим местам.',
             'Пресс-служба', '2026-10-26 10:15:00'],
            ['Хакатон по PHP-фреймворкам пройдёт в ноябре',
             'Команды за 24 часа разработают сервис на Laravel или Yii2.',
             'Студсовет', '2026-10-28 14:30:00'],
            ['Маршрутизация в Laravel',
             'Все адреса приложения описываются в routes/web.php: метод HTTP, URI и обработчик.',
             'Кафедра ИТ', '2026-10-30 08:45:00'],
        ];

        foreach ($items as [$title, $body, $author, $date]) {
            News::create(['title' => $title, 'body' => $body, 'author' => $author, 'created_at' => $date]);
        }
    }
}
