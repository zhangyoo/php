<?php

/**
 * This is the model class for table "{{label}}".
 *
 * The followings are the available columns in table '{{label}}':
 * @property string $id
 * @property string $name
 * @property string $parent_id
 * @property integer $type
 * @property string $category_id
 * @property string $sort_num
 * @property string $create_time
 * @property string $update_time
 * @property string $creater_id
 * @property string $updater_id
 * @property integer $is_del
 */
class Label extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_label';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, create_time, creater_id', 'required'),
			array('type, is_del', 'numerical', 'integerOnly'=>true),
			array('name, category_id', 'length', 'max'=>64),
			array('parent_id, create_time, update_time', 'length', 'max'=>10),
			array('sort_num, creater_id, updater_id', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, parent_id, type, category_id, sort_num, create_time, update_time, creater_id, updater_id, is_del', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'children'=>array(self::HAS_MANY, 'Label', 'parent_id'),
            'infos'=>array(self::HAS_MANY, 'Info', 'label_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'parent_id' => 'Parent',
			'type' => 'Type',
			'category_id' => 'Category',
			'sort_num' => 'Sort Num',
			'create_time' => 'Create Time',
			'update_time' => 'Update Time',
			'creater_id' => 'Creater',
			'updater_id' => 'Updater',
			'is_del' => 'Is Del',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('parent_id',$this->parent_id,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('category_id',$this->category_id,true);
		$criteria->compare('sort_num',$this->sort_num,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('creater_id',$this->creater_id,true);
		$criteria->compare('updater_id',$this->updater_id,true);
		$criteria->compare('is_del',$this->is_del);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Label the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
