<?php
	class Cookie
	{
		/**
		 * 
		 * 获取key为$name的cookie信息
		 * @param string $name
		 */
		public static function get($name)
		{
			$cookie=Yii::app()->request->cookies[$name];
			if(!$cookie)
				return null;
			return $cookie->value;
		}
		
		/**
		 * 
		 * 设置cookie
		 * @param string $name
		 * @param unknown_type $value
		 * @param int $expiration 
		 */
		public static function set($name, $value, $expiration=0)
		{
			$cookie=new CHttpCookie($name, $value);
			$cookie->expire=$expiration;
			Yii::app()->request->cookies[$name]=$cookie;
		}
	}
?>