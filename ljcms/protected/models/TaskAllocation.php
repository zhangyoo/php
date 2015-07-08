<?php

/**
 * This is the model class for table "{{task_allocation}}".
 *
 * The followings are the available columns in table '{{task_allocation}}':
 * @property string $id
 * @property string $obj_id
 * @property string $space_id
 * @property integer $order_type
 * @property integer $allocation_type
 * @property integer $task_type
 * @property string $sender
 * @property string $receiver
 * @property string $create_time
 * @property integer $status
 * @property integer $is_check
 */
class TaskAllocation extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_task_allocation';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('obj_id, order_type, allocation_type, sender, receiver, create_time', 'required'),
			array('order_type, allocation_type, task_type, status, is_check', 'numerical', 'integerOnly'=>true),
			array('obj_id, space_id, sender, receiver, create_time', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, obj_id, space_id, order_type, allocation_type, task_type, sender, receiver, create_time, status, is_check', 'safe', 'on'=>'search'),
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
			'obj_id' => 'Obj',
			'space_id' => 'Space',
			'order_type' => 'Order Type',
			'allocation_type' => 'Allocation Type',
			'task_type' => 'Task Type',
			'sender' => 'Sender',
			'receiver' => 'Receiver',
			'create_time' => 'Create Time',
			'status' => 'Status',
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
		$criteria->compare('obj_id',$this->obj_id,true);
		$criteria->compare('space_id',$this->space_id,true);
		$criteria->compare('order_type',$this->order_type);
		$criteria->compare('allocation_type',$this->allocation_type);
		$criteria->compare('task_type',$this->task_type);
		$criteria->compare('sender',$this->sender,true);
		$criteria->compare('receiver',$this->receiver,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('is_check',$this->is_check);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TaskAllocation the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
