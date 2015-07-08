<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'DefaultController'.
 */
class LoginForm extends CFormModel
{
	public $account;
	public $password;
	public $rememberMe;
    public $verifyCode;
	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			array('account, password', 'required'),//需要加验证码
			array('rememberMe', 'boolean'),
			array('password', 'authenticate'),
            array('verifyCode', 'captcha', 'allowEmpty'=>!extension_loaded('gd')),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'account'=>'帐  号',
			'password'=>'密  码',
			'rememberMe'=>'记住帐号',
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			$this->_identity=new UserIdentity($this->account,$this->password);
			if(!$this->_identity->authenticate())
				$this->addError('password','<img id="tip_arror_img" src="'.Yii::app()->theme->baseUrl.'/images/sorry.png"><span class="cRed">用户名或密码错误</span>');
		}
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if($this->_identity===null)
		{
			$this->_identity=new UserIdentity($this->account,$this->password);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
			Yii::app()->user->login($this->_identity,$duration);
            //种子里存用户id，给sns获取用户信息并自动登录
            $_SESSION['seed']=Yii::app()->user->getId();
			//记录用户的登录信息
			$this->recordLogin();
			return true;
		}
		else
			return false;
	}
	/**
	 * 
	 * 记录用户的登录信息
	 */
	private function recordLogin()
	{
		$sessionId=session_id();
		$userId=Yii::app()->user->getId();
		$command=Yii::app()->db->createCommand();
		$sessionIds=$command->select('session_id')
		->from('cms_user_login')
		->where('logout_time is null and user_id='.$userId)
		->queryColumn();
        $ip=Yii::app()->request->userHostAddress;
		if(!empty($sessionIds))
		{
			foreach ($sessionIds as $val)
			{//删除已经记录的session文件
				$filename=session_save_path().'/sess_'.$val;
				if(is_file($filename) && file_exists($filename))
				{
					unlink($filename);
				}
			}
			//先将以前未正常退出的登录信息设置为已退出
			$sql='update cms_user_login set logout_time=unix_timestamp(now()),ip=\''.$ip.'\'
					where logout_time is null and user_id='.$userId;
			$command->reset();
			$command->setText($sql);
			if(!$command->execute())
			{//@todo:记录更新操作失败
				
			}
		}
        
		$sql='insert into cms_user_login (session_id,user_id,login_time,ip)
				values (\''.$sessionId.'\','.$userId.',unix_timestamp(now()),\''.$ip.'\')';
		$command->reset();
		$command->setText($sql);
		if(!$command->execute())
		{//@todo:记录用户登录信息失败
			
		}
	}
}
?>