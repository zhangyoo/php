<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "alg_system".
 *
 * @property string $title
 * @property string $image
 * @property string $keywords
 * @property string $description
 * @property string $record
 * @property string $powerby
 */
class System extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'alg_system';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title', 'record'], 'string', 'max' => 32],
            [['keywords'], 'string', 'max' => 64],
            [['image','description', 'powerby'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'title' => 'Title',
            'image' => 'Image',
            'keywords' => 'Keywords',
            'description' => 'Description',
            'record' => 'Record',
            'powerby' => 'Powerby',
        ];
    }
}
