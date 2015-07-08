<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "alg_flink".
 *
 * @property string $id
 * @property string $name
 * @property string $image
 * @property string $url
 * @property string $create_time
 * @property string $update_time
 * @property integer $sort_num
 * @property integer $is_del
 */
class Flink extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'alg_flink';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'create_time'], 'required'],
            [['create_time', 'update_time', 'sort_num', 'is_del'], 'integer'],
            [['name'], 'string', 'max' => 32],
            [['image', 'url'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'image' => 'Image',
            'url' => 'Url',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'sort_num' => 'Sort Num',
            'is_del' => 'Is Del',
        ];
    }
}
