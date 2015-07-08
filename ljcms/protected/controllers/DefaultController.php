<?php

class DefaultController extends Controller
{
    /**
     * 配置action
     * @return type
     */
	public function actions()
	{
		return array(
			'captcha'=>array(//配置验证码
				'class'=>'CCaptchaAction',
                'backColor'=>0xFFFF33,
                'transparent'=>true,
                'maxLength'=>'4',       // 最多生成几个字符
                'minLength'=>'4',       // 最少生成几个字符
                'width'=>80,
                'height'=>35,
			),
            // page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array('class'=>'CViewAction'),
		);
	}
	/**
     * 访问过滤
     * @return type
     */
	public function accessRules()
	{
		return array(
            array('allow',
                'users'=>array('*'),
            )
		);
	}
	/**
	 * 
	 * 管理首页
	 */
	public function actionIndex()
	{
		if(Yii::app()->user->isGuest)
			$this->redirect('/default/login');
        $user=UserLogin::model()->find(array(
                'select'=>'*',
                'condition'=>'user_id='.Yii::app()->user->getId(),
                'order'=>'login_time desc',
                'limit'=>'1',
                'offset'=>'1'
                ));
		$this->render('index',array('user'=>$user));
	}
	/**
	 * 
	 * admin错误页面
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}
	/**
	 *
	 * 管理员登录
	 */
	public function actionLogin()
	{
        try {
            $model=new LoginForm;
            $params=$_POST;
            //ajax验证
            $this->performAjaxValidation($model);
            
            if(isset($params['LoginForm']))
            {
                $model->attributes=$params['LoginForm'];
                if($model->validate() && $model->login())
                    $this->redirect($params['callback']);
            }
            $callback=$this->request->getParam('callback');
            if(empty($callback))
                $callback="/";
            $this->renderPartial('login',array('model'=>$model,'callback'=>$callback));
        } catch (Exception $e) {
            throw new CHttpException(500,$e->getMessage());//测试时使用
        }
	}
	/**
	 * 
	 * 管理员登出
	 */
	public function actionLogout()
	{
        //记录登出时间
		$userId=Yii::app()->user->getId();
        $sql='update cms_user_login set logout_time=unix_timestamp(now())
            where logout_time is null and user_id='.$userId;
        Yii::app()->db->createCommand($sql)->execute();
        //退出账户
        Yii::app()->user->logout();
		$this->redirect('/');
	}
}