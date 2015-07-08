<?php
	/**
	 * 
	 * @author zhangyong
	 * @ps:空间管理 2014/10/25更新
	 */
	class SpaceController extends CmsController
	{
		/**
		 * 空间列表
		 * @author zhangyong
		 */
		public function actionIndex()
		{
            $like = '';//查询空间条件
            $connect=Yii::app()->db;
            $name=null;
            $params=$_GET;
            $seachData = array('name'=>'','timeStart'=>'','timeEnd'=>'');
            if(isset($params['name']) && !empty($params['name'])){
                $like = " and (name like '%".trim($params['name'])."%' or out_name like '%".trim($params['name'])."%')";
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
            $sql = "select * from tbl_space where is_del =0 $like order by create_time desc";
            $data=$this->getIndex($sql);
            $ul=$data['list'];
            $pages=$data['pages'];
            $count = $data['count'];
            $this->render('index',array('ul'=>$ul,'pages' => $pages,'seachData'=>$seachData,'count'=>$count));
        }
        
        /**
		 * 创建空间
		 * @author zhangyong
		 */
		public function actionCreate()
		{
            $model=new Space();
            $params=$_POST;
            try{
                if(isset($params['Space'])){
                    $space=$params['Space'];
                    $model->attributes=$space;
                    //处理上传的图片
                    $fileHelper=new FileHelper;
                    $fileHelper->subFolder='space';
                    $image=$fileHelper->saveFile($model,'image',array('upyun'=>Yii::app()->params['upYun']));
                    $model->image=$image;//存储缩略图
                    $img=array('pics','showpics');
                    $images=$fileHelper->saveBatchFilesByAttributes($model,$img,array('upyun'=>Yii::app()->params['upYun']));
                    foreach($img as $img){
                        if(!empty($images[$img])){
                            $pics=array();
                            foreach ($images[$img] as $kc=>$ps){
                                $pics[$params['angle'][$kc]]=$ps;
                            }
                            $model->$img=CJSON::encode($pics);
                        }
                    }
                    $floorplans=array();
                    $ang=array();
                    $uploadFiles=CUploadedFile::getInstances($model, 'floorplan');
                    if(!empty($uploadFiles)){
                        $pics=array();
                        $floorplans=$fileHelper->saveBatchFiles($model,'floorplan',array('upyun'=>Yii::app()->params['upYun']));
                        foreach ($params['angle'] as $ka=>$an){
                            if($fileHelper->hasUploadFile($model,'floorplan['.$ka.']')){
                                $ang[]=$an;//存储上传了floorplan图片的视角
                            }
                        }
                        foreach ($floorplans as $fp=>$fpval){
                            $pics[$ang[$fp]]=$fpval;//存储对应视角下的floorplan图片
                        }
                        $model->floorplan=CJSON::encode($pics);
                    }
                    $model->isNewRecord = TRUE;
                    if($model->save()){
                        if(isset($_GET['fid'])){
                            //信息上传空间入口
                            $info=  Info::model()->findByPk($_GET['fid']);
                            $info->space_id=$model->id;
                            $info->save();
                        }
                        $this->redirect('/space/index');  
                    }
                }
            } catch (Exception $e) {
                throw new CHttpException(500,$e->getMessage());//测试时使用    
            }
            $this->render('create',array('model'=>$model));
        }
        
        /**
		 * 编辑空间
		 * @author zhangyong
		 */
		public function actionUpdate($id)
		{
            $model = Space::model()->findByAttributes(array('id'=>$id));
            $params=$_POST;
            $oldImage=$model['image'];
            $oldPics=array();
            $oldShowpics=array();
            $oldFloorplan=array();
            if(!empty($model['pics']))
                $oldPics=$model['pics'];
            if(!empty($model['showpics']))
                $oldShowpics=$model['showpics'];
            if(!empty($model['floorplan']))
                $oldFloorplan=$model['floorplan'];
            $colimg=array('pics','showpics','floorplan');
            $imgData=array($oldPics,$oldShowpics,$oldFloorplan);
            $upImg=array();//已上传的视角图片，按视角存储
            foreach ($imgData as $kg=>$ida){
                if(!empty($ida)){
                    $temp=CJSON::decode($ida);
                    foreach ($temp as $kt=>$te){
                        $upImg[$kt][$colimg[$kg]]=$te;
                    }
                }
            }
            try{
                if(isset($params['Space'])){
                    $model->attributes=$params['Space'];
                    //处理上传的图片
                    $fileHelper=new FileHelper;
                    $fileHelper->subFolder='space';
                    //处理缩略图
                    if($fileHelper->hasUploadFile($model,'image')){
                        $model->image=$fileHelper->saveFile($model,'image',array('upyun'=>Yii::app()->params['upYun']));
                    }else{
                        if(!empty($oldImage))
                            $model->image=$oldImage;
                    }
                    //批量处理其他几组图片pics,showpics,floorplan
                    $IMG=array('pics','showpics','floorplan');
                    $oldValue=array($oldPics,$oldShowpics,$oldFloorplan);
                    foreach ($IMG as $ki=>$mg){
                        $uploadFiles=CUploadedFile::getInstances($model,$mg);
                        if(!empty($uploadFiles)){
                            $pics=array();
                            $ang=array();
                            $floorplans=array();
                            $floorplans=$fileHelper->saveBatchFiles($model,$mg,array('upyun'=>Yii::app()->params['upYun']));
                            foreach ($params['angle'] as $ka=>$an){
                                if($fileHelper->hasUploadFile($model,''.$mg.'['.$ka.']')){
                                    $ang[]=$an;//存储上传了的图片的视角
                                }
                            }
                            if(!empty($oldValue[$ki]))
                                $pics=CJSON::decode($oldValue[$ki]);
                            foreach ($floorplans as $fp=>$fpval){
                                $pics[$ang[$fp]]=$fpval;//存储对应视角下的图片
                            }
                            $model->$mg=CJSON::encode($pics);
                        }else{
                            $newAlgle=$params['angle'];
                            $oldJson= json_decode($oldValue[$ki], true);
                            $newAP=null;
                            $oldJsonK=  array_keys($oldJson);
                            foreach ($newAlgle as $ka=>$na){
                                if(isset($oldJsonK[$ka])){
                                    if($na!=$oldJsonK[$ka]){
                                         $newAP[$na]=$oldJson[$oldJsonK[$ka]];
                                     }else{
                                         $newAP[$oldJsonK[$ka]]=$oldJson[$oldJsonK[$ka]];
                                     }
                                }
                            }
                            $newAP=  json_encode($newAP);
                            $model->$mg=$newAP;
                        }
                    }
                    if($model->save()){
                        $this->redirect('/space/index');  
                    }
                }
            } catch (Exception $e) {
                throw new CHttpException(500,$e->getMessage());//测试时使用    
            }
            $this->render('update',array('model'=>$model,'upImg'=>$upImg));
        }
        
        /**
        * 删除空间操作
        * @PS:需删除与空间相关的样板间、元素、户型图打点(通过样板间id或方案id关联)以及
        *      方案（如果被删除的方案被设置为某个整装的封面，则删除该整装以及整装下的所有方案）
        * @author zhangyong
        */
       public function actionDelete()
       {
           $result = array('status'=>false,'info'=>'删除空间失败！');
           $connection=Yii::app()->db;
           try {
                if(isset($_POST['id']) && !empty($_POST['id'])){
                    $transaction=$connection->beginTransaction();
                    $id = intval($_POST['id']);
                    //删除空间下的样板间，包含热点
                    $sql='delete from tbl_apartment_pic_point where type=1 and is_show=1 and obj_id in (select id from tbl_showroom where is_del=0 and space_id='.$id.')';
                    $connection->createCommand($sql)->execute();//删除样板间关联的户型图打点
                    $sr=new CDbCriteria;
                    $sr->condition='space_id='.$id;
                    $up_sr=array('is_del'=>1);
                    Showroom::model()->updateAll($up_sr, $sr);
                    //删除空间绑定的元素
                    $e=new CDbCriteria;
                    $e->condition='id in ( select element_id from tbl_space_element_relation where space_id='. $id .')';
                    $up_e=array('is_del'=>1);
                    Element::model()->updateAll($up_e, $e);
                    //删除空间下的方案，包括热点
                    $pid=array();//存储所有与被删除空间相关的方案ID
                    $plans = Plan::model()->findAll(array('select'=>'id','condition'=>'is_del=0 and space_id='.$id));
                     if(!empty($plans)){
                         foreach ($plans as $plan_id){
                             if(!in_array($plan_id['id'], $pid))
                                 array_push ($pid, $plan_id['id']);
                             //获取整装
                             $planset=Plan::model()->find(array('select'=>'id','condition'=>'is_del=0 and type=2 and coverpic_id='.$plan_id['id']));
                             if(!empty($planset)){
                                 if(!in_array($planset['id'], $pid))
                                         array_push ($pid, $planset['id']);
                                 //获取整装下的方案
                                 $psArr=Plan::model()->findAll(array('select'=>'id','condition'=>'is_del=0 and type=3 and set_id='.$planset['id']));
                                 if(!empty($psArr)){
                                     foreach ($psArr as $ps){
                                         if(!in_array($ps['id'], $pid))
                                                 array_push ($pid, $ps['id']);
                                     }
                                 }
                             }
                         }
                     }

                     if(!empty($pid)){
                         $pStrings = implode(',', $pid);
                         //删除方案关联的户型图打点
                         $sql='delete from tbl_apartment_pic_point where is_show=1 and type=2 and obj_id in ( '.$pStrings.' )';
                         $connection->createCommand($sql)->execute();
                         //删除方案
                         $p=new CDbCriteria;
                         $p->condition='id in ( '. $pStrings .' )';
                         $up_p=array('is_del'=>1);
                         Plan::model()->updateAll($up_p, $p);
                     }
                    //修改空间的is_del状态
                    $model = Space::model()->findByPk($id);
                    $model->is_del = 1;
                    if($model->save()){
                        $result = array('status'=>true,'info'=>'删除空间成功！');
                         $result['id'] = $model->id;
                     }
                     $transaction->commit();
                }
           } catch (Exception $e) {
               $transaction->rollback();
               $result['status']=false;
               $result['info']=$e->getMessage();
            }
           echo CJSON::encode($result);
       }
        
        /**
		 * 添加空间视角
		 * @author zhangyong
		 */
        public function actionAddAngle()
        {
            $model=new Space();
            $form = $this->Widget('CActiveForm', array());
            $this->renderPartial('addAngle',array('model'=>$model,'form'=>$form));
        }
        
        /**
        * 
        * 绑定元素/解绑元素
        * @param int $id
        */
       public function actionElement($id)
       {
           $model=$this->loadModel($id);
           $rg=Space::model()->findByAttributes(array('id'=>$id));
           $roomcategory = isset($rg->room_category) ? $rg->room_category : null ;
           $connection=Yii::app()->db;
           $params = $_POST;
           if(isset($params['Element']))
           {
               $elementIds=$params['Element'];
               try {
                   $transaction=$connection->beginTransaction();
                   if(isset($_GET['unbind']) && empty($_GET['unbind']))
                   {//解除绑定元素
                       $sql="delete from tbl_space_element_relation 
                               where space_id=".$id." and element_id in (".implode(',', $elementIds).")";
                       $sqlShowroom="delete sre from tbl_showroom_element_relation as sre,tbl_showroom as sr
                                       where sre.showroom_id=sr.id and sr.space_id=".$id." and sre.element_id in (".implode(',', $elementIds).")";
                       $connection->createCommand($sql)->execute();
                       $connection->createCommand($sqlShowroom)->execute();
                   }
                   else 
                   {
                       $sql="insert into tbl_space_element_relation (element_id,space_id,room_category)
                               values (:elementId,".$id.",".$roomcategory.")";
                       $command=$connection->createCommand($sql);
                       foreach ($elementIds as $elementId)
                       {
                           $command->bindParam(":elementId",$elementId,PDO::PARAM_INT);
                           $command->execute();
                       }
                   }
                   $transaction->commit();
   //					$this->redirect(array('space/element','id'=>$id,'unbind'=>''));
                   $this->redirect(array('space/element','id'=>$id));
               }catch(Exception $e){
                   $transaction->rollback();
                   throw new CHttpException(500,$e->getMessage());
               }
           }
           $like = '';
           $seachData=array('bindType'=>'bind');
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
           if(isset($_GET['unbind']) && empty($_GET['unbind'])){
               $seachData['bindType']='unbind';
               //获取绑定元素
               $sql="select * from tbl_element where is_del=0 $like and id in (select element_id from tbl_space_element_relation where space_id=".$id.")";
           }else{
               //未绑定元素
               $sql="select * from tbl_element where is_del=0 $like and id not in (select element_id from tbl_space_element_relation where space_id=".$id.")";
           }
           $criteria=new CDbCriteria();
           $result = $connection->createCommand($sql)->query();
           $pages=new CPagination($result->rowCount);
           $count=$result->rowCount;
           $pages->pageSize= intval(Yii::app()->params['pageSize']);
           $pages->applyLimit($criteria);
           $result=$connection->createCommand($sql." LIMIT :offset,:limit");
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

       //删除
       public function actionDelys(){
           $rs['status'] = 0;
           $rs['info'] = '删除失败！';
           if(isset($_POST['id']) && !empty($_POST['id'])){
               $model = Element::model()->findByAttributes(array( 'id'=> intval($_POST['id'])));
               if(!empty($model))
               {
                   $model->is_del= 1;
                   $model->save();
                   $ret['status']=1;
                   $rs['info'] = '删除成功!';
               }
           }
           echo CJSON::encode($rs);
       }
       
        //批量删除    
        function actionBatchDelete(){
            $connection=Yii::app()->db;
            if (Yii::app()->request->isPostRequest) {
                foreach ($_POST['id'] as $v){
                    $sql="update tbl_element set is_del = 1 where id=".$v;
                    $command=$connection->createCommand($sql);
                    $rs = $command->query();
                }
                if(isset(Yii::app()->request->isAjaxRequest) && $rs)
                    echo CJSON::encode(array('success' => true));
            }
        }

        //初始化空间信息
        public function loadModel($id)
        {
            $model=Space::model()->findByPk($id,'is_del=0');
            if(null===$model)
                throw new CHttpException(404,'空间'.$id.'不存在或者已经删除！.');
            return $model;
        }
        
        /**
        * 
        * 更新绑定元素
        */
       public function actionUpdateElement($id)
       {
           $model=Element::model()->findByPk($id);
           if(isset($_GET['model']) && !empty($_GET['model'])){
               $model=$_GET['model']::model()->findByPk($id);
           }
           $image=$model['image'];
           if(isset($_POST['Element']) || isset($_POST['ElementTemp'])){
               $callbak=$_POST['callbak'];
               if(isset($_POST['ElementTemp']) && !empty($_POST['ElementTemp'])){
                   $element=$_POST['ElementTemp'];
               }else{
                   $element=$_POST['Element'];
               }
               $model->attributes=$element;
//               if(!empty($element['image']) && isset($element['image'])){
//                   $model->image = $this->saveImg($element['image']);
//               }else{
//                   if(empty($image)){
//                       echo '<html><head><meta http-equiv="Content-Type" content="textml; charset=utf-8"><title>提示</title>'.
//                                   '<script language=javascript>alert("请选择上传文件！");location.href="";</script></head><html>';
//                       exit;
//                   }
//               }
               if($model->save())
                   $this->redirect($callbak);
           }
           $this->render('updateElement',array('model'=>$model));
       }
    }