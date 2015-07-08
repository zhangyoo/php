<?php

namespace apps\controllers;

use Yii;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class IndexController extends BackController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
    
    /**
     * 列表页
     */
    public function actionIndex()
    {
        
        return $this->render('index');
    }
}
