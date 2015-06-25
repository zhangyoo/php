<?php
	/**
	 * 
	 * @author zhangyong
	 * @todo:
     * @PS：用户管理 2014/7/28
	 */
	class UserController extends CmsController
	{
        /**
		 * 用户列表
		 * @author zhangyong
		 */
		public function actionIndex()
		{
            $like = '';//查询用户条件
            $name=null;//存放搜索的关键词
            $params=$_POST;
            if(isset($params['name']) && !empty($params['name'])){
                $like = " and (username like '%".trim($params['name'])."%' or nickname like '%".trim($params['name'])."%')";
                $name=$params['name'];
            }
            $sql = "select * from cms_user where id!=1 and is_del =0 $like order by create_time desc";
            $data=$this->getIndex($sql);
            $ul=$data['list'];
            $pages=$data['pages'];
            $this->render('index',array('ul'=>$ul,'pages' => $pages,'name'=>$name));
        }
        
        /**
		 * 用户列表
		 * @author zhangyong
		 */
		public function actionCreate()
		{
            $model=new User();
            $this->performAjaxValidation($model);
            try{
                if(isset($_POST['User'])){
                    $userData=$_POST['User'];
                    $model->attributes=$userData;
                    $fileHelper=new FileHelper;
                    $fileHelper->subFolder='user';
                    $model->image=$fileHelper->saveFile($model,'image',array('upyun'=>Yii::app()->params['upYun']));
                    $model->isNewRecord = TRUE;
                }
                if($model->save())
                    $this->redirect('/user/index');
            }  catch (Exception $e) {
                throw new CHttpException(500,$e->getMessage());//测试时使用
            }
            $this->render('create',array('model'=>$model));
        }
        
        /**
		 * 编辑用户
		 * @author zhangyong
		 */
        public function actionUpdate($id)
        {
            $model = User::model()->findByAttributes(array('id'=>$id));
            $oldImage=$model['image'];
            try{
                if(isset($_POST['User'])){
                    $userData=$_POST['User'];
                    $oldpassword=$model['password'];
                    $model->attributes=$userData;
                    if($userData['password']!=$oldpassword){
                        $password=md5($userData['password'].'{key:leju}');
                        $model->password=$password;
                    }
                    $fileHelper=new FileHelper;
                    $fileHelper->subFolder='user';
                    if($fileHelper->hasUploadFile($model,'image')){
                        $model->image=$fileHelper->saveFile($model,'image',array('upyun'=>Yii::app()->params['upYun']));
                    }else{
                        if(!empty($oldImage))
                            $model->image=$oldImage;
                    }
                    if($model->save())
                        $this->redirect('/user/index');
                }
            }  catch (Exception $e) {
                throw new CHttpException(500,$e->getMessage());//测试时使用
            }
            $this->render('update',array('model'=>$model));
        }
        
        /**
		 * 修改密码
		 * @author zhangyong
		 */
		public function actionUpdatePassword($id)
		{
            if(!isset($id) && empty($id))
                throw new CHttpException(404,'该用户不存在或者已经删除！.');
            $model=  User::model()->findByPk($id);
            $oldPassword=$model['password'];
            if(isset($_POST['User'])){
                $model->username=$_POST['User']['username'];
                if($_POST['User']['password']!=$oldPassword){
                    $model->password=md5($_POST['User']['password'].'{key:leju}');
                }
                if($model->save())
                    $this->redirect('/default/index');
            }
            $this->render('updatePassword',array('model'=>$model));
        }
        
        /**
		 * 删除用户操作
		 * @author zhangyong
		 */
        public function actionDelete(){
            $result = array();
            $result['status'] = false;
            $result['info'] = '删除用户失败！';
            if(isset($_POST['id']) && !empty($_POST['id'])){
                $id = intval($_POST['id']);
                $model = User::model()->findByPk($id);
                $model->is_del = 1;
                if($model->save()){
                     $result['status'] = TRUE;
                     $result['info'] = '删除用户成功！';
                     $result['id'] = $model->id;
                 }
            }
            echo CJSON::encode($result);
        }
        
        /**
		 * 检查用户名，昵称，邮箱是否有重复
		 * @author zhangyong
		 */
        public function actionCheck(){
            $params=$_POST;
            $data['status']=false;
            $con='';
            if(isset($params['val']) && !empty($params['val']) && isset($params['type']) && !empty($params['type'])){
                if(intval($params['type'])>1){
                    $con="email like '%".trim($params['val'])."%'";
                    $info='该邮箱号已存在！';
                }else{
                    $con="username like '%".trim($params['val'])."%'";
                    $info='该用户名已存在！';
                }
                if(isset($params['edit']) && isset($params['uid']) && intval($params['uid'])>0){
                    $exist=  User::model()->find('id!='.$params['uid'].' and '.$con);//编辑时查询是否有重复数据
                }else{
                    $exist=  User::model()->find($con);
                }
                if(!empty($exist)){
                    $data['status']=true;
                    $data['info']=$info;
                }
            }
            echo CJSON::encode($data);
        }
		
    }