<?php
namespace admin\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
/**
 * 模块主控制器
 */

class BackController extends Controller
{
    /**
     * 
     * @see CController::init()
     */
   public function init()
   {
        parent::init();
        if(Yii::$app->user->isGuest)
        {
            //未登录情况下，登录之后返回这个地址
            $return_url = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
            
            $loginUrl = Yii::$app->urlManager->createUrl(['default/login']);
            $url=Yii::$app->params['admin'].$loginUrl. '?returnUrl='. urlencode($return_url);
            
            $this->redirect ($url);
        }
        
    }
    
    /**
     * 检测FORM是否提交
     * @return boolean
     */
    public function chksubmit()
    {
        return isset($_POST['form_submit']) ? $_POST['form_submit'] : 0;
    }
    
}
