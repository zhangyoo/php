<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	private $_id;
	
	public function getId()
	{
		return $this->_id;
	}
	/**
	 * Authenticates a user.
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
        if(Utils::isEmail($this->username))
            $user=User::model()->findByAttributes(array('email'=>$this->username,'is_del'=>0));
        else
            $user=User::model()->findByAttributes(array('username'=>$this->username,'is_del'=>0));
		if(empty($user))
		{
			$this->errorCode=self::ERROR_USERNAME_INVALID;
			return !$this->errorCode;
		}
		if($user->password!==$user->encrypt($this->password))
		{
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
			return !$this->errorCode;
		}
		//用户已经在线，不能多人同时使用一个账号登录
//		if($this->isLogin($user))
//		{
//			$this->errorCode=self::ERROR_UNKNOWN_IDENTITY;
//			return !$this->errorCode;
//		}
		$this->_id=$user->id;
        unset($user['password']);
        $this->setState('user', $user);
		$this->errorCode=self::ERROR_NONE;
		return !$this->errorCode;
	}
    
    /**
     * 加密后的密码登陆
     * @return type
     */
	public function authenticate_after()
	{
		false===strpos($this->username, "@")?
			$user=User::model()->findByAttributes(array('username'=>$this->username,'is_del'=>0))
			:$user=User::model()->findByAttributes(array('email'=>$this->username,'is_del'=>0));
		if(empty($user))
		{
			$this->errorCode=self::ERROR_USERNAME_INVALID;
			return !$this->errorCode;
		}
		if($user->password!==$this->password)
		{
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
			return !$this->errorCode;
		}
        //用户已经在线，不能多人同时使用一个账号登录
//		if($this->isLogin($user))
//		{
//			$this->errorCode=self::ERROR_UNKNOWN_IDENTITY;
//			return !$this->errorCode;
//		}
		$this->_id=$user->id;
        unset($user['password']);
        $this->setState('user', $user);
		$this->errorCode=self::ERROR_NONE;
		return !$this->errorCode;
	}
    
	/**
	 * 
	 * 判断用户是否已经在线
	 * @param CModel $user
	 */
	private function isLogin($user)
	{
		//限制一个账号不能同时在线
		$sql='select count(*) as num 
				from cms_user_login 
					where user_id='.$user->id.' and logout_time is null and ( to_days(curdate())-to_days(login_time)<1 ) ';
		$command=Yii::app()->db->createCommand($sql);
		$row=$command->queryRow();
		if(0!=$row['num'])
		{//该账号已经在线
			return true;
		}
		return false;
	}
}
?>