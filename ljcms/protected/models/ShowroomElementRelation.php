<?php

/**
 * This is the model class for table "{{showroom_element_relation}}".
 *
 * The followings are the available columns in table '{{showroom_element_relation}}':
 * @property string $showroom_id
 * @property string $element_id
 * @property integer $sort_num
 * @property integer $recommend_sort_num
 * @property integer $is_show
 * @property integer $status
 */
class ShowroomElementRelation extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{showroom_element_relation}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('showroom_id, element_id', 'required'),
			array('sort_num, recommend_sort_num, is_show, status', 'numerical', 'integerOnly'=>true),
			array('showroom_id, element_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('showroom_id, element_id, sort_num, recommend_sort_num, is_show, status', 'safe', 'on'=>'search'),
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
			'showroom_id' => 'Showroom',
			'element_id' => 'Element',
			'sort_num' => 'Sort Num',
			'recommend_sort_num' => 'Recommend Sort Num',
			'is_show' => 'Is Show',
			'status' => 'Status',
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

		$criteria->compare('showroom_id',$this->showroom_id,true);
		$criteria->compare('element_id',$this->element_id,true);
		$criteria->compare('sort_num',$this->sort_num);
		$criteria->compare('recommend_sort_num',$this->recommend_sort_num);
		$criteria->compare('is_show',$this->is_show);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ShowroomElementRelation the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
