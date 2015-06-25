<?php

/**
 * This is the model class for table "sns_sitemail".
 *
 * The followings are the available columns in table 'sns_sitemail':
 * @property string $id
 * @property string $uid
 * @property string $dialog_id
 * @property string $cmt_id
 * @property string $cmt_name
 * @property string $title
 * @property string $content
 * @property string $addtime
 * @property integer $counts
 * @property integer $is_del
 * @property integer $type
 * @property string $main_id
 */
class Sitemail extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cms_sitemail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uid, cmt_id, cmt_name, title, content', 'required'),
			array('counts, is_del, type', 'numerical', 'integerOnly'=>true),
			array('uid, cmt_id', 'length', 'max'=>11),
			array('dialog_id, addtime, main_id', 'length', 'max'=>10),
			array('cmt_name, title', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, uid, dialog_id, cmt_id, cmt_name, title, content, addtime, counts, is_del, type, main_id', 'safe', 'on'=>'search'),
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
    
    /*
     * 获取与当前用户相关的站内信
     * author zhangyong
     */
    public function _getMessage($uid,$connect,$option=array())
    {
        $like='';
        if(count($option)>0 && isset($option['like']) && !empty($option['like'])){
            $like .= " and ss.content like '%".trim($option['like'])."%'";
        }
        $med=array();//记录好友的最新消息
        //查询当前登录用户作为收件人或者发件人的第一条信息
        $sql="select ss.id,ss.uid as sid,ss.title,ss.content,ss.addtime,ssm.uid as rid,ssm.mail_id,ssm.flag from cms_sitemail as ss 
            left join cms_sitemail_member as ssm on ss.id=ssm.mail_id 
            where (ss.uid=".$uid." or ssm.uid=".$uid.") and dialog_id=0 and ss.is_del=0 and ssm.is_del=0 order by ss.addtime desc";
        $messages=$connect->createCommand($sql)->queryAll();
        //获取每个好友的最新消息
        if(!empty($messages)){
            foreach ($messages as $message){
                if($message['sid']==$uid){
                    //作为发件人
                    $sql="select ss.id,ss.uid as suid,u.id as uid,u.username,u.image,ss.dialog_id,ss.title,ss.content,ss.addtime,ss.main_id,ssm.flag,ssm.read_time from cms_sitemail as ss "
                            . "left join cms_sitemail_member as ssm on ss.id=ssm.mail_id "
                            . "left join cms_user as u on u.id=".$message['rid']." "
                            . "where ((ss.uid=".$uid." and ssm.uid=".$message['rid'].") or (ss.uid=".$message['rid']." and ssm.uid=".$uid.")) and (ss.main_id=".$message['id']." or ss.main_id is null)  $like and ss.is_del=0 and ssm.is_del=0 order by ss.addtime desc limit 1";
                }else{
                    //作为收件人
                    $sql="select ss.id,ss.uid as suid,u.id as uid,u.username,u.image,ss.dialog_id,ss.title,ss.content,ss.addtime,ss.main_id,ssm.flag,ssm.read_time from cms_sitemail as ss "
                            . "left join cms_sitemail_member as ssm on ss.id=ssm.mail_id "
                            . "left join cms_user as u on u.id=".$message['sid']." "
                            . "where ((ss.uid=".$message['sid']." and ssm.uid=".$uid.") or (ss.uid=".$uid." and ssm.uid=".$message['sid'].")) and (ss.main_id=".$message['id']." or ss.main_id is null)  $like and ss.is_del=0 and ssm.is_del=0 order by ss.addtime desc limit 1";
                }
                $medTemp=$connect->createCommand($sql)->queryAll();
                $med=array_merge($med,$medTemp);
            }
        }
        return $med;
    }
    
    /*
     * 获取常用收件人
     * author zhangyong
     */
    public function _getRecievers($uid,$connect)
    {
        $recievers=array();
        $sql="select ss.uid as sid,ssm.uid as rid from cms_sitemail as ss 
            left join cms_sitemail_member as ssm on ss.id=ssm.mail_id 
            where (ss.uid=".$uid." or ssm.uid=".$uid.") and dialog_id=0 order by ss.addtime desc";
        $allUser=$connect->createCommand($sql)->queryAll();
        if(!empty($allUser)){
            foreach ($allUser as $u){
                if($u['sid']==$uid){
                    $uArray[]=$u['rid'];
                }else{
                    $uArray[]=$u['sid'];
                }
            }
            if(!empty($uArray)){
                $recievers=  User::model()->findAll(array('select'=>'id,username,email','condition'=>'id in ('.  implode(',', $uArray).')'));
            }
        }
        return $recievers;
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'uid' => 'Uid',
			'dialog_id' => 'Dialog',
			'cmt_id' => 'Cmt',
			'cmt_name' => 'Cmt Name',
			'title' => 'Title',
			'content' => 'Content',
			'addtime' => 'Addtime',
			'counts' => 'Counts',
			'is_del' => 'Is Del',
			'type' => 'Type',
			'main_id' => 'Main',
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
		$criteria->compare('dialog_id',$this->dialog_id,true);
		$criteria->compare('cmt_id',$this->cmt_id,true);
		$criteria->compare('cmt_name',$this->cmt_name,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('addtime',$this->addtime,true);
		$criteria->compare('counts',$this->counts);
		$criteria->compare('is_del',$this->is_del);
		$criteria->compare('type',$this->type);
		$criteria->compare('main_id',$this->main_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Sitemail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
