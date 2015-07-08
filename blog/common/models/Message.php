<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "alg_message".
 *
 * @property string $id
 * @property string $title
 * @property string $email
 * @property string $content
 * @property string $create_time
 */
class Message extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'alg_message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'create_time'], 'required'],
            [['create_time'], 'integer'],
            [['title', 'email'], 'string', 'max' => 64],
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
            'title' => 'Title',
            'email' => 'Email',
            'content' => 'Content',
            'create_time' => 'Create Time',
        ];
    }
}
