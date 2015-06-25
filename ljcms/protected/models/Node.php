<?php

/**
 * This is the model class for table "{{node}}".
 *
 * The followings are the available columns in table '{{node}}':
 * @property string $id
 * @property string $space_id
 * @property string $area_id
 * @property string $layer
 * @property string $distance
 * @property string $name
 * @property integer $type
 * @property string $create_time
 * @property string $update_time
 * @property string $creater_id
 * @property string $updater_id
 */
class Node extends ActiveRecord
{
    /**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Node the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_node';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('space_id, layer, name, type, create_time, creater_id', 'required'),
			array('type', 'numerical', 'integerOnly'=>true),
			array('space_id, area_id, create_time, update_time, creater_id, updater_id', 'length', 'max'=>10),
			array('distance', 'length', 'max'=>8),
			array('name', 'length', 'max'=>64),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, space_id, area_id, layer, distance, name, type, create_time, update_time, creater_id, updater_id', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'space_id' => 'Space',
			'area_id' => 'Area',
			'layer' => 'Layer',
			'distance' => 'Distance',
			'name' => 'Name',
			'type' => 'Type',
			'create_time' => 'Create Time',
			'update_time' => 'Update Time',
			'creater_id' => 'Creater',
			'updater_id' => 'Updater',
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
		$criteria->compare('space_id',$this->space_id,true);
		$criteria->compare('area_id',$this->area_id,true);
		$criteria->compare('layer',$this->layer,true);
		$criteria->compare('distance',$this->distance,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('creater_id',$this->creater_id,true);
		$criteria->compare('updater_id',$this->updater_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	
}
