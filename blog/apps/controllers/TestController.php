<?php
namespace apps\controllers;

use yii\web\Controller;

/**
 * Test controller
 */
class TestController extends Controller
{
    /**
     * 测试
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    
}
