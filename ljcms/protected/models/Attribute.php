<?php

/**
 * This is the model class for table "sp_attribute".
 *
 * The followings are the available columns in table 'sp_attribute':
 * @property string $attr_id
 * @property string $attr_key
 * @property string $type_id
 * @property string $attr_name
 * @property integer $attr_input_type
 * @property integer $attr_type
 * @property string $attr_values
 * @property integer $sort
 * @property integer $attr_index
 * @property integer $editer_to_user
 */
class Attribute extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sp_attribute';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('attr_name, attr_values', 'required'),
			array('attr_input_type, attr_type, sort, attr_index, editer_to_user', 'numerical', 'integerOnly'=>true),
			array('attr_key', 'length', 'max'=>32),
			array('type_id', 'length', 'max'=>11),
			array('attr_name', 'length', 'max'=>60),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('attr_id, attr_key, type_id, attr_name, attr_input_type, attr_type, attr_values, sort, attr_index, editer_to_user', 'safe', 'on'=>'search'),
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
			'attr_id' => 'Attr',
			'attr_key' => 'Attr Key',
			'type_id' => 'Type',
			'attr_name' => 'Attr Name',
			'attr_input_type' => 'Attr Input Type',
			'attr_type' => 'Attr Type',
			'attr_values' => 'Attr Values',
			'sort' => 'Sort',
			'attr_index' => 'Attr Index',
			'editer_to_user' => 'Editer To User',
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

		$criteria->compare('attr_id',$this->attr_id,true);
		$criteria->compare('attr_key',$this->attr_key,true);
		$criteria->compare('type_id',$this->type_id,true);
		$criteria->compare('attr_name',$this->attr_name,true);
		$criteria->compare('attr_input_type',$this->attr_input_type);
		$criteria->compare('attr_type',$this->attr_type);
		$criteria->compare('attr_values',$this->attr_values,true);
		$criteria->compare('sort',$this->sort);
		$criteria->compare('attr_index',$this->attr_index);
		$criteria->compare('editer_to_user',$this->editer_to_user);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Attribute the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
