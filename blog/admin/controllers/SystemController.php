<?php
namespace admin\controllers;

use Yii;
use common\models\System;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
/**
 * 后台首页 
 */
class SystemController extends BackController
{
    /**
     * 主页
     */
    public function actionIndex()
    {
        $model = new System();
        if($this->chksubmit()){
            Yii::$app->db->createCommand()->delete('alg_system', '')->execute();
            $model->load($_POST);
            if($model->save())
                return $this->redirect(['index']);
        }
        $data = $model::find()->one();
        if(!empty($data))
            $model = $data;
        return $this->render('index',array('model'=>$model));
    }
}

