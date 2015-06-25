<?php

/**
 * This is the model class for table "{{order}}".
 *
 * The followings are the available columns in table '{{order}}':
 * @property string $id
 * @property string $title
 * @property string $number
 * @property string $content
 * @property integer $room_category
 * @property string $create_time
 * @property string $update_time
 * @property string $end_time
 * @property string $creater_id
 * @property string $updater_id
 * @property integer $type
 * @property integer $is_del
 * @property integer $status
 */
class Order extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_order';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, create_time, creater_id', 'required'),
			array('room_category, type, is_del, status', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>100),
			array('number', 'length', 'max'=>32),
			array('create_time, update_time, end_time, creater_id, updater_id', 'length', 'max'=>10),
			array('content', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, number, content, room_category, create_time, update_time, end_time, creater_id, updater_id, type, is_del, status', 'safe', 'on'=>'search'),
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
            'brandhalls'=>array(self::MANY_MANY, 'Brandhall', 'tbl_order_brandhall_relation(order_id, brandhall_id)','on'=>'brandhalls.is_del=0'),
            'albums'=>array(self::HAS_MANY, 'Album', 'obj_id','on'=>'albums.type=1'),
            'spaces'=>array(self::MANY_MANY, 'Space', 'tbl_order_space_relation(order_id, space_id)','on'=>'spaces.is_del=0'),
            'infos'=>array(self::MANY_MANY, 'Info', 'tbl_order_info_relation(order_id, info_id)','on'=>'infos.is_del=0'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Title',
			'number' => 'Number',
			'content' => 'Content',
			'room_category' => 'Room Category',
			'create_time' => 'Create Time',
			'update_time' => 'Update Time',
			'end_time' => 'End Time',
			'creater_id' => 'Creater',
			'updater_id' => 'Updater',
			'type' => 'Type',
			'is_del' => 'Is Del',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('number',$this->number,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('room_category',$this->room_category);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('end_time',$this->end_time,true);
		$criteria->compare('creater_id',$this->creater_id,true);
		$criteria->compare('updater_id',$this->updater_id,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('is_del',$this->is_del);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Order the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
