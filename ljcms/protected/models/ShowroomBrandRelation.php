<?php

/**
 * This is the model class for table "{{showroom_brand_relation}}".
 *
 * The followings are the available columns in table '{{showroom_brand_relation}}':
 * @property string $showroom_id
 * @property string $brand_id
 * @property string $brandhall_id
 * @property integer $is_show
 * @property integer $is_mine
 */
class ShowroomBrandRelation extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_showroom_brand_relation';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('showroom_id, brand_id', 'required'),
			array('is_show, is_mine', 'numerical', 'integerOnly'=>true),
			array('showroom_id, brand_id, brandhall_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('showroom_id, brand_id, brandhall_id, is_show, is_mine', 'safe', 'on'=>'search'),
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
			'brand_id' => 'Brand',
			'brandhall_id' => 'Brandhall',
			'is_show' => 'Is Show',
			'is_mine' => 'Is Mine',
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
		$criteria->compare('brand_id',$this->brand_id,true);
		$criteria->compare('brandhall_id',$this->brandhall_id,true);
		$criteria->compare('is_show',$this->is_show);
		$criteria->compare('is_mine',$this->is_mine);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ShowroomBrandRelation the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
