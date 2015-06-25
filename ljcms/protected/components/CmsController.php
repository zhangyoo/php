<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class CmsController extends Controller
{
    /**
	 * 
	 * @see CController::init()
	 */
	public function init()
	{
        parent::init();
        if(Yii::app()->user->isGuest)
        {
            $callback = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
            $loginUrl=$this->createUrl('/default/login');
            $url=$loginUrl.'?callback='.  urlencode($callback);
            
            $this->redirect ($url);
        }
    }
}
