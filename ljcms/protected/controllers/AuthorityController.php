<?php
	/**
	 * 
	 * @author zhangyong
	 * @PS: 权限管理  2014/7/26
	 */
	class AuthorityController extends CmsController
	{
        /**
		 * 分组列表
		 * @author zhangyong
		 */
		public function actionIndex()
		{
            $itemlist = Yii::app()->authManager->getAuthItems(2);//params: type and userid
            $this->render('index',array('itemlist'=>$itemlist));
        }
        
        /**
		 * 创建分组
		 * @author zhangyong
		 */
		public function actionCreate()
		{
            if(isset($_POST) && !empty($_POST)){
                $transaction = Yii::app()->db->beginTransaction();
                try{
                    $name = trim($_POST['name']);
                    $description = $_POST['description'];
                    $type = intval($_POST['type']);
                    if($name==='') throw new CHttpException(500,'请填写分组名称');
                    $transaction->commit();
                }  catch (Exception $e){
                    $transaction->rollback(); //如果操作失败, 数据回滚
                    throw new CHttpException(500,'参数错误！');
                }
                $rs = Yii::app()->authManager->createAuthItem($name,$type,$description,$bizRule=null,$data=null);
                if($rs->name) $this->redirect ('/authority/index');
            }
            $this->render('create');
        }
        
        /**
		 * 编辑分组
		 * @author zhangyong
		 */
        public function actionUpdate($name){
            $model = Yii::app()->authManager->getAuthItem($name);
            if(!isset($name) || empty($name) || !$model || $name=='administrator'){
                $this->redirect ('/authority/index');
            }
            $tip = '';
            if (isset($_POST['name']) && !empty($_POST['name'])) {
                $model->name = trim($_POST['name']);
                $model->description = trim($_POST['description']);
                Yii::app()->authManager->saveAuthItem($model);
                $this->redirect ('/authority/index');
            }
            $this->render('update',array('model'=>$model,'tips'=>$tip));
        }
        
        /**
		 * 删除分组
		 * @author zhangyong
		 */
        public function actionDelete(){
            $rs['status'] = 0;
            $rs['info'] = '删除失败！';
            if(isset($_POST['itemname']) && !empty($_POST['itemname']) && $_POST['itemname']!=='administrator'){
                $itemname = trim($_POST['itemname']);
                $ok = Yii::app()->authManager->removeAuthItem($itemname);
                if($ok){
                    $rs['status'] = 1;
                    $rs['info'] = '删除成功!';
                }
            }
            echo CJSON::encode($rs);
        }
        
        /**
		 * 已设置权限节点
		 * @author zhangyong
		 */
        public function actionSetrole($name){
            
             if(!isset($name) || empty($name) || $name=='administrator'){
                $this->redirect ('/authority/index');
            }
            $child = Yii::app()->authManager->getItemChildren($name);
            $this->render('setrole',array('itemname'=>$name,'child'=>$child));
        }
        
        /**
		 * 权限节点切换
		 * @author zhangyong
		 */
        public function actionSetroles($name){
            
             if(!isset($name) || empty($name)){
                $this->redirect ('/authority/index');
            }
            $type=null;
            if(isset($_GET['val']) && intval($_GET['val'])==0){
                $type=$_GET['val'];
                //0为已设置节点
                $child = Yii::app()->authManager->getItemChildren($name);
            }else{
                $type=$_GET['val'];
                //1为未设置节点
                $db = Yii::app()->db;
                $sql = "select * from cms_auth_item where type=1 and name not in (select child from cms_auth_item_child where parent ='{$name}')";
                $child = $db->createCommand($sql)->queryAll();
            }
            $this->renderPartial('roles',array('itemname'=>$name,'child'=>$child,'type'=>$type));
        }
        
        /**
		 * 添加权限节点
		 * @author zhangyong
		 */
        public function actionAddrole($name){
             $model = Yii::app()->authManager->getAuthItem($name);
            if(!isset($name) || empty($name) || !$model){
                $this->redirect ('/authority/index');
            }
            
            if (isset($_POST['name']) && !empty($_POST['name'])) {
                $transaction = Yii::app()->db->beginTransaction();
                try{
                    $name = trim($_POST['name']);
                    $name_array = explode('_', $name);

                    if(!is_numeric($_POST['set'])){
                       $name = $name."_".$_POST['set']; 
                       $data['role'] = $_POST['set'];
                    }
                   $data['controler'] = $name_array[0];
                   $data['action'] = $name_array[1];

                   $description = $_POST['description'];
                   $type = intval($_POST['type']);
                   if($name==='') throw new CHttpException(500,'请填写分组名称');
                   $rs = Yii::app()->authManager->createAuthItem($name,$type,$description,$bizRule=null,$data);
                   if($rs){
                        Yii::app()->authManager->addItemChild($_POST['parent'],$name);
                   }
                    $transaction->commit(); //提交事务会真正的执行数据库操作
                   $this->redirect ('/authority/setrole/name/'.$_POST['parent']);
                }  catch (Exception $e){
                    $transaction->rollback(); //如果操作失败, 数据回滚
                }
            }
            $this->render('addrole',array('model'=>$model));
        }
        
        /**
		 * 批量取消设置节点
		 * @author zhangyong
		 */
        public function actionBatchcancel(){
            $status = FALSE;
            if(isset($_POST['itemname']) && isset($_POST['child'])){
                foreach ($_POST['child'] as $child) {
                    $rs = Yii::app()->authManager->removeItemChild($_POST['itemname'],$child);
                }
                if($rs) $status = TRUE;
            }
            echo $status;
        }
        
        /**
		 * 批量设置节点
		 * @author zhangyong
		 */
        public function actionBatchset(){
            $status = FALSE;
            if(isset($_POST['itemname']) && isset($_POST['child'])){
                foreach ($_POST['child'] as $child) {
                    $rs = Yii::app()->authManager->addItemChild($_POST['itemname'],$child);
                }
                if($rs) $status = TRUE;
            }
            echo $status;
        }
        
        /**
		 * 设置用户权限
		 * @author zhangyong
		 */
        public function actionSetauth(){
            $itemlist = Yii::app()->authManager->getAuthItems(2);//params: type and userid
            $userid = 0;
            if(isset($_GET['userid']) && is_numeric($_GET['userid'])){
                $userid = $_GET['userid'];
            }
            $this->render('setauth',array('itemlist'=>$itemlist,'userid'=>$userid));
        }
        
        /**
		 * 处理设置用户权限
		 * @author zhangyong
		 */
        public function actionDoSetauth(){
             $status = FALSE;
            if(isset($_POST['itemname']) && isset($_POST['userid'])){
                foreach ($_POST['itemname'] as $itemname) {
                    $ok = Yii::app()->authManager->isAssigned($itemname,$_POST['userid']);
                    if(!$ok) $rs = Yii::app()->authManager->assign($itemname,$_POST['userid']);
                    else
                        $rs = FALSE;
                }
                if($rs) $status = TRUE;
            }
            echo $status;
        }
        
        /**
		 * 处理设置取消用户权限
		 * @author zhangyong
		 */
        public function actionDoSetCancelauth(){
             $status = FALSE;
            if(isset($_POST['itemname']) && isset($_POST['userid'])){
                foreach ($_POST['itemname'] as $itemname) {
                    $ok = Yii::app()->authManager->isAssigned($itemname,$_POST['userid']);
                    if($ok) $rs = Yii::app()->authManager->revoke($itemname,$_POST['userid']);
                    else
                        $rs = FALSE;
                }
                if($rs) $status = TRUE;
            }
            echo $status;
        }
		
    }