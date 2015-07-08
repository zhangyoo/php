<?php
	/**
	 * 
	 * @author zhangyong
	 * @ps:任务分配模块  2014/10/20
     * 任务可以以订单或者单个个体（单个个体指的是单个素材或者单个空间）形式分配任务
     * 以订单的形式：分配类型->订单的类型->订单id(即obj_id)
     * 以单个个体形式：分配类型->订单的类型: 1.建模订单->素材id(即obj_id)->任务类型(即task_type,模型/贴图/QC)
     *                                     2.渲染订单->订单id(即obj_id)->空间id(即space_id) 
     *                                     3.新空间渲染订单->订单id(即obj_id)
	 */
	class TaskController extends CmsController
	{
        /**
		 * 任务列表
		 * @author zhangyong
		 */
		public function actionIndex()
		{
            $like = '';//查询订单条件
            $params=$_GET;
            $connection=Yii::app()->db;
            $receiver = Yii::app()->user->getId();
            if(!isset($receiver) || empty($receiver))
                throw new CException('未登录...');
            if(!isset($_GET['mold']) && !isset($_GET['space']))
                throw new CHttpException(500,'缺少必要参数...');
            $con = '';//查询被分配任务的条件
            $seachData=array('name'=>'','type'=>'','timeStart'=>'','timeEnd'=>'','status'=>'');
            //搜索条件
            if(isset($params['name']) && !empty($params['name'])){
                if(isset($_GET['space'])){
                    $like .= " and ( o.title like '%".trim($params['name'])."%' or osr.space_name like '%".trim($params['name'])."%' or o.number like '%".trim($params['name'])."%' )";
                }else{
                    $like .= " and ( title like '%".trim($params['name'])."%' or number like '%".trim($params['name'])."%' )";
                }
                $seachData['name']=$params['name'];
            }
            if(isset($params['type']) && $params['type'] !=''){
                if(isset($_GET['space'])){
                    $like .= " and o.type=".$params['type']."";
                }else{
                    $like .= " and type=".$params['type']."";
                }
                $seachData['type']=$params['type'];
            }
            if(isset($params['status']) && $params['status'] !=''){
                if(isset($_GET['space'])){
                    $like .=" and ( o.status=".$params['status']." or osr.status=".$params['status'].")";
                }else{
                    $like .= " and status=".$params['status']."";
                }
                $seachData['status']=$params['status'];
            }
            if(isset($params['timeStart']) && !empty($params['timeStart'])){
                if(isset($_GET['space'])){
                    $like.= " and o.create_time >= '".strtotime(trim($params['timeStart']))."'";
                }else{
                    $like.= " and create_time >= '".strtotime(trim($params['timeStart']))."'";
                }
                $seachData['timeStart']=$params['timeStart'];
            }
            if(isset($params['timeEnd']) && !empty($params['timeEnd']))
            {
                if(isset($_GET['space'])){
                    $like.= " and o.create_time <= '".strtotime(trim($params['timeEnd']))."'";
                }else{
                    $like.= " and create_time <= '".strtotime(trim($params['timeEnd']))."'";
                }
                $seachData['timeEnd']=$params['timeEnd'];
            }
            
            if(isset($_GET['mold'])){
                $con = ' and order_type in ('. implode(',', Yii::app()->params['allowCinfo']) .')';
            }else{
                $con = ' and order_type not in ('. implode(',', Yii::app()->params['allowCinfo']) .')';
            }
            $taskData = TaskAllocation::model()->findAll('receiver='.$receiver.$con);
            $taskOrder = array();//任务列表
            $pages = array();//避免任务列表为空时，页面报错
            $data = array('list'=>array(),'pages'=>array());
            if(!empty($taskData)){
                if(isset($_GET['mold'])){
                    //获取被分配的建模订单
                    $orderIds = array();
                    $infoIds = array();
                    $orders = array();
                    $ac = '';
                    foreach ($taskData as $task){
                        if($task['allocation_type']==0){
                            if(!in_array($task['obj_id'],$orderIds))
                                    array_push ($orderIds, $task['obj_id']);
                        }else{
                            if(!in_array($task['obj_id'],$infoIds))
                                    array_push ($infoIds, $task['obj_id']);
                        }
                    }
                    if(!empty($orderIds) && !empty($infoIds)){
                        $ac =' and ( id in (select order_id from tbl_order_info_relation where info_id in ('. implode(',', $infoIds) .')) or id in('. implode(',', $orderIds) .'))';
                    }elseif(!empty($infoIds)){
                        $ac = ' and id in (select order_id from tbl_order_info_relation where info_id in ('. implode(',', $infoIds) .'))';
                        
                    }elseif (!empty($orderIds)) {
                        $ac =' and id in('. implode(',', $orderIds) .')';
                    }
                    $sql = "select * from tbl_order where is_del=0 and type in (". implode(',', Yii::app()->params['allowCinfo']) .")".$ac.$like." group by id order by create_time desc";
                    $data=$this->getIndex($sql);
                }else{
                    //获取被分配的渲染订单(包含渲染订单和新空间渲染订单)
                    $sql = "select o.*,ta.status as task_status,ta.is_check,osr.space_name,osr.space_id,osr.status as space_status from tbl_task_allocation as ta 
                        left join tbl_order_space_relation as osr on (ta.obj_id=osr.order_id and ta.space_id=osr.space_id) 
                        left join tbl_order as o on ta.obj_id=o.id 
                        where o.is_del=0 and ta.receiver=".$receiver.$like." and ta.order_type not in (". implode(',', Yii::app()->params['allowCinfo']) .") order by o.create_time desc";
                    $data=$this->getIndex($sql);
                }
                $taskOrder=$data['list'];
                $pages=$data['pages'];
            }
            $this->render('index',array('taskOrder'=>$taskOrder,'pages'=>$pages,'seachData'=>$seachData));
        }
        
        /**
		 * 订单列表(建模订单/贴图订单/渲染订单)
		 * @author zhangyong
		 */
		public function actionOrder()
		{
            $like = ' and type in ('. implode(',', Yii::app()->params['allowCinfo']) .')';//查询订单条件
            $params=$_GET;
            $connection=Yii::app()->db;
            if(!isset($_GET['mold']) && !isset($_GET['space']))
                throw new CHttpException(500,'缺少必要参数...');
            if(isset($_GET['space']))
                $like = ' and type not in ('. implode(',', Yii::app()->params['allowCinfo']) .')';
            //可指定任务的员工
            $itemname = 'mold';
            if(isset($_GET['space']))
                $itemname = 'space';
            $taskUser = User::model()->findAll(array(
                'select'=>'id,username',
                'condition'=>"is_del=0 and id in ( select userid from cms_auth_assignment where itemname='".$itemname."')",
            ));
            if(!empty($taskUser))
                $taskUser = CHtml::listData($taskUser,'id','username');
            
            //搜索条件
            $seachData=array('name'=>'','type'=>'','timeStart'=>'','timeEnd'=>'','status'=>'','brandhall'=>'');
            if(isset($params['brandhall']) && !empty($params['brandhall'])){
                if(isset($_GET['space'])){
                    $like .= " and o.id in (select order_id from tbl_order_brandhall_relation where brandhall_id=".$params['brandhall'].")";
                }else{
                    $like .= " and id in (select order_id from tbl_order_brandhall_relation where brandhall_id=".$params['brandhall'].")";
                }
                $seachData['brandhall']=$params['brandhall'];
            }
            if(isset($params['name']) && !empty($params['name'])){
                if(isset($_GET['space'])){
                    $like .= " and ( o.title like '%".trim($params['name'])."%' or osr.space_name like '%".trim($params['name'])."%' or o.number like '%".trim($params['name'])."%' )";
                }else{
                    $like .= " and ( title like '%".trim($params['name'])."%' or number like '%".trim($params['name'])."%' )";
                }
                $seachData['name']=$params['name'];
            }
            if(isset($params['type']) && $params['type'] !=''){
                if(isset($_GET['space'])){
                    $like .= " and o.type=".$params['type']."";
                }else{
                    $like .= " and type=".$params['type']."";
                }
                $seachData['type']=$params['type'];
            }
            if(isset($params['status']) && $params['status'] !=''){
                if(isset($_GET['space'])){
                    $like .=" and ( o.status=".$params['status']." or osr.status=".$params['status'].")";
                }else{
                    $like .= " and status=".$params['status']."";
                }
                $seachData['status']=$params['status'];
            }
            if(isset($params['timeStart']) && !empty($params['timeStart'])){
                if(isset($_GET['space'])){
                    $like.= " and o.create_time >= '".strtotime(trim($params['timeStart']))."'";
                }else{
                    $like.= " and create_time >= '".strtotime(trim($params['timeStart']))."'";
                }
                $seachData['timeStart']=$params['timeStart'];
            }
            if(isset($params['timeEnd']) && !empty($params['timeEnd'])){
                if(isset($_GET['space'])){
                    $like.= " and o.create_time <= '".strtotime(trim($params['timeEnd']))."'";
                }else{
                    $like.= " and create_time <= '".strtotime(trim($params['timeEnd']))."'";
                }
                $seachData['timeEnd']=$params['timeEnd'];
            }
            //查询订单sql语句
            $sql = "select * from tbl_order where is_del =0 $like order by create_time desc";
            if(isset($_GET['space'])){
                $sql = "select o.*,osr.space_id,osr.space_name,osr.status as space_status from tbl_order as o "
                        . "left join tbl_order_space_relation as osr on o.id=osr.order_id "
                        . "where o.is_del=0 and o.type not in (". implode(',', Yii::app()->params['allowCinfo']) .")".$like." order by o.create_time desc";
            }
            $data=$this->getIndex($sql);
            $ul=$data['list'];
            $pages=$data['pages'];
            //处理订单数据，获取订单是否被分配数据
            if(!empty($ul)){
                $tempArray = array();//临时存放订单
                foreach ($ul as $order){
                    if(!in_array($order['type'], Yii::app()->params['allowCinfo'])){//渲染订单
                        if($order['type'] == 2){//新空间渲染订单
                            $sql = 'select u.username,ta.* from cms_user as u left join tbl_task_allocation as ta on u.id=ta.receiver '
                                    . 'where u.is_del=0 and ta.obj_id='.$order['id'].' and ta.order_type=2 and ta.allocation_type=0';
                        }else{//渲染订单
                            $sql = 'select u.username,ta.* from cms_user as u left join tbl_task_allocation as ta on u.id=ta.receiver '
                                    . 'where u.is_del=0 and ta.obj_id='.$order['id'].' and ta.space_id='.$order['space_id'].' and ta.order_type=1 and ta.allocation_type=1';
                        }
                        $TaskAllocation = $connection->createCommand($sql)->queryRow();
                        if(!empty($TaskAllocation)){
                            $order['task']['username'] = $TaskAllocation['username'];
                            $order['task']['taskId'] = $TaskAllocation['id'];
                            $order['task']['rid'] = $TaskAllocation['receiver'];
                            $order['task']['status'] = $TaskAllocation['status'];
                            $order['task']['is_check'] = $TaskAllocation['is_check'];
                        }else{
                            $order['task'] = array();
                        }
                        array_push($tempArray, $order);
                    }else{//建模订单
                        $sql = 'select u.username,ta.* from cms_user as u left join tbl_task_allocation as ta on u.id=ta.receiver '
                                . 'where u.is_del=0 and ta.obj_id='.$order['id'].' and ta.order_type in ('. implode(',', Yii::app()->params['allowCinfo']) .') and ta.allocation_type=0';
                        $TaskAllocation = $connection->createCommand($sql)->queryRow();
                        $infoTask = TaskAllocation::model()->findAll('order_type in ('. implode(',', Yii::app()->params['allowCinfo']) .') and allocation_type=1 and obj_id '
                                . 'in (select info_id from tbl_order_info_relation where order_id='.$order['id'].')');
                        if(empty($infoTask)){
                            if(!empty($TaskAllocation)){
                                $order['task']['username'] = $TaskAllocation['username'];
                                $order['task']['taskId'] = $TaskAllocation['id'];
                                $order['task']['rid'] = $TaskAllocation['receiver'];
                                $order['task']['status'] = $TaskAllocation['status'];
                                $order['task']['is_check'] = $TaskAllocation['is_check'];
                            }else{
                                $order['task'] = array();
                            }
                        }else{
                            $order['notask'] = array();//notask表示该建模订单，素材已被分配任务，则订单不可以再作为任务进行分配
                        }
                        array_push($tempArray, $order);
                    }
                }
                if(!empty($tempArray))
                    $ul = $tempArray;
            }
            //获取已添加的品牌馆
            $orderTypeLike = ' and o.type in ('. implode(',', Yii::app()->params['allowCinfo']) .')';
            if(isset($_GET['space']))
                $orderTypeLike = ' and o.type not in ('. implode(',', Yii::app()->params['allowCinfo']) .')';
            $sql = "select b.id,b.name from tbl_brandhall as b 
                left join tbl_order_brandhall_relation as obr on b.id=obr.brandhall_id 
                left join tbl_order as o on o.id=obr.order_id 
                where b.is_del=0 and o.is_del=0 $orderTypeLike group by b.id";
            $brandhalls = $connection->createCommand($sql)->queryAll();
            if(!empty($brandhalls))
                $brandhalls = CHtml::listData($brandhalls,'id','name');
            $this->render('order',array('ul'=>$ul,'pages' => $pages,'seachData'=>$seachData,'taskUser'=>$taskUser,'brandhalls'=>$brandhalls));
        }
        
        /**
		 * 订单详情(建模订单/渲染订单)
		 * @author zhangyong
		 */
		public function actionInfo($oid)
		{
            $like = '';//查询素材条件
            $name=null;
            $params=$_POST;
            $connection=Yii::app()->db;
            $model = Order::model()->findByPk($oid,'is_del=0');
            if(empty($model))
                throw new CHttpException(404,'该订单不存在或者已经删除！.');
            $receiver = Yii::app()->user->getId();//登陆用户的id
            if(!isset($receiver) || empty($receiver))
                throw new CException('未登录...');
            //可指派任务的员工
            $itemname = 'mold';
            if(!in_array($model['type'], Yii::app()->params['allowCinfo']))
                $itemname = 'space';
            $taskUser = User::model()->findAll(array(
                'select'=>'id,username',
                'condition'=>"is_del=0 and id in ( select userid from cms_auth_assignment where itemname='".$itemname."')",
            ));
            if(!empty($taskUser))
                $taskUser = CHtml::listData($taskUser,'id','username');
            //素材检索条件
            if(isset($params['name']) && !empty($params['name'])){
                $like .= " and ( title like '%".trim($params['name'])."%' or item like '%".trim($params['name'])."%' or number like '%".trim($params['name'])."%' )";
                $name=$params['name'];
            }
            //查询订单下素材的条件
            $orderAllocation = TaskAllocation::model()->findAll('obj_id='.$oid.' and order_type in ('. implode(',', Yii::app()->params['allowCinfo']) .') and allocation_type=0 and receiver='.$receiver);
            if(isset($_GET['allocation']) && intval($_GET['allocation'])>0 && empty($orderAllocation) && in_array($model['type'], Yii::app()->params['allowCinfo'])){
                //获取被分配的素材
                $like .= ' and id in (select obj_id from tbl_task_allocation where order_type in ('. implode(',', Yii::app()->params['allowCinfo']) .') and allocation_type=1 and receiver='.$receiver.') '
                        . 'and id in (select info_id from tbl_order_info_relation where order_id='.$oid.')';
            }else{
                //订单下的所有素材
                $like .= ' and id in (select info_id from tbl_order_info_relation where order_id='.$oid.')';
            }
            //查询素材的SQL语句
            $sql = "select * from tbl_info where is_del =0 $like group by id order by create_time desc";
            $data=$this->getIndex($sql);
            $ul=$data['list'];
            $pages=$data['pages'];
            
            //查看订单是否被分配任务
            $taskSpace = ' and ta.allocation_type=0';
            $isTA = array();//记录被分配任务的数据
            //查询被分配渲染订单（type=1）的条件
            if(isset($_GET['sid']))
                $taskSpace =' and ta.allocation_type=1 and ta.space_id='.$_GET['sid'];
            $sql = 'select u.username,ta.* from cms_user as u left join tbl_task_allocation as ta on u.id=ta.receiver '
                    . 'where u.is_del=0 and ta.obj_id='.$model['id'].' and ta.order_type='.$model['type'].$taskSpace;
            $TaskAllocation = $connection->createCommand($sql)->queryRow();
            if(in_array($model['type'], Yii::app()->params['allowCinfo'])){
                $infoTask = TaskAllocation::model()->findAll('order_type='.$model['type'].' and allocation_type=1 and obj_id '
                        . 'in (select info_id from tbl_order_info_relation where order_id='.$model['id'].')');
                if(!empty($infoTask)){
                    $isTA['notask']=array();//notask表示该建模订单，素材已被分配任务，则订单不可以再作为任务进行分配
                }else{
                    if(empty($TaskAllocation)){
                        $isTA['task']=array();
                    }else{
                        $isTA['task']['username'] = $TaskAllocation['username'];
                        $isTA['task']['taskId'] = $TaskAllocation['id'];
                        $isTA['task']['rid'] = $TaskAllocation['receiver'];
                        $isTA['task']['space_id'] = $TaskAllocation['space_id'];
                        $isTA['task']['status'] = $TaskAllocation['status'];
                        $isTA['task']['is_check'] = $TaskAllocation['is_check'];
                    }
                }
            }else{
                if(empty($TaskAllocation)){
                    $isTA['task']=array();
                }else{
                    $isTA['task']['username'] = $TaskAllocation['username'];
                    $isTA['task']['taskId'] = $TaskAllocation['id'];
                    $isTA['task']['rid'] = $TaskAllocation['receiver'];
                    $isTA['task']['space_id'] = $TaskAllocation['space_id'];
                    $isTA['task']['status'] = $TaskAllocation['status'];
                    $isTA['task']['is_check'] = $TaskAllocation['is_check'];
                }
            }
            
            //品牌系列
            $brandhalls = Brand::model()->findAll(array('select'=>'id,name','condition'=>'is_del=0 and is_show=1'));
            $materials = Material::model()->findAll(array('select'=>'id,name','condition'=>''));
            if(!empty($brandhalls))
                $brandhalls=CHtml::listData($brandhalls,'id','name');
            if(!empty($materials))
                $materials=CHtml::listData($materials,'id','name');
            if(!empty($ul)){
                //查询建模订单下的素材被指派的任务类型(模型/贴图/QC)
                if(in_array($model['type'], Yii::app()->params['allowCinfo'])){
                    foreach ($ul as $kn=>$infoT){
                        $aM = '';//为获取接受订单时，接受的素材任务类型
                        if(isset($_GET['allocation']) && intval($_GET['allocation'])>0 && in_array($model['type'], Yii::app()->params['allowCinfo']))
                            $aM =' and ta.receiver='.$receiver;
                        $sql = 'select u.username,ta.* from cms_user as u left join tbl_task_allocation as ta on u.id=ta.receiver '
                                . 'where u.is_del=0 and ta.obj_id='.$infoT['id'].' and ta.order_type in ('. implode(',', Yii::app()->params['allowCinfo']) .') and ta.allocation_type=1'.$aM;
                        $taData = $connection->createCommand($sql)->queryAll();
                        if(!empty($taData)){
                            foreach ($taData as $tad){
                                if($tad['task_type']==1){
                                    $ul[$kn]['task'][1] = $tad;
                                    $ul[$kn]['TT'][] = 1;
                                }elseif ($tad['task_type']==2) {
                                    $ul[$kn]['task'][2] = $tad;
                                    $ul[$kn]['TT'][] = 2;
                                }elseif ($tad['task_type']==3) {
                                    $ul[$kn]['task'][3] = $tad;
                                    $ul[$kn]['TT'][] = 3;
                                }
                            }
                        }else{
                            $ul[$kn]['task'] = array();
                            if(!empty($orderAllocation)){
                                $ul[$kn]['TT'] = array(1,2,3);
                            }else{
                                $ul[$kn]['TT'] = array();
                            }
                        }
                    }
                }
                //查询素材关联的品牌、系列、风格、颜色、材质数据
                $ul = Info::model()->findBSCM($ul,array('brandhalls'=>$brandhalls,'materials'=>$materials));
            }
            //颜色
            $colorsData = new COLORS();
            $colorsSN = array_flip($colorsData->colorsControl());
            $this->render('info',array('model'=>$model,'ul'=>$ul,'pages' => $pages,'name'=>$name,'taskUser'=>$taskUser,'isTA'=>$isTA,'colorsSN'=>$colorsSN,'count'=>$data['count']));
        }
        
        /**
		 * 分配任务
         * $_POST['bindData'] = {"taskType":"","sid":"","allocationType":"","objId":"","rid":""}
         * taskType=>任务类型,sid=>空间id,allocationType=>任务分配类型，objId=>对象id(订单id/素材id),rid=>接受任务的用户ID
		 * @author zhangyong
		 */
		public function actionBindTask()
		{
            $data = array('status'=>false,'info'=>'分配任务失败！','taskId'=>'','rid'=>'','rname'=>'');
            $connection=Yii::app()->db;
            $OType = Yii::app()->params['typeAllow'];
            try {
                if(isset($_POST['bindData'])){
                    $sender = Yii::app()->user->getId();
                    if(!isset($sender) || empty($sender))
                        throw new CException('未登录...');
                    $transaction=$connection->beginTransaction();
                    $bindData = $_POST['bindData'];
                    $model = new TaskAllocation();//实例化任务分配表
                    if($bindData['allocationType']==0){
                        //以订单形式的分配类型
                        $order = Order::model()->findByPk($bindData['objId'],'is_del=0');
                        if(!empty($order)){
                            $model->order_type = $order['type'];
                            $model->allocation_type = 0;
                        }
                    }else{
                        //以单个形式的分配类型
                        if(intval($bindData['sid'])>0){
                            //渲染订单
                            $model->order_type = 1;
                            $model->space_id = $bindData['sid'];
                            $model->allocation_type = 1;
                        }else{
                            //建模订单
                            $model->order_type = 0;
                            $model->allocation_type = 1;
                            $model->task_type = $bindData['taskType'];
                        }
                    }
                    $model->obj_id = $bindData['objId'];
                    $model->sender = $sender;
                    $model->receiver = $bindData['rid'];
                    $model->create_time = time();
                    if($model->save()){
                        $receiver = User::model()->findByPk($model->receiver,'is_del=0');
                        $data = array('status'=>true,'info'=>'分配任务成功！','taskId'=>$model->id,'rid'=>$receiver['id'],'rname'=>$receiver['username'],'type'=>$OType[$model->order_type]);
                    }
                    $transaction->commit();
                }
                echo CJSON::encode($data);
            } catch (Exception $e) {
                $transaction->rollback();
                throw new CHttpException(500,$e->getMessage());//测试时使用
            }
        }
        
        /**
		 * 点击铅笔按钮切换到更改接单人员复选框
		 * @author zhangyong
		 */
		public function actionChangePerson()
		{
            if(isset($_POST['rid']) && isset($_POST['taskId']) && isset($_POST['type'])){
                $rid = $_POST['rid'];//接受任务的用户ID
                $taskId = $_POST['taskId'];//任务分配表主键ID
                $type = $_POST['type'];
//                $TaskAllocation = TaskAllocation::model()->findByPk($taskId);
                //可指派任务的员工
                $taskUser = User::model()->findAll(array(
                    'select'=>'id,username',
                    'condition'=>"is_del=0 and id in ( select userid from cms_auth_assignment where itemname='".$type."')",
                ));
                if(!empty($taskUser))
                    $taskUser = CHtml::listData($taskUser,'id','username');
                $this->renderPartial('selectUser',array('taskUser'=>$taskUser,'rid'=>$rid,'taskId'=>$taskId));
            }
        }
        
        /**
		 * 修改分配任务的人员
		 * @author zhangyong
		 */
		public function actionChangeBindTask()
		{
            $data = array('status'=>false,'info'=>'分配任务失败！','taskId'=>'','rid'=>'','rname'=>'','taskType'=>'');
            $OType = Yii::app()->params['typeAllow'];
            $connection=Yii::app()->db;
            try{
                if(isset($_POST['selId']) && isset($_POST['taskId'])){
                    $transaction=$connection->beginTransaction();
                    $selId = $_POST['selId'];
                    $taskId = $_POST['taskId'];
                    $task = TaskAllocation::model()->findByPk($taskId);
                    if(!empty($task)){
                        $task->receiver = $selId;
                        if($task->save()){
                            $receiver = User::model()->findByPk($task->receiver,'is_del=0');
                            $data = array('status'=>true,'info'=>'分配任务成功！','taskId'=>$task->id,'rid'=>$receiver['id'],
                                'rname'=>$receiver['username'],'type'=>$OType[$task->order_type],'taskType'=>$task->task_type);
                        }
                    }
                    $transaction->commit();
                }
                echo CJSON::encode($data);
            } catch (Exception $ex) {
                $transaction->rollback();
                throw new CHttpException(500,$e->getMessage());//测试时使用
            }
        }
        
        /**
		 * 加载编辑状态内容
         * $params['requestData'] = array("obj_id"=>"","type"=>"","sid"=>"")
         * obj_id 订单id或素材id， type 编辑的类型（订单/素材），sid 空间ID，
		 * @author zhangyong
		 */
		public function actionLoadStatus()
		{
            $params = $_POST;
            $data = array('ta'=>array(),'order'=>array(),'info'=>array(),'sid'=>'');
            $uid = Yii::app()->user->getId();
            if(!isset($uid) || empty($uid))
                throw new CException('未登录...');
            //查找当前登录用户是否有分配权限
            $authTask = AuthAssignment::model()->find("userid=".$uid." and ( itemname='task' or itemname='administrator' )");
            if(isset($params['requestData'])){
                $requestData = $params['requestData'];
                if(!empty($requestData['sid']))
                    $data['sid'] = $requestData['sid'];
                if($requestData['type'] == 'order'){
                    $oid = $requestData['obj_id'];
                    $order = Order::model()->findByPk($oid,'is_del=0');
                    if(!empty($data['sid'])){
                        $sql = 'select o.*,osr.status as space_status from tbl_order as o left join tbl_order_space_relation as osr on o.id=osr.order_id '
                                . 'where o.is_del=0 and osr.space_id='.$data['sid'];
                        $order = Yii::app()->db->createCommand($sql)->queryRow();
                    }
                    if(empty($order)){
                        throw new CHttpException(404,'该订单不存在或者已经删除！.');
                    }else{
                        $data['order'] = $order;
                        if(!empty($authTask)){
                           if($order['type'] != 1){
                                $data['ta'] = TaskAllocation::model()->find('obj_id='.$oid.' and order_type='.$order['type'].' and allocation_type=0');
                            }else{
                                $data['ta'] = TaskAllocation::model()->find('obj_id='.$oid.' and order_type='.$order['type'].' and space_id='.$requestData['sid'].' and allocation_type=1');
                            } 
                        }
                    }
                    
                }else{
                    $info = Info::model()->findByPk($requestData['obj_id'],'is_del=0');
                    if(empty($info)){
                        throw new CHttpException(404,'该素材不存在或者已经删除！.');
                    }else{
                        $data['info'] = $info;
                    }
                }
            }
            $this->renderPartial('loadStatus',$data);
        }
        
        /**
		 * 保存编辑状态
         * 暂时只有分配者有权限编辑状态
         * @todo 被分配者也有编辑状态权限，并且需要有分配权限的人审核
		 * @author zhangyong
		 */
		public function actionSaveEditStatus()
		{
            $params = $_POST;
            $connection=Yii::app()->db;
            $data = array('status'=>false,'info'=>'操作失败！');
            if(!empty($params)){
                try{
                    $transaction=$connection->beginTransaction();
                    //修改任务状态
                    if(isset($params['task_id']) && !empty($params['task_id'])){
                        $task = TaskAllocation::model()->findByPk($params['task_id']);
                        $task->status = $params['task_status'];
                        if($params['task_status'] == 1)
                            $task->is_check = 1;
                        $task->save();
                    }
                    //修改订单状态
                    if(isset($params['order_id']) && !empty($params['order_id'])){
                        $order = Order::model()->findByPk($params['order_id'],'is_del=0');
                        if($order['type'] ==1 && $params['order_status']==3){
                            $order->status = 2;
                        }else{
                            $order->status = $params['order_status'];
                        }
                        if($order->save()){
                            if(in_array($order->type, Yii::app()->params['allowCinfo'])){
                                if($order->status==3){
                                    //修改建模订单订单下的素材状态
                                    $con = ' id in (select info_id from tbl_order_info_relation where order_id='.$order->id.')';
                                    Info::model()->updateAll(array('status'=>2),$con);
                                    //修改建模订单及订单下的素材关联的任务状态
                                    $con = '(obj_id in (select info_id from tbl_order_info_relation where order_id='.$order->id.') and order_type='.$order->type.' and allocation_type=1)'
                                            . ' or (obj_id='.$order->id.' and order_type='.$order->type.' and allocation_type=0)';
                                    TaskAllocation::model()->updateAll(array('status'=>1,'is_check'=>1),$con);
                                }
                            }else{
                                if($order->type==1){
                                    //渲染订单要同步tbl_order_space_relation表
                                    if(isset($params['sid']) && !empty($params['sid'])){
                                        OrderSpaceRelation::model()->updateAll(array('status'=>$params['order_status']),'order_id='.$order->id.' and space_id='.$params['sid']);
                                    }
                                    //判断渲染订单下各空间是否渲染完成，如果全部完成则自动修改订单状态
                                    $osrData = OrderSpaceRelation::model()->find('order_id='.$order->id.' and status!=3');
                                    if(empty($osrData)){
                                        $order->status = 3;
                                        $order->save();
                                    }
                                }
                                //修改该订单任务状态
                                $conAdd = '';
                                if($order->type==1)
                                    $conAdd .= ' and space_id='.$params['sid'];
                                if(isset($params['order_status']) && $params['order_status']==3){
                                    TaskAllocation::model()->updateAll(array('status'=>1,'is_check'=>1),'obj_id='.$order->id.' and order_type='.$order->type.$conAdd);
                                }else{
                                    TaskAllocation::model()->updateAll(array('status'=>0,'is_check'=>0),'obj_id='.$order->id.' and order_type='.$order->type.$conAdd);
                                }
                            }
                        }
                    }
                    //修改素材状态
                    if(isset($params['info_id']) && !empty($params['info_id'])){
                        $info = Info::model()->findByPk($params['info_id'],'is_del=0');
                        $od = Order::model()->find('type=0 and is_del=0 and id in (select order_id from tbl_order_info_relation where info_id='.$params['info_id'].')');
                        if(!empty($info)){
                            $info->status = $params['info_status'];
                            if($info->save()){
                                if($info->status==2){
                                    //修改分配该素材的任务状态
                                    TaskAllocation::model()->updateAll(array('status'=>1,'is_check'=>1),'obj_id='.$params['info_id'].' and order_type='.$od['type'].' and allocation_type=1');
                                    //修改素材状态的时候，检查该素材对应的建模/贴图订单下的所有素材是否全部完成，如果全部完成则自动更改该建模/贴图订单的状态
                                    if(!empty($od)){
                                        $oid = $od['id'];
                                        $InfoArray = Info::model()->findAll('status!=2 and id in (select info_id from tbl_order_info_relation where order_id='.$oid.')');
                                        if(empty($InfoArray)){
                                            $od->status=3;
                                            $od->save();
                                            //如果该订单被分配任务，则修改该订单任务状态
                                            TaskAllocation::model()->updateAll(array('status'=>1,'is_check'=>1),'obj_id='.$oid.' and order_type='.$od['type'].' and allocation_type=0');
                                        }
                                    }

                                }else{
                                    TaskAllocation::model()->updateAll(array('status'=>0,'is_check'=>0),'obj_id='.$params['info_id'].' and order_type='.$od['type'].' and allocation_type=1');
                                    if(!empty($od)){
                                        $oid = $od['id'];
                                        $InfoArray = Info::model()->findAll('status!=2 and id in (select info_id from tbl_order_info_relation where order_id='.$oid.')');
                                        if(!empty($InfoArray)){
                                            $od->status=2;
                                            $od->save();
                                            //如果该订单被分配任务，则修改该订单任务状态
                                            TaskAllocation::model()->updateAll(array('status'=>0,'is_check'=>0),'obj_id='.$oid.' and order_type='.$od['type'].' and allocation_type=0');
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $transaction->commit();
                    $data = array('status'=>true,'info'=>'操作成功！');
                } catch (Exception $ex) {
                    $transaction->rollback();
                    throw new CHttpException(500,$e->getMessage());//测试时使用
                }
            }
            echo CJSON::encode($data);
        }
        
    }