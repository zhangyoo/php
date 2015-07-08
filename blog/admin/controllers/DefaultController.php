<?php
namespace admin\controllers;

use Yii;
use yii\web\Controller;
use admin\models\LoginForm;

/**
 * 默认控制器
 */
class DefaultController extends Controller
{
    /**
     * 登录
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            $index = Yii::$app->urlManager->createUrl(['index/index']);
            $this->redirect($index);
        }
        $returnUrl = '';
        if(isset($_GET['returnUrl']))
            $returnUrl = $_GET['returnUrl'];
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $this->redirect(urldecode($_REQUEST['returnUrl']));
        } else {
            return $this->renderPartial('login', [
                'model' => $model,'returnUrl'=>$returnUrl
            ]);
        }
    }
    
    /**
     * 退出
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        $index = Yii::$app->urlManager->createUrl(['default/login']);
        $this->redirect($index);
    }
}