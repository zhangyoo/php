<?php
	/**
	 * 
	 * @author zhangyong
	 * @ps:素材管理  2014/10/11
	 */
	class InfoController extends CmsController
	{
        /**
		 * 创建素材
         * @PS:素材只能在建模订单下创建，渲染订单下只是绑定素材
		 * @author zhangyong
		 */
		public function actionCreate($oid)
		{
            $order = $this->loadOrderModel($oid);
            $model = new Info();
            $defaultData=$this->getDefault();
            $params = $_POST;
            $connection=Yii::app()->db;
            //获取标签数据
            $label = Label::model()->findAll('parent_id=0 and is_del=0');
            if(!empty($label))
                $label = CHtml::listData($label,'id','name');
            try{
                if(isset($params['Info'])){
                    $transaction=$connection->beginTransaction();
                    $info = $params['Info'];
                    $model->attributes=$info;
                    $fileHelper=new FileHelper;
                    $fileHelper->subFolder='info';
                    $image = $fileHelper->saveFile($model,'image',array('upyun'=>Yii::app()->params['upYun']));
                    $model->image = $image;
                    if(!empty($info['label_id'][1])){
                        $model->label_id = $info['label_id'][1];
                    }else{
                        $model->label_id = $info['label_id'][0];
                    }
                    //品牌馆
                    if(isset($info['brandhall_id']) && intval($info['brandhall_id'])>0){
                        $model->brandhall_id=$info['brandhall_id'];
                    }
                    //品牌系列
                    if(isset($info['brand_id'])){
                        if(intval($info['brand_id'][1])>0){
                            $model->brand_id=$info['brand_id'][1];
                        }elseif(intval($info['brand_id'][1])<=0 && intval($info['brand_id'][0])>0){
                            $model->brand_id=$info['brand_id'][0];
                        }else{
                            $model->brand_id=null;
                        }
                    }
                    $model->isNewRecord = TRUE;
                    if($model->save()){
                        //存储素材的编号
                        $model->number = date("Ymd").'N'.$model->id;
                        $model->save();
                        //存储功能空间
                        if(isset($params['room_category'])){
                            foreach ($params['room_category'] as $rc){
                                $infoRoomCategory = new InfoRoomCategory();
                                $infoRoomCategory->info_id = $model->id;
                                $infoRoomCategory->room_category = $rc;
                                $infoRoomCategory->save();
                            }
                        }
                        //保存素材材质
                        if(isset($params['material_id'])){
                            $material_id=null;
                            if(intval($params['material_id'][1])>0){
                                $material_id=$params['material_id'][1];
                            }elseif(intval($params['material_id'][1])<=0 && intval($params['material_id'][0])>0){
                                $material_id=$params['material_id'][0];
                            }
                            if(!empty($material_id) && intval($material_id)>0){
                                $InfoMaterialRelation = new InfoMaterialRelation();
                                $InfoMaterialRelation->info_id = $model->id;
                                $InfoMaterialRelation->material_id = $material_id;
                                $InfoMaterialRelation->save();
                            }
                        }
                        //保存素材风格
                        if(isset($params['style'])){
                            foreach ($params['style'] as $style){
                                $InfoStyleRelation = new InfoStyleRelation();
                                $InfoStyleRelation->info_id = $model->id;
                                $InfoStyleRelation->style_id = $style;
                                $InfoStyleRelation->save();
                            } 
                        }
                        //保存素材颜色
                        if(isset($params['color'])){
                            foreach ($params['color'] as $ck=>$color){
                                $colArr = explode('|', $color);
                                $ck = $ck + 1 ;
                                $InfoColorRelation = new InfoColorRelation();
                                $InfoColorRelation->info_id = $model->id;
                                $InfoColorRelation->color_name = $colArr[0];
                                $InfoColorRelation->color_value = $colArr[1];
                                $InfoColorRelation->color_sort = $ck;
                                $InfoColorRelation->save();
                            } 
                        }
                        //关联素材订单
                        if(!empty($oid)){
                            $OrderInfoRelation = new OrderInfoRelation();
                            $OrderInfoRelation->order_id = $oid;
                            $OrderInfoRelation->info_id = $model->id;
                            $OrderInfoRelation->save();
                        }
                        //添加360度图片
                        $imgArray = array();
                        $imgArray['info_id'] = $model->id;
                        if(isset($params['image']) && !empty($params['image'])){
                            foreach ($params['image'] as $k=>$img){
                                $desc = '';
                                if(isset($params["summary"][$k])){
                                    $desc = $params["summary"][$k];
                                }
                                $imgArray['img_path'] = $img;
                                if(!$this->InsertImg($imgArray,$desc))
                                        continue;
                            }
                        }
                        //统计360度图片的张数
                        $albumData = Album::model()->count('type=2 and obj_id='.$model->id);
                        if(intval($albumData)>0){
                            $imgCon = json_decode($model->img_condition,true);
                            if(empty($imgCon) || empty($model->img_condition))
                                $imgCon = array();
                            $imgCon[5] = intval($albumData);
                            $model->img_condition = json_encode($imgCon);
                            $model->save();
                        }
                        
                        $transaction->commit();
                        
                        $this->redirect('/order/info/oid/'.$oid); 
                    }
                }
            }catch (Exception $e) {
                $transaction->rollback();
                throw new CHttpException(500,$e->getMessage());//测试时使用
            }
            $this->render('create',array('model'=>$model,'defaultData'=>$defaultData,'order'=>$order,'label'=>$label));
        }
        
        /**
		 * 编辑素材
		 * @author zhangyong
		 */
		public function actionUpdate($id)
		{
            $criteria=new CDbCriteria();
            $criteria->with=array(
                'label'=>array(
                    'on'=>'',
                ),
                'orders'=>array(
                    'on'=>'orders.type in ('. implode(',', Yii::app()->params['allowCinfo']) .')',
                ),
            );
            $criteria->condition='t.is_del=0';
            $model = Info::model()->findByPk($id,$criteria);
            if(empty($model)) 
                throw new CHttpException(404,'该素材不存在或者已经删除！.');
            if(isset($_GET['oid']) && !empty($_GET['oid']))
                $orderTemp = $this->loadOrderModel($_GET['oid']);
            
            $defaultData = $this->getDefault();//初始数据
            $connection=Yii::app()->db;
            $params = $_POST;
            $imgSrc = $model['image'];
            $labels = array('parent'=>array(),'child'=>array(),'pid'=>'','cid'=>'');
            $labels['parent'] = Label::model()->findAll('parent_id=0 and is_del=0');
            if(!empty($labels['parent']))
                $labels['parent'] = CHtml::listData($labels['parent'],'id','name');
            //已选标签分类数据
            if(!empty($model['label_id'])){
                $labelType = Label::model()->findByPk($model['label_id'],'is_del=0');
                if(!empty($labelType)){
                    if(!empty($labelType['parent_id'])){
                        //该物品属于二级的分类下
                        $labels['pid'] = $labelType['parent_id'];//物品的一级分类id
                        $childLabel = Label::model()->findAll('is_del=0 and parent_id='.$labelType['parent_id']);
                        if(!empty($childLabel))
                            $labels['child'] = CHtml::listData($childLabel,'id','name');
                        $labels['cid'] = $model['label_id'];//物品的二级分类id
                    }else{
                        //该物品属于一级分类下
                        $labels['pid'] = $model['label_id'];//物品的一级分类id
                        $labels['cid'] = '';//物品的二级分类id
                    }
                }
            }
            //已添加颜色
            $infoColor = array();
            $infoColorData = InfoColorRelation::model()->findAll('info_id='.$id.' order by color_sort asc');
            if(!empty($infoColorData)){
                foreach ($infoColorData as $kic=>$ic){
                    if(!in_array(implode('|', array($ic['color_name'],$ic['color_value'])), $infoColor)){
                        array_push($infoColor, implode('|', array($ic['color_name'],$ic['color_value'])));
                    }
                }
            }
            //已添加的分类
            $selCat=array('selectCat'=>array(),'top_id'=>null,'second_id'=>null);
            if(intval($model['category_id'])>0)
                $selCat=$this->selCat($model['category_id']);
            
            //已选品牌
            $Bsel=array('second'=>array(),'pid'=>null,'secid'=>null);
            if(!empty($model['brand_id']))
                $Bsel=$this->BMsel($model['brand_id'],array('model'=>'Brand'));
            if(empty($model['brandhall_id'])){
                $BPid=null;
                $brands['top']=array();
            }elseif(intval($model['brandhall_id'])>0){
                $brandTops=  Brand::model()->findAll(array('select'=>'id,name','condition'=>'(parent_id=0 or parent_id is null) and brandhall_id='.$model['brandhall_id']));
                $brands['top']=CHtml::listData($brandTops,'id','name');
            }
            
            //已选材质
            $selMid= InfoMaterialRelation::model()->find('info_id='.$id);
            $Msel=array('second'=>array(),'pid'=>'','secid'=>'');
            if(!empty($selMid['material_id']))
                $Msel=$this->BMsel($selMid['material_id'],array('model'=>'Material'));
            
            //已选功能空间
            $room_categorys = array();
            $rcData= InfoRoomCategory::model()->findAll('info_id='.$id);
            if(!empty($rcData)){
                foreach ($rcData as $rc){
                    if(!in_array($rc['room_category'], $room_categorys))
                        array_push ($room_categorys, $rc['room_category']);
                }
            }
            
            //已选中的风格
            $selStyle=array();
            $selStyleData= InfoStyleRelation::model()->findAll('info_id='.$id);
            if(!empty($selStyleData)){
                foreach ($selStyleData as $ssa){
                    array_push($selStyle, $ssa['style_id']);
                }
            }
            
            //已上传的360度图片
            $albums = array();
            if($model['is_rotation'] = 1){
                $albums = Album::model()->findAll(array(
                    'select'=>'*',
                    'condition'=>'type=2 and obj_id='.$model['id'],
                    'order'=>'sort_num asc',
                ));
            }
            //记录已选信息
            $selData = array('labels'=>$labels,'infoColor'=>$infoColor,'selCat'=>$selCat,'Bsel'=>$Bsel,'albums'=>$albums,
                'Msel'=>$Msel,'selStyle'=>$selStyle,'room_categorys'=>$room_categorys);
            try{
                if(isset($params['Info'])){
                    $transaction=$connection->beginTransaction();
                    $info = $params['Info'];
                    $model->attributes=$info;
                    $fileHelper=new FileHelper;
                    $fileHelper->subFolder='info';
                    if($fileHelper->hasUploadFile($model,'image')){
                        $image = $fileHelper->saveFile($model,'image',array('upyun'=>Yii::app()->params['upYun']));
                        $model->image = $image;
                    }else{
                        $model->image = $imgSrc;
                    }
                    //品牌馆
                    if(isset($info['brandhall_id']) && intval($info['brandhall_id'])>0){
                        $model->brandhall_id=$info['brandhall_id'];
                    }
                    //品牌系列
                    if(isset($info['brand_id'])){
                        if(intval($info['brand_id'][1])>0){
                            $model->brand_id=$info['brand_id'][1];
                        }elseif(intval($info['brand_id'][1])==0 && intval($info['brand_id'][0])>0){
                            $model->brand_id=$info['brand_id'][0];
                        }elseif(intval($info['brand_id'][1])==0 && intval($info['brand_id'][0])==0){
                            $model->brand_id=null;
                        }
                    }
                    if(!empty($info['label_id'][1])){
                        $model->label_id = $info['label_id'][1];
                    }else{
                        $model->label_id = $info['label_id'][0];
                    }
                    if($model->save()){
                        //存储功能空间
                        if(isset($params['room_category'])){
                            InfoRoomCategory::model()->deleteAll('info_id='.$model->id);
                            foreach ($params['room_category'] as $rc){
                                $infoRoomCategory = new InfoRoomCategory();
                                $infoRoomCategory->info_id = $model->id;
                                $infoRoomCategory->room_category = $rc;
                                $infoRoomCategory->save();
                            }
                        }
                        //保存素材材质
                        if(isset($params['material_id'])){
                            $material_id=null;
                            if(intval($params['material_id'][1])>0){
                                $material_id=$params['material_id'][1];
                            }elseif(intval($params['material_id'][1])<=0 && intval($params['material_id'][0])>0){
                                $material_id=$params['material_id'][0];
                            }
                            if(!empty($material_id) && intval($material_id)>0){
                                InfoMaterialRelation::model()->deleteAll('info_id='.$model->id);
                                $InfoMaterialRelation = new InfoMaterialRelation();
                                $InfoMaterialRelation->info_id = $model->id;
                                $InfoMaterialRelation->material_id = $material_id;
                                $InfoMaterialRelation->save();
                            }
                        }
                        //保存素材风格
                        if(isset($params['style'])){
                            InfoStyleRelation::model()->deleteAll('info_id='.$model->id);
                            foreach ($params['style'] as $style){
                                $InfoStyleRelation = new InfoStyleRelation();
                                $InfoStyleRelation->info_id = $model->id;
                                $InfoStyleRelation->style_id = $style;
                                $InfoStyleRelation->save();
                            } 
                        }
                        //保存素材颜色
                        if(isset($params['color'])){
                            InfoColorRelation::model()->deleteAll('info_id='.$model->id);
                            foreach ($params['color'] as $ck=>$color){
                                $colArr = explode('|', $color);
                                $ck = $ck + 1 ;
                                $InfoColorRelation = new InfoColorRelation();
                                $InfoColorRelation->info_id = $model->id;
                                $InfoColorRelation->color_name = $colArr[0];
                                $InfoColorRelation->color_value = $colArr[1];
                                $InfoColorRelation->color_sort = $ck;
                                $InfoColorRelation->save();
                            } 
                        }
                        
                        //修改360度图片
                        $imgArray = array();
                        $imgArray['info_id'] = $model->id;
                        if($model->is_rotation == 0)
                            Album::model()->deleteAll("obj_id=".$model->id." and type=2");
                        if(isset($params['image']) && !empty($params['image'])){
                            foreach ($params['image'] as $k=>$img){
                                $desc = '';
                                if(isset($params["summary"][$k])){
                                    $desc = $params["summary"][$k];
                                }
                                $imgArray['img_path'] = $img;
                                if(isset($params['ftId']) && intval($params['ftId'][$k])>0){
                                    //修改旧的空间参考图（删除之后的旧数据）
                                    $album = Album::model()->find("image='".$img."' and obj_id=".$model->id." and type=2");
                                    $album->sort_num=$params["summary"][$k];
                                    $album->save();
                                }else{
                                    if(!$this->InsertImg($imgArray,$desc))
                                            continue;
                                }
                            }
                        }
                        
                        //统计360度图片的张数
                        $albumData = Album::model()->count('type=2 and obj_id='.$model->id);
                        if(intval($albumData)>0){
                            $imgCon = json_decode($model->img_condition,true);
                            if(empty($imgCon) || empty($model->img_condition))
                                $imgCon = array();
                            $imgCon[5] = intval($albumData);
                            $model->img_condition = json_encode($imgCon);
                            $model->save();
                        }
                        
                        //修改素材的时候修改与素材相关的模型及元素数据
                        Info::model()->_updateME($model->id);
                        
                        $transaction->commit();
                        $fromUrl = $_SERVER['REQUEST_URI'];
                        $fromUrlArr = explode('/', $fromUrl);
                        for($i=0;$i<5;$i++){
                            unset($fromUrlArr[$i]);
                        }
                        $toUrl = "/order/info/".implode('/', $fromUrlArr);
                        $this->redirect($toUrl); 
                    }
                }
            }catch (Exception $e) {
                $transaction->rollback();
                throw new CHttpException(500,$e->getMessage());//测试时使用
            }
            
            $this->render('update',array('model'=>$model,'defaultData'=>$defaultData,'selData'=>$selData));
        }
        
        /**
		 * 删除素材
         * @PS：如果删除的是建模订单下的素材则删除该素材及订单素材关联数据
         *      如果删除的是渲染订单下的素材，则删除订单素材关联数据即可
		 * @author zhangyong
		 */
		public function actionDel()
		{
            $data = array('status'=>false,'info'=>'删除失败！');
            $connection=Yii::app()->db;
            if(isset($_POST['oid']) && !empty($_POST['oid']) && isset($_POST['id']) && !empty($_POST['id'])){
                $oid = $_POST['oid'];
                $id = $_POST['id'];
                $order = Order::model()->findByPk($oid,'is_del=0');
                $info = Info::model()->findByPk($id,'is_del=0');
                if(!empty($order) && !empty($info)){
                    $type = $order['type'];
                    if($type ==0 ){
                        $info->is_del = 1;
                        $info->save();
                        $sql = 'delete from tbl_order_info_relation where info_id='.$id;
                    }else{
                        $sql = 'delete from tbl_order_info_relation where info_id='.$id.' and order_id='.$oid;
                    }
                    $connection->createCommand($sql)->execute();
                    //删除素材的贴图
                    $texture_id = json_decode($info->texture_id,true);
                    if(!empty($info->texture_id) && !empty($texture_id))
                        Texture::model ()->deleteAll('id in ('. implode(',',array_keys($texture_id)) .')');
                    $info->texture_id = '';
                    $info->save();
                    $data = array('status'=>true,'info'=>'删除成功！');
                }
            }
            echo CJSON::encode($data);
        }
        
        /**
		 * 是否360度，图片集
		 * @author zhangyong
		 */
		public function actionChangeOType()
		{
            if(isset($_POST['type'])){
                $default = array('albums'=>array());
                $type = $_POST['type'];
                if(isset($_POST['infoId']) && intval($_POST['infoId'])>0){
                    $default['albums'] = Album::model()->findAll(array(
                        'select'=>'*',
                        'condition'=>'type=2 and obj_id='.$_POST['infoId'],
                        'order'=>'sort_num asc',
                    ));
                }
                $this->renderPartial('changeType',array('type'=>$type,'default'=>$default));
            }
        }
        
        /*
         * 批量插入360度图片
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
            if($imgArray['info_id'])
            {
                $model = new Album();
                $model->obj_id = $imgArray['info_id'];
                if(!empty($desc))
                    $model->sort_num = $desc;
                //复制图片文件
                $arr = explode('..', $imgArray['img_path']);
                $imgs = $path.$arr[1];//参数
                $filename = basename($imgArray['img_path']);
                if(copy($imgs, $tarpath.$filename)){
                    $image = '/upload/cmsAlbum/'.$date.'/'.$filename;
                    $model->image = $image;
                    $model->type = 2;

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
		 * 展示360度图片
		 * @author zhangyong
		 */
		public function actionDisPics()
		{
            $params = $_POST;
            $albums = array();
            if(isset($params['id']) && !empty($params['id'])){
                $id = $params['id'];
                $albums = Album::model()->findAll(array(
                    'select'=>'*',
                    'condition'=>"type=2 and obj_id=".$id,
                    'order'=>'sort_num asc'
                ));
            }
            $this->renderPartial('addAlbums',array('albums'=>$albums));
        }
        
        /**
		 * 修改物品贴图
		 * @author zhangyong
		 */
		public function actionTexture($id)
		{
            $model = $this->loadModel($id);
            $record = Mold::model()->_updateTemp($model,array('model'=>'Info'));
            $texture_id = json_decode($model['texture_id'],true);
            if(!empty($model['texture_id']) && !empty($texture_id)){
                $tids = array();
                foreach ($texture_id as $kt=>$vt){
                    $tex = Texture::model()->findByPk($kt);
                    if(empty($tex['name'])){
                        array_push ($tids, $tex['id']);
                        $record['lwh']['length'] = $tex['length'];
                        $record['lwh']['width'] = $tex['width'];
                        $record['lwh']['height'] = $tex['height'];
                    }
                }
                if(!empty($tids)){
                    $recordTex = Mold::model()->_getTexture($tids);
                    $record['textures'] = $recordTex['textures'];
                }
            }
            //颜色
            $colorsData = new COLORS();
            $record['colorsSN'] = array_flip($colorsData->colorsControl());
            $this->render('updateTemp',$record);
        }
        
        /**
		 * 修改新命名规则的顶视图的尺寸
         * $temp['type']=>模型/素材，$temp['id']=>数据id，$temp['length']=>修改之后的长度，
         * $temp['width']=>修改之后的宽度，$temp['height']=>修改之后的高度
		 * @author zhangyong
		 */
		public function actionChangeTSize()
		{
            $data = array('status'=>false,'info'=>'修改失败！');
            if(isset($_POST['temp'])){
                $temp = $_POST['temp'];
                $model = Info::model()->findByPk($temp['id']);
                if($temp['type'] == 'mold')
                    $model = Mold::model()->findByPk($temp['id']);
                $number = '';
                if(!empty($model)){
                    if($temp['type'] == 'mold'){
                        $nameTemp = explode('-', $model['name']);
                        $number = $nameTemp[0];
                    }else{
                        $number = $model['number'];
                    }
                }
                if(!empty($number)){
                    $textures = Texture::model()->findAll("name like '%".$number."%'");
                    if(!empty($textures)){
                        Texture::model()->updateAll(array(
                            'length'=>$temp['length'],'width'=>$temp['width'],'height'=>$temp['height']
                        ),"name like '%".$number."%'");
                        $data = array('status'=>true,'info'=>'修改成功！');
                    }
                }
            }
            echo CJSON::encode($data);
        }
        
        public function loadModel($id)
        {
            $model = Info::model()->findByPk($id,'is_del=0');
            if(null===$model)
                throw new CHttpException(404,'素材'.$id.'不存在或者已经删除！.');
            return $model;
        }
        
        public function loadOrderModel($id)
        {
            $model = Order::model()->findByPk($id,'is_del=0');
            if(null===$model)
                throw new CHttpException(404,'订单'.$id.'不存在或者已经删除！.');
            return $model;
        }
    }