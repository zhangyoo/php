<?php
namespace admin\controllers;

use yii\web\Controller;

/*
 * Test Controller
 */
class TestController extends \yii\base\Controller
{
    /**
     * 测试
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
?>
