<?php

/**
 * This is the model class for table "{{element_layer}}".
 *
 * The followings are the available columns in table '{{element_layer}}':
 * @property string $element_id
 * @property string $space_id
 * @property string $angle
 * @property string $layer
 * @property string $name
 */
class ElementLayer extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{element_layer}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('element_id, space_id, angle, layer', 'required'),
			array('element_id, space_id', 'length', 'max'=>10),
			array('angle, layer', 'length', 'max'=>2),
			array('name', 'length', 'max'=>32),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('element_id, space_id, angle, layer, name', 'safe', 'on'=>'search'),
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
			'element_id' => 'Element',
			'space_id' => 'Space',
			'angle' => 'Angle',
			'layer' => 'Layer',
			'name' => 'Name',
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

		$criteria->compare('element_id',$this->element_id,true);
		$criteria->compare('space_id',$this->space_id,true);
		$criteria->compare('angle',$this->angle,true);
		$criteria->compare('layer',$this->layer,true);
		$criteria->compare('name',$this->name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ElementLayer the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
