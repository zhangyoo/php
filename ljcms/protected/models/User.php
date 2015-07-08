<?php

/**
 * This is the model class for table "cms_user".
 *
 * The followings are the available columns in table 'cms_user':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $nickname
 * @property string $email
 * @property string $image
 * @property string $create_time
 * @property string $update_time
 * @property integer $is_del
 * @property integer $status
 */
class User extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cms_user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, password, nickname, email, create_time', 'required'),
			array('is_del, status', 'numerical', 'integerOnly'=>true),
			array('username, password, nickname, email', 'length', 'max'=>64),
			array('image', 'length', 'max'=>256),
			array('create_time, update_time', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, username, password, nickname, email, image, create_time, update_time, is_del, status', 'safe', 'on'=>'search'),
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
			'username' => 'Username',
			'password' => 'Password',
			'nickname' => 'Nickname',
			'email' => 'Email',
			'image' => 'Image',
			'create_time' => 'Create Time',
			'update_time' => 'Update Time',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('nickname',$this->nickname,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('is_del',$this->is_del);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    /**
     * 判断用户是否存在
     * @param int $type 
     */
    public function isExisted($type=1)
    {
        $flag=false;
        switch ($type)
        {
            case 1://用户名或邮箱 和 密码
                $flag=User::model()->exists("(username='".$this->username."' or email='".$this->email."') and password='".$this->encrypt($this->password)."'");
                break;
            case 2://用户名 和 密码
                $flag=User::model()->exists("username='".$this->username."' and password='".$this->encrypt($this->password)."'");
                break;
            case 3://邮箱 和 密码
                $flag=User::model()->exists("email='".$this->email."' and password='".$this->encrypt($this->password)."'");
                break;
        }
        return $flag;
    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
    /**
	 * 加密方法
	 * @param string $value
	 * @return string
	 */
	public function encrypt($value)
	{
		return md5($value.'{key:leju}');
	}
    
    protected function beforeValidate()
	{
		if($this->isNewRecord)
		{
			$this->create_time=time();
		}
		else
			$this->update_time=time();
		return CActiveRecord::beforeValidate();
	}
	protected function afterValidate()
	{
		parent::afterValidate();
		if($this->isNewRecord)
			$this->password=$this->encrypt($this->password);
	}
}
