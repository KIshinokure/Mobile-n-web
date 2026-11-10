<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * @property int    $id
 * @property string $title
 * @property string $body
 * @property string $author
 * @property string $created_at
 * @property int    $likes_count
 */
class News extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%news}}';
    }

    public function rules(): array
    {
        return [
            [['title', 'body'], 'required'],
            [['likes_count'], 'integer', 'min' => 0],
            [['title'], 'string', 'max' => 255],
            [['author'], 'string', 'max' => 100],
        ];
    }
}
