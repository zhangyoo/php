<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "alg_tag".
 *
 * @property string $id
 * @property string $name
 * @property string $description
 * @property string $create_time
 * @property string $update_time
 * @property integer $is_del
 */
class Tag extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'alg_tag';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'create_time'], 'required'],
            [['create_time', 'update_time', 'is_del'], 'integer'],
            [['name'], 'string', 'max' => 32],
            [['description'], 'string', 'max' => 255]
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
            'description' => 'Description',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'is_del' => 'Is Del',
        ];
    }
}
