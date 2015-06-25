<?php
	/**
	 * 
	 * @author zhangyong
	 * @ps:样板间管理  2014/10/25 更新
	 */
	class ShowroomController extends CmsController
	{
		/**
		 * 样板间列表
		 * @author zhangyong
		 */
		public function actionIndex($sid)
		{
            $params=$_GET;
            $like = '';//查询空间条件
            $name=null;
            $seachData = array('name'=>'','timeStart'=>'','timeEnd'=>'');
            $space = Space::model()->findByPk($sid,'is_del=0');
            if(intval($sid)<=0 || empty($space))
                throw new CHttpException(404,'空间'.$sid.'不存在或者已经删除！.');
            if(isset($params['name']) && !empty($params['name'])){
                $like = " and name like '%".trim($params['name'])."%'";
                $seachData['name']=$params['name'];
            }
            if(isset($params['timeStart']) && !empty($params['timeStart'])){
               $like.= " and create_time >= '".strtotime($params['timeStart'])."'";
               $seachData['timeStart']=$params['timeStart'];
           }
           if(isset($params['timeEnd']) && !empty($params['timeEnd']))
           {
               $like.= " and create_time <= '".strtotime($params['timeEnd'])."'";
               $seachData['timeEnd']=$params['timeEnd'];
           }
           
            $sql = "select * from tbl_showroom where is_del =0 and (parent_id=0 or parent_id is null) and space_id=".$sid." $like order by create_time desc";
            $data=$this->getIndex($sql);
            $ul=$data['list'];
            $pages=$data['pages'];
            $this->render('index',array('ul'=>$ul,'pages' => $pages,'seachData'=>$seachData,'sid'=>$sid));
        }
        
        /**
		 * 创建/编辑样板间
		 * @author zhangyong
		 */
		public function actionCreate($sid)
		{
            $params = $_POST;
            $space = Space::model()->findByPk($sid,'is_del=0');
            if(intval($sid)<=0 || empty($space))
                throw new CHttpException(404,'空间'.$sid.'不存在或者已经删除！.');
            $model=new Showroom();
            $oldImage='';
            if(isset($_GET['id']) && !empty($_GET['id'])){
                $model = Showroom::model ()->findByPk($_GET['id'],'is_del=0');
                $oldImage=$model['image'];
            }
            //获取视角数据
            $angles=array();
            if(!empty($space['pics'])){
                $pics=CJSON::decode($space['pics']);
                foreach ($pics as $k=>$p){
                    $angles[$k]=$k;
                }
            }
            //处理提交的样板间的数据
            if(isset($params['Showroom'])){
                $showroom=$params['Showroom'];
                $model->attributes=$showroom;
                $fileHelper=new FileHelper;
                $fileHelper->subFolder='showroom';
                if($fileHelper->hasUploadFile($model,'image')){
                    $model->image=$fileHelper->saveFile($model,'image',array('upyun'=>Yii::app()->params['upYun']));
                }else{
                    $model->image=$oldImage;
                }
                $model->space_id=$sid;
                $model->room_category=$space['room_category'];
                if(!isset($_GET['id']))
                    $model->isNewRecord = TRUE;
                if($model->save()){
                    
                    $this->redirect('/showroom/index/sid/'.$sid);
                }
            }
            $this->render('create',array('model'=>$model,'sid'=>$sid,'angles'=>$angles));
        }
        
        /**
		 * 删除样板间操作
		 * @author zhangyong
		 */
        public function actionDelete()
        {
            $result = array();
            $result['status'] = false;
            $result['info'] = '删除样板间失败！';
            if(isset($_POST['id']) && !empty($_POST['id'])){
                $id = intval($_POST['id']);
                $model = Showroom::model()->findByPk($id);
                $model->is_del = 1;
                if($model->save()){
                     $result['status'] = TRUE;
                     $result['info'] = '删除样板间成功！';
                     $result['id'] = $model->id;
                 }
            }
            echo CJSON::encode($result);
        }
        
        /**
		 * 
		 * 绑定元素/解绑元素
		 * @param int $id
         * @todo:原先是直接根据空间是否有元素,现在计划根据同视角下的层级是否有元素
		 */
		public function actionElement($id)
		{
			$model=Showroom::model()->findByAttributes(array('id'=>$id));
			if(isset($_POST['Element']))
			{
				try {
					$connection=Yii::app()->db;
					$transaction=$connection->beginTransaction();
					if(isset($_GET['unbind']) && empty($_GET['unbind']))
					{//解除绑定元素
						$sql="delete from {{showroom_element_relation}} 
								where showroom_id=".$id." and element_id=:elementId";
					}
					else 
					{
						$now=date('Y-m-d H:i:s');
						$sql="insert into {{showroom_element_relation}} (element_id,showroom_id)
								values (:elementId,".$id.")";
					}
					$command=$connection->createCommand($sql);
					$elementIds=$_POST['Element'];
					foreach ($elementIds as $elementId)
					{
						$command->bindParam(":elementId",$elementId,PDO::PARAM_INT);
						$command->execute();
					}
                    //操作授权样板间绑定/解绑元素
                    $showroom_ids = $this->getShowroom($id);//获取已授权的样板间id
                    if(isset($showroom_ids) && !empty($showroom_ids)){
                        //处理子集样板间
                        if(isset($_GET['unbind']) && empty($_GET['unbind']))
                        {//解除绑定元素
                            $showroom_ids = implode(',', $showroom_ids);
                            $sql="delete from {{showroom_element_relation}} 
                                    where showroom_id in (".$showroom_ids.") and element_id=:elementId";
                            $command=$connection->createCommand($sql);
                            foreach ($elementIds as $elementId)
                            {
                                $command->bindParam(":elementId",$elementId,PDO::PARAM_INT);
                                $command->execute();
                            }
                        }else{
                            foreach ($showroom_ids as $sid){
                                $sql="insert into {{showroom_element_relation}} (element_id,showroom_id)
                                        values (:elementId,".$sid.")";
                                $command=$connection->createCommand($sql);
                                foreach ($elementIds as $elementId)
                                {
                                    $command->bindParam(":elementId",$elementId,PDO::PARAM_INT);
                                    $command->execute();
                                }
                            }
                        }
                    }
                    //更新样板间对应的品牌和分类数据
                    $sql = 'select p.cat_id,p.brand_id,p.brandhall_id from tbl_showroom_element_relation as ser 
                        left join tbl_product_element_relation as per on per.element_id=ser.element_id 
                        left join sp_product as p on p.product_id=per.product_id 
                        where ser.showroom_id='.$id.' and p.is_delete=0 and p.is_show=1';
                    $showroomCB = $connection->createCommand($sql)->queryAll();
                    //清除老数据
                    ShowroomBrandRelation::model()->deleteAll('showroom_id='.$id);
                    ShowroomCategoryRelation::model()->deleteAll('showroom_id='.$id);
                    if(!empty($showroomCB)){
                        foreach ($showroomCB as $scb){
                            $hasSBR = ShowroomBrandRelation::model()->find('showroom_id='.$id.' and brandhall_id='.$scb['brandhall_id'].' and brand_id='.$scb['brand_id']);
                            if(empty($hasSBR)){
                                $ShowroomBrand = new ShowroomBrandRelation();
                                $ShowroomBrand->showroom_id = $id;
                                $ShowroomBrand->brand_id = $scb['brand_id'];
                                $ShowroomBrand->brandhall_id = $scb['brandhall_id'];
                                $ShowroomBrand->save();
                            }
                            $hasSCR = ShowroomCategoryRelation::model()->find('showroom_id='.$id.' and brandhall_id='.$scb['brandhall_id'].' and category_id='.$scb['cat_id']);
                            if(empty($hasSCR)){
                                $ShowroomCategory = new ShowroomCategoryRelation();
                                $ShowroomCategory->showroom_id = $id;
                                $ShowroomCategory->category_id = $scb['cat_id'];
                                $ShowroomCategory->brandhall_id = $scb['brandhall_id'];
                                $ShowroomCategory->save();
                            }
                        }
                    }
                    
					$transaction->commit();
//					$this->redirect(array('view','id'=>$id));
                    $this->redirect(array('showroom/element','id'=>$id));
				}catch(Exception $e){
					$transaction->rollback();
					throw new CHttpException(500,$e->getMessage());
				}
			}
            $like = '';
            $seachData=array('bindType'=>'bind');
            if(isset($_POST['name']) && !empty($_POST['name'])){
                $like = " and name like '%".trim($_POST['name'])."%'";
                $seachData['name']=$_POST['name'];
            }
            if(isset($_POST['timeStart']) && !empty($_POST['timeStart'])){
                $like.= " and create_time >= '".strtotime(trim($_POST['timeStart']))."'";
                $seachData['timeStart']=$_POST['timeStart'];
            }
            if(isset($_POST['timeEnd']) && !empty($_POST['timeEnd']))
            {
                $like.= " and create_time <= '".strtotime(trim($_POST['timeEnd']))."'";
                $seachData['timeEnd']=$_POST['timeEnd'];
            }
            if(isset($_GET['unbind']) && empty($_GET['unbind'])){
                $seachData['bindType']='unbind';
                //已绑定元素
                $sql="select * from tbl_element where is_del=0".$like." and id in (select element_id from tbl_showroom_element_relation where showroom_id=".$id.")
 						and id in (select element_id from tbl_space_element_relation where space_id=".$model->space_id.")";
            }else{
                //未绑定元素
                $sql="select * from tbl_element where is_del=0".$like." and id not in (select element_id from tbl_showroom_element_relation where showroom_id=".$id.")
 						and id in (select element_id from tbl_space_element_relation where space_id=".$model->space_id.")";
            }
            $criteria=new CDbCriteria();
            $result = Yii::app()->db->createCommand($sql)->query();
            $pages=new CPagination($result->rowCount);
            $count=$result->rowCount;
            $pages->pageSize= intval(Yii::app()->params['pageSize']);
            $pages->applyLimit($criteria);
            $result=Yii::app()->db->createCommand($sql." LIMIT :offset,:limit");
            $result->bindValue(':offset', $pages->currentPage*$pages->pageSize);
            $result->bindValue(':limit', $pages->pageSize);
            $dataProvider=$result->queryAll();
			$this->render('element',array(
				'dataProvider'=>$dataProvider,
				'model'=>$model,
                'pages'=>$pages,
                'count'=>$count,
                'seachData'=>$seachData
			));
		}
        
        //批量删除    
        function actionDeletecate(){
            $connection=Yii::app()->db;
            if (Yii::app()->request->isPostRequest) {
                    foreach ($_POST['id'] as $v){
                        $sql="update tbl_element set is_del = 1 where id=".$v;
                        $command=$connection->createCommand($sql);
                        $rs = $command->query();
                    }

                    if (isset(Yii::app()->request->isAjaxRequest) && $rs) {
                        echo CJSON::encode(array('success' => true));
                    }
            }
        }
        
        /**
		 * 授权的样板间，只查询二级样板间
		 * @author zhangyong
		 */
        function getShowroom($id) {
            $ids = array();
            $data = Showroom::model()->findAll("is_del=0 and brandhall_id is not null and parent_id=".$id);
            if(!empty($data)){
                foreach ($data as $sr){
                    if(!in_array($sr['id'], $ids))
                            array_push ($ids, $sr['id']);
                }
            }
            return $ids;
        }
        
        /**
		 * 更新样板间封面
         * @author zhangyong
		 */
		public function actionSetCover($id)
		{
            $plan = new Plan();
			$model=$this->loadModel($id);
            $planlist = $plan->findAll("showroom_id=".$model->id." and is_del=0");
            $checked = 0;
			$this->performAjaxValidation($model);
			if(isset($_POST['Showroom']))
			{
                $plan=  Plan::model()->findByPk($_POST['Showroom']['coverpic_id']);
                $model->coverpic_id = $plan['id'];
                $model->image = $plan['image'];
                if($model->save()){
                     $checked = $_POST['Showroom']['coverpic_id'];
                }
			}
			$this->render('updatecover',array('model'=>$model,'planlist'=>$planlist,'checked'=>$checked));
		}
        
        /**
		 * 
		 * 取消样板间封面
		 */
		public function actionCancelCover()
		{
            $data = array('status'=>false,'info'=>'操作失败！');
            if(isset($_POST['sid']) && !empty($_POST['sid'])){
                $showroom = Showroom::model()->findByPk($_POST['sid'],'is_del=0');
                if(!empty($showroom)){
                    $showroom->coverpic_id = null;
                    if($showroom->save())
                        $data = array('status'=>true,'info'=>'操作成功！');
                }
            }
            echo CJSON::encode($data);
        }
        
        public function loadModel($id)
		{
			$model=Showroom::model()->findByPk($id,'is_del=0');
			if(null===$model)
				throw new CHttpException(404,'样板间'.$id.'不存在或者已经删除！.');
			return $model;
		}
    }