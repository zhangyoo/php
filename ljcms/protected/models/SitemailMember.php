<?php

/**
 * This is the model class for table "sns_sitemail_member".
 *
 * The followings are the available columns in table 'sns_sitemail_member':
 * @property string $id
 * @property string $uid
 * @property string $mail_id
 * @property integer $flag
 * @property string $read_time
 * @property integer $is_del
 */
class SitemailMember extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cms_sitemail_member';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uid, mail_id', 'required'),
			array('flag, is_del', 'numerical', 'integerOnly'=>true),
			array('uid, mail_id', 'length', 'max'=>11),
			array('read_time', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, uid, mail_id, flag, read_time, is_del', 'safe', 'on'=>'search'),
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
			'uid' => 'Uid',
			'mail_id' => 'Mail',
			'flag' => 'Flag',
			'read_time' => 'Read Time',
			'is_del' => 'Is Del',
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
		$criteria->compare('uid',$this->uid,true);
		$criteria->compare('mail_id',$this->mail_id,true);
		$criteria->compare('flag',$this->flag);
		$criteria->compare('read_time',$this->read_time,true);
		$criteria->compare('is_del',$this->is_del);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SitemailMember the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
