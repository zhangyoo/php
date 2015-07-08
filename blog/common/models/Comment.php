<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "alg_comment".
 *
 * @property string $id
 * @property integer $article_id
 * @property string $content
 * @property string $create_time
 */
class Comment extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'alg_comment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['article_id', 'content', 'create_time'], 'required'],
            [['article_id', 'create_time'], 'integer'],
            [['content'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'article_id' => 'Article ID',
            'content' => 'Content',
            'create_time' => 'Create Time',
        ];
    }
}
