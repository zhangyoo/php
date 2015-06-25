<?php

/**
 * This is the model class for table "{{info_color_relation}}".
 *
 * The followings are the available columns in table '{{info_color_relation}}':
 * @property string $id
 * @property string $info_id
 * @property string $color_name
 * @property string $color_value
 * @property integer $color_sort
 */
class InfoColorRelation extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_info_color_relation';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('color_name', 'required'),
			array('color_sort', 'numerical', 'integerOnly'=>true),
			array('info_id', 'length', 'max'=>10),
			array('color_name, color_value', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, info_id, color_name, color_value, color_sort', 'safe', 'on'=>'search'),
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
			'info_id' => 'Info',
			'color_name' => 'Color Name',
			'color_value' => 'Color Value',
			'color_sort' => 'Color Sort',
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
		$criteria->compare('info_id',$this->info_id,true);
		$criteria->compare('color_name',$this->color_name,true);
		$criteria->compare('color_value',$this->color_value,true);
		$criteria->compare('color_sort',$this->color_sort);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return InfoColorRelation the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
