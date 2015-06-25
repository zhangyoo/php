<?php
	/**
	 * 
	 * @author zhangyong
	 * @ps:订单管理  2014/10/08
	 */
	class OrderController extends CmsController
	{
        /**
		 * 订单列表
		 * @author zhangyong
		 */
		public function actionIndex()
		{
            $like = '';//查询订单条件
            $connect=Yii::app()->db;
            $params=$_GET;
            $seachData=array('name'=>'','type'=>'','timeStart'=>'','timeEnd'=>'','status'=>'','infoName'=>'','brandhall'=>'');
            if(isset($params['brandhall']) && !empty($params['brandhall'])){
                $like .= " and id in (select order_id from tbl_order_brandhall_relation where brandhall_id=".$params['brandhall'].")";
                $seachData['brandhall']=$params['brandhall'];
            }
            if(isset($params['name']) && !empty($params['name'])){
                $like .= " and ( title like '%".trim($params['name'])."%' or number like '%".trim($params['name'])."%' )";
                $seachData['name']=$params['name'];
            }
            if(isset($params['infoName']) && !empty($params['infoName'])){
                $orderExist = Order::model()->with('infos')->find("infos.number='".trim($params['infoName'])."' and t.type in (".implode(',', Yii::app()->params['allowCinfo']).")");
                if(empty($orderExist))
                    throw new CHttpException(404,'编号为'.$params['infoName'].'素材不存在或者已经删除！.');
                $this->redirect("/order/info/oid/".$orderExist['id']."/name/".$params['infoName']);
            }
            if(isset($params['type']) && $params['type'] !=''){
                $like .= " and type=".$params['type']."";
                $seachData['type']=$params['type'];
            }
            if(isset($params['status']) && $params['status'] !=''){
                $like .= " and status=".$params['status']."";
                $seachData['status']=$params['status'];
            }
            if(isset($params['timeStart']) && !empty($params['timeStart'])){
                $like.= " and create_time >= '".strtotime(trim($params['timeStart']))."'";
                $seachData['timeStart']=$params['timeStart'];
            }
            if(isset($params['timeEnd']) && !empty($params['timeEnd']))
            {
                $like.= " and create_time <= '".strtotime(trim($params['timeEnd']))."'";
                $seachData['timeEnd']=$params['timeEnd'];
            }
            $sql = "select * from tbl_order where is_del =0 $like order by create_time desc";
            $data=$this->getIndex($sql);
            $ul=$data['list'];
            $pages=$data['pages'];
            //获取已添加的品牌馆
            $sql = "select b.id,b.name from tbl_brandhall as b 
                left join tbl_order_brandhall_relation as obr on b.id=obr.brandhall_id 
                left join tbl_order as o on o.id=obr.order_id 
                where b.is_del=0 and o.is_del=0 group by b.id";
            $brandhalls = $connect->createCommand($sql)->queryAll();
            if(!empty($brandhalls))
                $brandhalls = CHtml::listData($brandhalls,'id','name');
            $this->render('index',array('ul'=>$ul,'pages' => $pages,'seachData'=>$seachData,'brandhalls'=>$brandhalls));
        }
        
        /**
		 * 创建订单
         * @PS:编辑订单的时候，订单的类型不可更改
		 * @author zhangyong
		 */
		public function actionCreate()
		{
            $params = $_POST;
            $connection=Yii::app()->db;
            $model = new Order();
            $default = array('ob'=>array(),'albums'=>array(),'spaces'=>array(),'room_category'=>'');
            if(isset($_GET['id']) && !empty($_GET['id'])){
                $order_id = $_GET['id'];
                $model = $this->loadModel($order_id);
                $type = $model['type'];
                if($type == 2)
                    $default['room_category'] = $model['room_category'];
                //新空间渲染订单已关联的品牌馆数据
                $orderRelated = Order::model()->with('brandhalls','albums','spaces')->findByPk($order_id);
                if(!empty($orderRelated['brandhalls'])){
                    foreach ($orderRelated['brandhalls'] as $b){
                        array_push($default['ob'], $b['id']);
                    }
                }
                //订单关联的空间参考图数据
                if($model['type'] == 2){
                    if(!empty($orderRelated['albums']))
                        $default['albums'] = $orderRelated['albums'];
                }
                //渲染订单关联的空间数据
                if(!empty($orderRelated['spaces']))
                    $default['spaces'] = $orderRelated['spaces'];
            }
            //初始化品牌馆数据
            $brandhalls = Brandhall::model()->findAll(
                        array(
                            'select'=>'id,name',
                            'condition'=>'is_del=0 and is_show=1',
                            'order'=>'create_time asc'
                        )
                    );
            if(!empty($brandhalls))
                $brandhalls = CHtml::listData($brandhalls,'id','name');
            //处理提交的订单数据
            try {
                if(isset($params['Order'])){
                    $transaction=$connection->beginTransaction();
                    $order = $params['Order'];
                    $model->attributes=$order;
                    if(isset($order['end_time']) && !empty($order['end_time']))
                        $model->end_time = strtotime($order['end_time']);
                    if(isset($_GET['id']) && !empty($_GET['id'])){
                        $model->type = $type;
                    }
                    if(!isset($_GET['id']))
                        $model->isNewRecord = true;
                    if($model->save()){
                        //保存订单编号
                        if(!isset($_GET['id'])){
                            $model->number = date("Ymd").'T'.$model->type.'N'.$model->id;
                            $model->save();
                        }
                        //添加订单空间关联数据
                        if(isset($params['space'])){
                            $spaces = array_unique($params['space']);
                            //判断绑定的空间是否有来自不同功能空间的
                            $space_rcs = Space::model()->findAll(
                                   array(
                                       'select'=>'room_category',
                                       'condition'=>'is_del=0 and id in('. implode(',', $spaces) .')',
                                       'group'=>'room_category',
                                   ) 
                            );
                            if(count($space_rcs)>1)
                                throw new CHttpException(500,'单个渲染订单绑定的空间只能来自同一种功能空间！');
                            OrderSpaceRelation::model()->deleteAll('order_id='.$model->id);
                            foreach ($spaces as $sp){
                                $spData = Space::model()->find(array('select'=>'name','condition'=>'is_del=0 and id='.$sp));
                                if(!empty($spData)){
                                    $orderSpaceRelation = new OrderSpaceRelation();
                                    $orderSpaceRelation->order_id = $model->id;
                                    $orderSpaceRelation->space_id = $sp;
                                    $orderSpaceRelation->space_name = $spData['name'];
                                    $orderSpaceRelation->save();
                                }
                            }
                            $space = Space::model()->findByPk($spaces[0]);
                            $model->room_category = $space['room_category'];
                            $model->save();
                        }
                        //添加品牌馆订单关联数据
                        if(isset($params['brandhall']) && !empty($params['brandhall'])){
                            OrderBrandhallRelation::model()->deleteAll('order_id='.$model->id);
                            foreach ($params['brandhall'] as $bd){
                                $orderBrandhall = new OrderBrandhallRelation();
                                $orderBrandhall->order_id = $model->id;
                                $orderBrandhall->brandhall_id = $bd;
                                $orderBrandhall->save();
                            }
                        }
                        //添加新空间渲染订单的空间参考图
                        $imgArray = array();
                        $imgArray['order_id'] = $model->id;
                        if(isset($params['image']) && !empty($params['image'])){
                            foreach ($params['image'] as $k=>$img){
                                $desc = '';
                                if(isset($params["summary"][$k])){
                                    $desc = $params["summary"][$k];
                                }
                                $imgArray['img_path'] = $img;
                                if(isset($params['ftId']) && intval($params['ftId'][$k])>0){
                                    //修改旧的空间参考图（删除之后的旧数据）
                                    $album = Album::model()->find("image='".$img."' and obj_id=".$model->id." and type=1");
                                    $album->summary=$params["summary"][$k];
                                    $album->save();
                                }else{
                                    if(!$this->InsertImg($imgArray,$desc))
                                            continue;
                                }
                            }
                        }
                        $transaction->commit();
                        if(in_array($model->type, array(0,3))){
                            $this->redirect('/order/info/oid/'.$model->id);//建模订单跳转到添加素材界面
                        }else{
                            $this->redirect('/order/info/oid/'.$model->id.'/bind');//渲染订单跳转到绑定素材界面
                        }
                        
                    }
                }
            } catch (Exception $e) {
                $transaction->rollback();
                throw new CHttpException(500,$e->getMessage());//测试时使用
            }

            
            $this->render('create',array('model'=>$model,'brandhalls'=>$brandhalls,'default'=>$default));
        }
        
        /**
		 * 切换订单类型
		 * @author zhangyong
		 */
		public function actionChangeOType()
		{
            if(isset($_POST['type'])){
                $default = array('ob'=>array(),'albums'=>array(),'spaces'=>array(),'room_category'=>'');//配合编辑订单时使用共同模板
                $type = $_POST['type'];
                $brandhalls = Brandhall::model()->findAll(
                            array(
                                'select'=>'id,name',
                                'condition'=>'is_del=0 and is_show=1',
                                'order'=>'create_time asc'
                            )
                        );
                if(!empty($brandhalls))
                    $brandhalls=CHtml::listData($brandhalls,'id','name');
                $this->renderPartial('changeType',array('brandhalls'=>$brandhalls,'type'=>$type,'default'=>$default));
            }
        }
        
        /**
		 * 搜索空间
         * 只能搜索某个功能空间下的空间
		 * @author zhangyong
		 */
		public function actionSearchSpace()
		{
            $con = '';
            if(isset($_POST['rc']) && isset($_POST['name'])){
                $room_category = $_POST['rc'];
                $con .=' and room_category='.$room_category;
                $name = trim($_POST['name']);
                if(!empty($name)){
                    $name = explode('*', $name);
                    $con .=" and length='".$name[0]."' and width='".$name[1]."'";
                }
                //获取空间数据
                $spaceData=  Space::model()->findAll(array('select'=>'id,name,image,length,width',
                    'condition'=>'is_del=0 and is_show=1'.$con,
                    'order'=>'create_time desc',
                    ));
                $this->renderPartial('spaceTemp',array('spaceData'=>$spaceData));    
            }
        }
        
        /**
		 * 临时记录绑定的空间数据
         * 作用是把绑定的空间的id返回到要创建/编辑的订单页面
		 * @author zhangyong
		 */
		public function actionSaveSpace()
		{
            $psData=array();
            $oldArr = array();
            if(isset($_POST['temp']) && !empty($_POST['temp'])){
                if(isset($_POST['oldArr']) && !empty($_POST['oldArr']))
                    $oldArr = $_POST['oldArr'];
                foreach ($_POST['temp'] as $model){
                    if(!in_array($model, $oldArr)){
                        $sql='select id,name,image,length,width from tbl_space where id='.$model;
                        $ps=Yii::app()->db->createCommand($sql)->queryRow();
                        array_push($psData,$ps);
                    }
                }
            }
            
            $this->renderPartial('addS',array('psData'=>$psData));
        }
        
        /**
		 * 删除临时相册图片
		 * @author zhangyong
		 */
        public function actionDelTempImage()
        {
            $arr  = array();
            $bool = FALSE;
            if(isset($_POST) && !empty($_POST['tempUrl'])){
                $path = dirname(Yii::app()->BasePath).YII::app()->request->baseUrl.'/common/kindeditor';
                 $arr = explode('..', $_POST['tempUrl']);
                 $imgs = $path.$arr[1];//参数
                 if(unlink($imgs)){
                     $bool = true;
                 }
            }
            echo $bool;
        }
        
        /*
         * 批量插入相册图片
         * @author zhangyong
         */
        public function InsertImg($imgArray,$desc=''){
            $arr = array();
            $bool = FALSE; 
            $path = dirname(Yii::app()->BasePath).YII::app()->request->baseUrl.'/common/kindeditor';
            $date=date('Y/m/d');
            $tarpath = Yii::app()->params['realPathOfStatic'].'/upload/cmsAlbum/'.$date.'/';
            if(!is_dir($tarpath))
                mkdir($tarpath,0777,true);
            if($imgArray['order_id'])
            {
                $model = new Album();
                $model->obj_id = $imgArray['order_id'];
                if(!empty($desc))
                    $model->summary = $desc;
                //复制图片文件
                $arr = explode('..', $imgArray['img_path']);
                $imgs = $path.$arr[1];//参数
                $filename = basename($imgArray['img_path']);
                if(copy($imgs, $tarpath.$filename)){
                    $image = '/upload/cmsAlbum/'.$date.'/'.$filename;
                    $model->image = $image;
                    $model->type = 1;

                    //插入图片表....
                    if($model->save()){
                        unlink($imgs);//是否删除临时图片
                        unset($model->obj_id);
                        $bool = true;
                    }

                }

            }
            return $bool;
        }
        
        /**
		 * 删除订单
         * @PS：如果删除的是建模订单，则删除该订单、该订单下的的所有素材(以及与这些素材关联的tbl_order_info_relation数据)及该订单与素材的所有关联数据，
         *      如果是渲染订单，需要删除该订单及该订单与素材所有关联数据即可
		 * @author zhangyong
		 */
		public function actionDel()
		{
            $data = array('status'=>false,'info'=>'删除失败！');
            $connection = Yii::app()->db;
            if(isset($_POST['id']) && intval($_POST['id'])>0){
                $id=$_POST['id'];
                $order = $this->loadModel($id);
                if(!empty($order)){
                    $type = $order['type'];
                    if(in_array($type,Yii::app()->params['allowCinfo'])){
                        //删除建模订单，贴图订单
                        $infoIds = OrderInfoRelation::model()->findAll(array(
                            'select'=>'info_id',
                            'condition'=>'order_id='.$id,
                        ));
                        if(!empty($infoIds)){
                            $info_id = array();
                            foreach ($infoIds as $io){
                                if(!in_array($io['info_id'], $info_id))
                                    array_push ($info_id, $io['info_id']);
                            }
                            if(!empty($info_id)){
                                Info::model()->updateAll(array('is_del'=>'1'),'id in ('.implode(',', $info_id).')');
                                OrderInfoRelation::model()->deleteAll('info_id in ('.implode(',', $info_id).')');
                            }
                        }
                    }else{
                        //删除渲染订单
                        OrderInfoRelation::model()->deleteAll('order_id='.$id);
                    }
                    $order->is_del=1;
                    if($order->save())
                        $data = array('status'=>true,'info'=>'删除成功！');
                }
            }
            echo CJSON::encode($data);
        }
        
        /**
		 * 删除空间参考图
		 * @author zhangyong
		 */
        public function actionDelSImg()
        {
            $data = array('status'=>false,'info'=>'删除失败！');
            if(isset($_POST['aid'])){
                Album::model()->deleteByPk($_POST['aid']);
                $data = array('status'=>true,'info'=>'删除成功！');
            }
            echo CJSON::encode($data);
        }
        
        /**
		 * 订单的素材列表，绑定/解绑素材
		 * @author zhangyong
		 */
		public function actionInfo($oid)
		{
            $like = '';//查询素材条件
            $bind = '';
            $name=null;
            $params=$_GET;
            $connection=Yii::app()->db;
            $model = $this->loadModel($oid);
            if(isset($params['name']) && !empty($params['name'])){
                $like .= " and ( title like '%".trim($params['name'])."%' or item like '%".trim($params['name'])."%' or number like '%".trim($params['name'])."%' )";
                $name=$params['name'];
            }
            //处理素材信息
            $infoData = array();
            if(isset($params['Info']))
                $infoData = $params['Info'];
            if(isset($_GET['bind'])){
                $bind = '/bind';
                //未绑素材数据
                $like .= ' and id not in (select info_id from tbl_order_info_relation where order_id='.$oid.')';
                //绑定素材
                if(!empty($infoData)){
                    foreach ($infoData as $in){
                        $orderInfo = new OrderInfoRelation();
                        $orderInfo->order_id = $oid;
                        $orderInfo->info_id = $in;
                        $orderInfo->save();
                    }
                    $this->redirect('/order/info/oid/'.$oid.'/bind');
                }
            }elseif(isset($_GET['unbind'])){
                $bind = '/unbind';
                //已绑素材数据
                $like .= ' and id in (select info_id from tbl_order_info_relation where order_id='.$oid.')';
                //解绑素材
                if(!empty($infoData)){
                    OrderInfoRelation::model()->deleteAll('order_id='.$oid.' and info_id in ('.implode(',', $infoData).')');
                    $this->redirect('/order/info/oid/'.$oid.'/bind');
                }
            }
            
            if(!isset($_GET['bind']) && !isset($_GET['unbind'])){
                //建模订单下的素材列表
                $like .= " and id in (select info_id from tbl_order_info_relation where order_id=".$oid.")";
            }else{
                //附加条件，检索该订单相关的素材数据
                $like .= ' and ( brandhall_id in (select brandhall_id from tbl_order_brandhall_relation where order_id='.$oid.')'
                        . ' or id in (select info_id from tbl_info_room_category where room_category='.$model['room_category'].') )';
            }
            
            $sql = "select * from tbl_info where is_del =0 $like group by id order by create_time desc";
            $data=$this->getIndex($sql);
            $ul=$data['list'];
            $pages=$data['pages'];
            //品牌系列
            $brandhalls = Brand::model()->findAll(array('select'=>'id,name','condition'=>'is_del=0 and is_show=1'));
            $materials = Material::model()->findAll(array('select'=>'id,name','condition'=>''));
            if(!empty($brandhalls))
                $brandhalls=CHtml::listData($brandhalls,'id','name');
            if(!empty($materials))
                $materials=CHtml::listData($materials,'id','name');
            if(!empty($ul)){
                $ul = Info::model()->findBSCM($ul,array('brandhalls'=>$brandhalls,'materials'=>$materials));
            }
            //颜色
            $colorsData = new COLORS();
            $colorsSN = array_flip($colorsData->colorsControl());
            $this->render('info',array('model'=>$model,'ul'=>$ul,'pages' => $pages,'name'=>$name,'bind'=>$bind,'colorsSN'=>$colorsSN,'count'=>$data['count']));
        }
        
        public function loadModel($id)
        {
            $model = Order::model()->findByPk($id,'is_del=0');
            if(null===$model)
                throw new CHttpException(404,'订单'.$id.'不存在或者已经删除！.');
            return $model;
        }
    }

