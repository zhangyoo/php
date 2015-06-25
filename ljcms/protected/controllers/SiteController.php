<?php

class SiteController extends Controller
{
	/**
	 * This is the action to handle external exceptions.
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
	 * 站内信收件箱
	 */
	public function actionLetterlist()
	{
	    $this->render('letterlist');
	}
    
    /**
	 * 已发送
	 */
	public function actionHasbeensend()
	{
	    $this->render('hasbeensend');
	}
    
    /**
	 * 回收站
	 */
	public function actionRecyclelist()
	{
	    $this->render('recyclelist');
	}
    
    /**
	 * 发送站内信
	 */
	public function actionSendletter()
	{
	    $this->render('sendletter');
	}
}
