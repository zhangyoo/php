<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%article}}".
 *
 * @property string $id
 * @property string $title
 * @property string $category_id
 * @property string $image
 * @property string $keywords
 * @property string $description
 * @property string $content
 * @property string $create_time
 * @property string $update_time
 * @property string $hits
 * @property string $comment_num
 * @property integer $is_del
 */
class Article extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'alg_article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'create_time'], 'required'],
            [['category_id', 'create_time', 'update_time', 'hits', 'comment_num', 'is_del'], 'integer'],
            [['content'], 'string'],
            [['title', 'keywords'], 'string', 'max' => 64],
            [['image', 'description'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'category_id' => 'Category ID',
            'image' => 'Image',
            'keywords' => 'Keywords',
            'description' => 'Description',
            'content' => 'Content',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'hits' => 'Hits',
            'comment_num' => 'Comment Num',
            'is_del' => 'Is Del',
        ];
    }
}
