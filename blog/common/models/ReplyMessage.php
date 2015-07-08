<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "alg_reply_message".
 *
 * @property string $id
 * @property string $message_id
 * @property string $content
 * @property string $create_time
 */
class ReplyMessage extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'alg_reply_message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['message_id', 'create_time'], 'required'],
            [['message_id', 'create_time'], 'integer'],
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
            'message_id' => 'Message ID',
            'content' => 'Content',
            'create_time' => 'Create Time',
        ];
    }
}
