<?php

/**
 * This is the model class for table "{{brand}}".
 *
 * The followings are the available columns in table '{{brand}}':
 * @property string $id
 * @property string $parent_id
 * @property string $brandhall_id
 * @property string $logo
 * @property string $name
 * @property string $letter
 * @property string $summary
 * @property integer $sort_num
 * @property string $create_time
 * @property string $update_time
 * @property string $creater_id
 * @property string $updater_id
 * @property integer $is_del
 * @property integer $is_show
 * @property integer $is_check
 */
class Brand extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_brand';
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
			array('sort_num, is_del, is_show, is_check', 'numerical', 'integerOnly'=>true),
			array('parent_id, brandhall_id, create_time, update_time, creater_id, updater_id', 'length', 'max'=>10),
			array('logo, summary', 'length', 'max'=>255),
			array('name, letter', 'length', 'max'=>64),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, parent_id, brandhall_id, logo, name, letter, summary, sort_num, create_time, update_time, creater_id, updater_id, is_del, is_show, is_check', 'safe', 'on'=>'search'),
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
			'parent_id' => 'Parent',
			'brandhall_id' => 'Brandhall',
			'logo' => 'Logo',
			'name' => 'Name',
			'letter' => 'Letter',
			'summary' => 'Summary',
			'sort_num' => 'Sort Num',
			'create_time' => 'Create Time',
			'update_time' => 'Update Time',
			'creater_id' => 'Creater',
			'updater_id' => 'Updater',
			'is_del' => 'Is Del',
			'is_show' => 'Is Show',
			'is_check' => 'Is Check',
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
		$criteria->compare('parent_id',$this->parent_id,true);
		$criteria->compare('brandhall_id',$this->brandhall_id,true);
		$criteria->compare('logo',$this->logo,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('letter',$this->letter,true);
		$criteria->compare('summary',$this->summary,true);
		$criteria->compare('sort_num',$this->sort_num);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('creater_id',$this->creater_id,true);
		$criteria->compare('updater_id',$this->updater_id,true);
		$criteria->compare('is_del',$this->is_del);
		$criteria->compare('is_show',$this->is_show);
		$criteria->compare('is_check',$this->is_check);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Brand the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
