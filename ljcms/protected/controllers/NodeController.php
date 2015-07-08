<?php
	/**
	 * 
	 * @author zhangyong
	 * @PS：层级管理 2014/10/25 更新
	 */
	class NodeController extends CmsController
	{
		/**
		 *
		 * 层级列表
		 */
		public function actionIndex($sid)
		{
			if(isset($_GET['sid'])){
                $sid=$_GET['sid'];
            }else {
                throw new CHttpException(400,'空间不存在或已经删除！');
            }
            $like = '';
            if(isset($_GET['name']) && !empty($_GET['name'])){
                $like = " and name like '%".trim($_GET['name'])."%'";
            }
            $sql='select * from tbl_node where space_id='.$sid.' '.$like.' order by create_time asc';
            $criteria=new CDbCriteria();
            $result = Yii::app()->db->createCommand($sql)->query();
            $pages=new CPagination($result->rowCount);
            $count=$result->rowCount;
            $pages->pageSize=10;
            $pages->applyLimit($criteria);
            $result=Yii::app()->db->createCommand($sql." LIMIT :offset,:limit");
            $result->bindValue(':offset', $pages->currentPage*$pages->pageSize);
            $result->bindValue(':limit', $pages->pageSize);
            $dataProvider=$result->queryAll();
			$this->render('index',array(
				'dataProvider'=>$dataProvider,
				'sid'=>$sid,
                'pages'=>$pages,
                'count'=>$count,
			));
		}
        
		/**
		 * 
		 * 创建层级
		 * @param int $sid
		 * @todo:多视角
		 */
		public function actionCreate($sid)
		{
			$model=new Node('create');
			$this->performAjaxValidation($model);
			if(isset($_POST['Node']))
			{
				$model->attributes=$_POST['Node'];
				$model->space_id=$sid;
				$layer=explode(',', $_POST['Node']['layer']);
                $angles=explode(',', $_POST['Node']['angle']);
                $layers=array();
                foreach ($angles as $key=>$angle)
                {
                    $layers[$angle]=$layer[$key];
                }
				$model->layer=json_encode($layers);
				if($model->save())
					$this->redirect(array('index','sid'=>$model->space_id));
			}
			
			$this->render('create',array(
					'model'=>$model,
			));
		}
		/**
		 * 
		 * 更新层级
		 * @todo:多视角
		 */
		public function actionUpdate($sid,$id)
		{
			$model=$this->loadModel($id);
			$arr=json_decode($model->layer,true);
			$model->layer=implode(',', $arr);
			$angle=implode(',', array_keys($arr));
			$this->performAjaxValidation($model);
			if(isset($_POST['Node']))
			{
				$model->attributes=$_POST['Node'];
				$model->space_id=$sid;
                $layer=explode(',', $_POST['Node']['layer']);
                $angles=explode(',', $_POST['Node']['angle']);
                $layers=array();
                foreach ($angles as $key=>$angle)
                {
                    $layers[$angle]=$layer[$key];
                }
                
				$model->layer=json_encode($layers);
				if($model->save())
					$this->redirect(array('index','sid'=>$model->space_id));
			}
            
			$this->render('update',array(
					'model'=>$model,'angle'=>$angle
			));
		}
        
        /**
		 *
		 * 删除层级
		 */
        public function actionDel()
		{
            $rs['status'] = false;
            if(isset($_POST['id'])){
                $models=Node::model()->findByPk($_POST['id']);
                if(!empty($models['layer'])){
                    $layers=json_decode($models['layer'], true);
                    if(!empty($layers)){
                        foreach ($layers as $a=>$l){
                            ElementLayer::model()->deleteAll("space_id=".$models['space_id']." and angle='".$a."' and layer='".$l."'");
                        }
                    }
                }
                Node::model()->deleteByPk($_POST['id']);
                $rs['status'] = TRUE;
            }
            echo CJSON::encode($rs);
        }  
        
        //小铅笔修改名称
        function actionUpdateview(){
		
            $model = Node::model();
            $connection=Yii::app()->db;
            $model->id = intval($_POST['id']);
            $val = $_POST['val'];
            $sql="update tbl_node set {$_POST['type']} = '{$val}' where id=".$model->id."";
            $command=$connection->createCommand($sql);
            $rs = $command->query();
            echo $rs;
        }
		
		public function loadModel($id)
		{
			$model=Node::model()->findByPk($id);
			if(null===$model)
				throw new CHttpException(404,'层级'.$id.'不存在或者已经删除！.');
			return $model;
		}  
        
        /**
		 * 
		 * 绑定or添加元素//解除绑定元素
		 * @param int $id
		 */
		public function actionElement($id)
		{
			$model=$this->loadModel($id);
            $alArr=json_decode($model->layer,true);
            $angle=null;
            $layer=null;
            if(!empty($alArr)){
                foreach ($alArr as $kal=>$kval){
                    $angle=$kal;
                    $layer=$kval;
                }
            }
            if(empty($angle) || empty($layer)){
                throw new CHttpException(404,'空间'.$model->space_id.'视角'.$angle.'层级'.$layer.'不存在或者已经删除！.');
            }
			if(isset($_POST['Element']))
			{
				try 
				{
					$connection=Yii::app()->db;
					$transaction=$connection->beginTransaction();
					
					$elementIds=$_POST['Element'];
                    if(!empty($elementIds)){
                        if(isset($_GET['unbind']) && empty($_GET['unbind']))
                        {//解除绑定元素
                            foreach ($elementIds as $elementId)
                            {
                                $sql="delete from tbl_element_layer where element_id=".$elementId." "
                                        . "and space_id=".$model->space_id." and angle='".$angle."' and layer='".$layer."'";
                                $connection->createCommand($sql)->execute();
                            }
                        }else {
                            foreach ($elementIds as $elementId)
                            {
                                $nodeName=Node::model()->find(array('select'=>'name',
                                    'condition'=>"space_id=".$model->space_id." and layer='".json_encode(array($angle,$layer))."'"));
                                $sql="insert into tbl_element_layer (element_id,space_id,angle,layer,name)
                                        values (".$elementId.",".$model->space_id.",'".$angle."','".$layer."','".$nodeName['name']."')";
                                $connection->createCommand($sql)->execute();
                            }
                        }
                    }
                        
					$transaction->commit();
					$this->redirect(array('node/element','id'=>$id));
				}
				catch(Exception $e)
				{
					$transaction->rollback();
					throw new CHttpException(500,$e->getMessage());
				}
			}
            $like = '';
            $seachData=array('bindType'=>'bind');
            if(isset($_POST['name']) && !empty($_POST['name'])){
                $like = " and e.name like '%".trim($_POST['name'])."%'";
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
                //获取绑定元素
                $sqlel="select e.* from tbl_element as e inner join tbl_element_layer as el on e.id=el.element_id "
                        . "where e.is_del=0".$like." and el.space_id=".$model->space_id." and el.angle='". $angle ."' and el.layer='". $layer ."'";
            }else{
                //未绑定元素
                $sqlel="select e.* from tbl_element as e inner join tbl_space_element_relation as ser on e.id=ser.element_id "
                        . "where e.is_del=0".$like." and ser.space_id=".$model->space_id." and ser.element_id not in (select element_id from tbl_element_layer where space_id=".$model->space_id.")";
            }
            $criteria=new CDbCriteria();
            $result = Yii::app()->db->createCommand($sqlel)->query();
            $pages=new CPagination($result->rowCount);
            $count=$result->rowCount;
            $pages->pageSize= intval(Yii::app()->params['pageSize']);
            $pages->applyLimit($criteria);
            $result=Yii::app()->db->createCommand($sqlel." LIMIT :offset,:limit");
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
		
	}