<?php

/**
 * This is the model class for table "{{space}}".
 *
 * The followings are the available columns in table '{{space}}':
 * @property string $id
 * @property string $name
 * @property string $out_name
 * @property string $image
 * @property string $pics
 * @property string $showpics
 * @property string $floorplan
 * @property integer $room_category
 * @property string $summary
 * @property string $length
 * @property string $width
 * @property string $height
 * @property string $max_length
 * @property string $max_width
 * @property string $max_height
 * @property string $create_time
 * @property string $update_time
 * @property string $creater_id
 * @property string $updater_id
 * @property string $hot_num
 * @property string $showroom_num
 * @property string $plan_num
 * @property integer $is_show
 * @property integer $is_del
 * @property integer $status
 * @property integer $is_common
 * @property integer $is_360
 */
class Space extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_space';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, out_name, image, pics, showpics, room_category, create_time, creater_id', 'required'),
			array('room_category, is_show, is_del, status, is_common, is_360', 'numerical', 'integerOnly'=>true),
			array('name, out_name', 'length', 'max'=>64),
			array('image, summary', 'length', 'max'=>255),
			array('length, width, height, max_length, max_width, max_height', 'length', 'max'=>8),
			array('create_time, update_time, creater_id, updater_id, hot_num, showroom_num, plan_num', 'length', 'max'=>10),
			array('floorplan', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, out_name, image, pics, showpics, floorplan, room_category, summary, length, width, height, max_length, max_width, max_height, create_time, update_time, creater_id, updater_id, hot_num, showroom_num, plan_num, is_show, is_del, status, is_common, is_360', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'out_name' => 'Out Name',
			'image' => 'Image',
			'pics' => 'Pics',
			'showpics' => 'Showpics',
			'floorplan' => 'Floorplan',
			'room_category' => 'Room Category',
			'summary' => 'Summary',
			'length' => 'Length',
			'width' => 'Width',
			'height' => 'Height',
			'max_length' => 'Max Length',
			'max_width' => 'Max Width',
			'max_height' => 'Max Height',
			'create_time' => 'Create Time',
			'update_time' => 'Update Time',
			'creater_id' => 'Creater',
			'updater_id' => 'Updater',
			'hot_num' => 'Hot Num',
			'showroom_num' => 'Showroom Num',
			'plan_num' => 'Plan Num',
			'is_show' => 'Is Show',
			'is_del' => 'Is Del',
			'status' => 'Status',
			'is_common' => 'Is Common',
			'is_360' => 'Is 360',
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
		$criteria->compare('out_name',$this->out_name,true);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('pics',$this->pics,true);
		$criteria->compare('showpics',$this->showpics,true);
		$criteria->compare('floorplan',$this->floorplan,true);
		$criteria->compare('room_category',$this->room_category);
		$criteria->compare('summary',$this->summary,true);
		$criteria->compare('length',$this->length,true);
		$criteria->compare('width',$this->width,true);
		$criteria->compare('height',$this->height,true);
		$criteria->compare('max_length',$this->max_length,true);
		$criteria->compare('max_width',$this->max_width,true);
		$criteria->compare('max_height',$this->max_height,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('creater_id',$this->creater_id,true);
		$criteria->compare('updater_id',$this->updater_id,true);
		$criteria->compare('hot_num',$this->hot_num,true);
		$criteria->compare('showroom_num',$this->showroom_num,true);
		$criteria->compare('plan_num',$this->plan_num,true);
		$criteria->compare('is_show',$this->is_show);
		$criteria->compare('is_del',$this->is_del);
		$criteria->compare('status',$this->status);
		$criteria->compare('is_common',$this->is_common);
		$criteria->compare('is_360',$this->is_360);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Space the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
