<?php
namespace admin\controllers;

/**
 * 后台首页 
 */
class ReplyController extends BackController
{
    /**
     * 主页
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}

