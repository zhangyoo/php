<?php
namespace admin\controllers;

use Yii;
use common\models\Category;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * 后台首页 
 */
class CategoryController extends BackController
{
//    public $enableCsrfValidation = false;
    
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
        $model = new Category();
        $condition = array('is_del' => '0');
        $data = $model::find()->andWhere($condition);
        $pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' => Yii::$app->params['pageSize']]);
        $list = $data->offset($pages->offset)->limit($pages->limit)->all();
        
        return $this->render('index',array('list'=>$list,'pages' => $pages));
    }
    
    /**
     * 创建数据
     */
    public function actionCreate()
    {
        $output = array();
        $model = new Category();
        $output['model'] = $model;
        if($this->chksubmit()){
            $model->load($_POST);
            $model->isNewRecord = true;
            if($model->save())
                return $this->redirect(['index']);
        }
        return $this->render('create',$output);
    }
    
    /**
     * 修改数据
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    
    /**
     * 获取分类数据
     */
    function actionGetcategory()
    {
        $tag = '<option value="0">请选择</option>';
        $model = new Category();
        $condition = array();
        $res = $model::find()->all();
        if(!empty($res)){
            foreach ($res as $key=>$val){
                $tag .= '<option value="'.$val['id'].'">'.$val['name'].'</option>';
            }
        }
        echo $tag;
    }
    
    /**
     * Finds the Article model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Article the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

