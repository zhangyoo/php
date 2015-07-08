<?php
namespace admin\controllers;

/**
 * 后台首页 
 */
class CommentController extends BackController
{
    /**
     * 主页
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}

