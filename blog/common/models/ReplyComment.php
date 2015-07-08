<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "alg_reply_comment".
 *
 * @property string $id
 * @property integer $comment_id
 * @property string $content
 * @property string $create_time
 */
class ReplyComment extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'alg_reply_comment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['comment_id', 'content', 'create_time'], 'required'],
            [['comment_id', 'create_time'], 'integer'],
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
            'comment_id' => 'Comment ID',
            'content' => 'Content',
            'create_time' => 'Create Time',
        ];
    }
}
