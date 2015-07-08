<?php
namespace admin\controllers;

/**
 * 用户中心
 */

class UserController extends BackController
{
    /**
     * 主页
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
