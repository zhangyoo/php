<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "alg_category".
 *
 * @property string $id
 * @property string $name
 * @property string $parent_id
 * @property string $keywords
 * @property string $description
 * @property integer $sort_num
 * @property string $create_time
 * @property string $update_time
 * @property integer $is_del
 */
class Category extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'alg_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'create_time'], 'required'],
            [['parent_id', 'sort_num', 'create_time', 'update_time', 'is_del'], 'integer'],
            [['name', 'keywords'], 'string', 'max' => 64],
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
            'parent_id' => 'Parent ID',
            'keywords' => 'Keywords',
            'description' => 'Description',
            'sort_num' => 'Sort Num',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'is_del' => 'Is Del',
        ];
    }
}
