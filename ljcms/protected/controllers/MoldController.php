<?php
	/**
	 * 
	 * @author zhangyong
     * 模型管理 2014/09/29 更新
	 */
	class MoldController extends CmsController
	{
        private $root='/mnt/static/molds';//等待上传的模型目录
//        private $root='D:/www/static/molds';//测试时使用
		private $errorFile='autoCreateMoldError.txt';//发生错误，用于回滚用的文件
		private $successFile='autoCreateMoldSuccess.txt';//上传成功后，用于删除作废图片的文件
        private $GMFile='GMFile.txt';//存放高模路径的文件，上传完之后需要删除掉
        private $unUpFile='unUpFile.txt';//存放未上传的文件，成功上传完之后需要删除掉
        
        //编辑模型时相关文档
        private $textureErrorFile='autoCreateTextureError.txt';//发生错误，用于回滚用的文件
		private $textureSuccessFile='autoCreateTextureSuccess.txt';//上传成功后，用于删除作废图片的文件
        private $unUpTexture='unUpTexture.txt';//存放未上传的文件，成功上传完之后需要删除掉
        
        public $moldForms=array('max','awd','fbx','md5','3ds');
        public $imgForms=array('jpg','jpeg','png');
        
        public $moreTex = array('UV','FX','MUV','MFX');//可加多张图片的贴图字段缩写

        /**
		 * 模型列表
		 * @author zhangyong
		 */
		public function actionIndex()
		{
            $like = '';//查询模型条件
            $connect=Yii::app()->db;
            $name=null;
            $params=$_GET;
            $seachData = array('name'=>'','timeStart'=>'','timeEnd'=>'');
            if(isset($params['name']) && !empty($params['name'])){
                $like = " and ( name like '%".trim($params['name'])."%' or item like '%".trim($params['name'])."%' )";
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
            $sql = "select * from tbl_mold where is_del =0 $like order by create_time desc";
            $data=$this->getIndex($sql);
            $ul=$data['list'];
            $pages=$data['pages'];
            $this->render('index',array('ul'=>$ul,'pages' => $pages,'seachData'=>$seachData));
        }
        
        /**
		 * 编辑模型操作
         * @PS：编辑状态下，模型的类型不可编辑
		 * @author zhangyong
		 */
        public function actionUpdate($id)
        {
            $model = $this->loadModel($id);
            if($model['is_old'] == 0){
                $record = Mold::model()->_updateTemp($model,array('model'=>'Mold'));
                //颜色
                $colorsData = new COLORS();
                $record['colorsSN'] = array_flip($colorsData->colorsControl());
                $this->render('updateTemp',$record);
                exit;
            }
            $connect = Yii::app()->db;
            $params = $_POST;
            $oldImage = $model['image'];
            $oldFloorplan = $model['floorplan'];
            $defaultData = $this->getDefault();//初始数据
            $selData=array();//已选数据
            
            $moldType = $model['mold_type'];
            $moldImg = $model['image'];
            $moldSrc = $model['mold'];
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
            //已添加颜色帧
            $tids = array();
            if(!empty($model['texture_id']))
                $tids = json_decode ($model['texture_id'],true);
            $textures = array();//贴图数据
            $reference = array();//组合贴图图片名称参考数据
            $colors = array();//颜色路径=>简写字母 数组
            $colorTemp = new COLORS();
            $colorsData = $colorTemp->colorsControl();
            foreach ($colorsData as $kco=>$col){
                $cdEXT = explode('|', $col);
                $colors[$cdEXT[1]] = $kco;
            }
            if(!empty($tids)){
                $tids = array_keys($tids);
                $texData = Mold::model()->_getTexture($tids);
                $textures = $texData['textures'];
                $reference = $texData['reference'];
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
            $sql='select material_id from tbl_mold_material_relation where mold_id='.$id;
            $selMid=$connect->createCommand($sql)->queryRow();
            $Msel=array('second'=>array(),'pid'=>'','secid'=>'');
            if(!empty($selMid['material_id']))
                $Msel=$this->BMsel($selMid['material_id'],array('model'=>'Material'));
            
            //已选中的风格
            $selStyle=array();
            $sql='select style_id from tbl_mold_style_relation where mold_id='.$id;
            $selStyleData=$connect->createCommand($sql)->queryAll();
            if(!empty($selStyleData)){
                foreach ($selStyleData as $ssa){
                    array_push($selStyle, $ssa['style_id']);
                }
            }
            
            //记录已选信息
            $selData = array('labels'=>$labels,'selCat'=>$selCat,'Bsel'=>$Bsel,'Msel'=>$Msel,'selStyle'=>$selStyle);
            
            //更新模型信息
            try{
                $transaction=$connect->beginTransaction();
                if(isset($params['Mold'])){
                    $molds=$params['Mold'];
                    $model->attributes=$molds;
                    if(intval($molds['category_id'])<=0)
                        $model->category_id=0;
                    //品牌馆
                    if(!empty($molds['brandhall_id']) && intval($molds['brandhall_id'])>0){
                        $model->brandhall_id=$molds['brandhall_id'];
                    }else{
                        $model->brandhall_id=null;
                    }
                    //根据模型的类型处理模型文件
                    $fileHelper=new FileHelper;
                    $fileHelper->subFolder='mold';
                    if($moldType == 0){
                        $model->mold = $params['moldText'];
                    }else{
                        if($fileHelper->hasUploadFile($model,'mold')){
                            $mold = $fileHelper->saveFile($model,'mold',array('upyun'=>false));
                            $model->mold = $mold;
                        }else{
                            $model->mold = $moldSrc;
                        }
                    }
                    //保存缩略图
                    if($fileHelper->hasUploadFile($model,'image')){
                        $moldImage = $fileHelper->saveFile($model,'image',array('upyun'=>Yii::app()->params['upYun']));
                        $model->image = $moldImage;
                    }else{
                        $model->image = $moldImg;
                    }
                    $model->mold_type = $moldType;
                    //品牌系列
                    if(isset($molds['brand_id']) && intval($molds['brand_id'][0])>0){
                        if(intval($molds['brand_id'][1])>0){
                            $model->brand_id=$molds['brand_id'][1];
                        }elseif(intval($molds['brand_id'][1])==0 && intval($molds['brand_id'][0])>0){
                            $model->brand_id=$molds['brand_id'][0];
                        }elseif(intval($molds['brand_id'][1])==0 && intval($molds['brand_id'][0])==0){
                            $model->brand_id=null;
                        }
                    }else{
                        $model->brand_id=0;
                    }
                    if(isset($molds['label_id'][1]) && !empty($molds['label_id'][1])){
                        $model->label_id = $molds['label_id'][1];
                    }else{
                        $model->label_id = $molds['label_id'][0];
                    }
                    if($model->save()){
                        //保存模型材质
                        if(isset($params['material_id'])){
                            $material_id=null;
                            if(intval($params['material_id'][1])>0){
                                $material_id=$params['material_id'][1];
                            }elseif(intval($params['material_id'][1])<=0 && intval($params['material_id'][0])>0){
                                $material_id=$params['material_id'][0];
                            }
                            if(!empty($material_id) && intval($material_id)>0){
                                $sql="replace into tbl_mold_material_relation (mold_id,material_id) values (".$model->id.",".$material_id.")";
                                $connect->createCommand($sql)->execute();
                            }
                        }
                        //保存模型风格
                        if(isset($params['style'])){
                            $sql='delete from tbl_mold_style_relation where mold_id='.$model->id;
                            $connect->createCommand($sql)->execute();
                            foreach ($params['style'] as $style){
                                $sql="replace into tbl_mold_style_relation (mold_id,style_id) values (".$model->id.",".$style.")";
                                $connect->createCommand($sql)->execute();
                            } 
                        }
                        //更新贴图的长宽高
                        if(!empty($params['length']) || !empty($params['width']) || !empty($params['height'])){
                            $texture_ids = json_decode($model->texture_id,true);
                            if(!empty($model->texture_id) && !empty($texture_ids)){
                                $conLike = 'id in ('. implode(',', array_keys ($texture_ids)) .')';
                                Texture::model()->updateAll(array('length'=>$params['length'],'width'=>$params['width'],'height'=>$params['height']),$conLike);
                                $textureDas = Texture::model()->find('image is not null and '.$conLike);
                                if(!empty($textureDas))
                                    $model->image = $textureDas['image'];
                                $textureDas = Texture::model()->find('floorplan is not null and '.$conLike);
                                if(!empty($textureDas))
                                    $model->floorplan = $textureDas['floorplan'];
                                $model->save();
                            }
                                
                        }
                        //更新模型对应的元素
                        Mold::model()->_updateE($model->id);
                        
                        $transaction->commit();
                        
                        $this->redirect('/mold/index');   
                    }
                }  
            }  catch (Exception $e) {
                $transaction->rollback();
                throw new CHttpException(500,$e->getMessage());//测试时使用
            }
            $this->render('update',array(
                'model'=>$model,
                'defaultData'=>$defaultData,
                'selData'=>$selData,
                'textures'=>$textures,
                'reference'=>$reference,
                'colors'=>$colors
                    ));
        }
        
        /**
		 * 删除贴图图片
		 * @author zhangyong
		 */
        public function actionDelTexImg()
        {
            $data = array('status'=>false,'info'=>'删除失败！');
            $params = $_POST;
            if(isset($params['temp'])){
                $temp = $params['temp'];
                $model = Texture::model()->findByPk($temp['tid']);
                if(!empty($model)){
                    $img = json_decode($model[$temp['column']],true);
                    unset($img[$temp['imgKey']]) ;
                    $model->$temp['column'] = json_encode($img);
                    if($model->save())
                        $data = array('status'=>true,'info'=>'删除成功！');
                }
            }
            echo CJSON::encode($data);
        }
        /**
		 * 删除模型操作
		 * @author zhangyong
		 */
        public function actionDelete()
        {
            $connection=Yii::app()->db;
            $result = array('status'=>false,'info'=>'删除模型失败！');
            try{
                $transaction=$connection->beginTransaction();
                if(isset($_POST['id']) && !empty($_POST['id'])){
                    $id = intval($_POST['id']);
                    $model = Mold::model()->findByPk($id);
                    $model->is_del = 1;
                    //修改素材模型信息
                    $infos = Info::model()->with('molds')->findAll(array(
                        'select'=>'*',
                        'condition'=>"t.is_del=0 and molds.id=".$model->id,
                        'group'=>'t.id',
                    ));
                    InfoMoldRelation::model()->deleteAll("mold_id=".$model->id);
                    if(!empty($infos)){
                        foreach ($infos as $info){
                            Info::model()->_IPME(array('type'=>'unbind','model'=>'Mold','info'=>$info,'select'=>array($model->id)));
                        }
                    }
                    if($model->save()){
                        $eles = array();
                         $elements = Element::model()->findAll("is_del=0 and mold_id=".$id);
                         if(!empty($elements)){
                             foreach ($elements as $element){
                                 if(!in_array($element['id'], $eles))
                                         array_push ($eles, $element['id']);
                             }
                             Element::model()->updateAll(array('is_del'=>1),"id in (". implode(',', $eles) .")");
                             ProductElementRelation::model()->deleteAll("element_id in (". implode(',', $eles) .")");
                             SpaceElementRelation::model()->deleteAll("element_id in (". implode(',', $eles) .")");
                             ShowroomElementRelation::model()->deleteAll("element_id in (". implode(',', $eles) .")");
                             ElementLayer::model()->deleteAll("element_id in (". implode(',', $eles) .")");
                         }
                         ProductMoldRelation::model()->deleteAll("mold_id=".$id);
                         //删除模型的贴图
                         $texture_id = json_decode($model->texture_id,true);
                         if(!empty($model->texture_id) && !empty($texture_id))
                             Texture::model ()->deleteAll('id in ('. implode(',',array_keys($texture_id)) .')');
                         $model->texture_id = '';
                         $model->save();
                         $result = array('status'=>TRUE,'info'=>'删除模型成功！');
                         $result['id'] = $model->id;
                     }
                }
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollback();
                throw new CHttpException(500,$e->getMessage());//测试时使用
            }
            
            echo CJSON::encode($result);
        }
        
        public function loadModel($id)
        {
            $model = Mold::model()->findByPk($id,'is_del=0');
            if(null===$model)
                throw new CHttpException(404,'模型'.$id.'不存在或者已经删除！.');
            return $model;
        }
        
        /**
		 * 获取子分类（如分类/品牌）
		 * @author zhangyong
		 */
		public function actionGetCat()
		{
            $params=$_POST;
            $con='';//附加条件
            if(!isset($params['model']) || empty($params['model']))
                exit('缺少model参数');
            $model=trim($params['model']);
            if($model!='Material')
                $con .=' and is_show=1';
            if($model=='Brand')
                $con .=' and is_del=0 and is_check=1 and is_show=1';
            $model=ucfirst($model);//首字母大写
            $model=new $model;
            if(isset($params['pid']) && intval($params['pid'])>0){
                $pid=$params['pid'];
                $ret=$model->findAll(array('select'=>'id,name','condition'=>'parent_id='.$_POST['pid'].$con));
                if(isset($params['source']) && $params['source']=='brandhall')
                    $ret=$model->findAll(array('select'=>'id,name','condition'=>'(parent_id is null or parent_id=0) and brandhall_id='.$_POST['pid'].$con));
                $data=CHtml::listData($ret,'id','name');
                echo CHtml::tag('option',array('value'=>'empty'),CHtml::encode('请选择'));
                foreach ($data as $key=>$value)
                {
                    echo CHtml::tag('option',array('value'=>$key),CHtml::encode($value));
                }
                
            }else{
                echo CHtml::tag('option',array('value'=>'empty'),CHtml::encode('请选择'));
            }
        }
        
        /**
		 * 删除贴图
		 * @author zhangyong
		 */
		public function actionDelMoldMap()
		{ 
            $data = array('status'=>false,'info'=>'删除失败!');
            if(isset($_POST['mmId']) && isset($_POST['id'])){
                if(isset($_POST['model']) && !empty($_POST['model'])){
                    $modelData = trim($_POST['model']);
                    $modelData = ucfirst($modelData);//首字母大写
                    $newModel = new $modelData;
                }else{
                    throw new CHttpException(500,'缺少必要的model参数！');
                }
                //删除该贴图
                Texture::model()->deleteByPk($_POST['mmId']);
                //更新商品/模型绑定的贴图数据
                if($modelData == 'Product'){
                    $result = $newModel->findByPk($_POST['id'],'is_delete=0  and is_show=1');
                }else{
                    $result = $newModel->findByPk($_POST['id'],'is_del=0');
                }
                if(!empty($result)){
                    if(!empty($result['texture_id'])){
                        $texture = json_decode($result['texture_id'],true);
                        unset($texture[$_POST['mmId']]);
                        $result->texture_id = json_encode($texture);
                        $result->save();
                    }
                }
                    
                $data = array('status'=>true,'info'=>'删除成功!');
            }
            echo CJSON::encode($data);
        }
        
        /*
        * 判断模型编号是否存在
        * @author zhangyong
        */
       public function actionGetExistMold()
       {
           $params = $_POST;
           $data = array('status'=>false,'info'=>'');
           if(isset($params['item']) && !empty($params['item'])){
               $rs = Mold::model()->count("item='{$params['item']}' and is_del = 0");
               if(!empty($rs))
                   $data = array('status'=>true,'info'=>'此模型编号已存在！');
           }
           echo CJSON::encode($data);
       }
       
       /**
        * 模型自动上传
        * 
        * 商品型号的命名规则：品牌域名-型号  如：FB-GBY307
        * 
        * 素材命名规则
        * 素材型号命名规则：品牌域名-型号  如：FB-GBY307
        * 素材编号命名规则：日期N素材ID  如：20141117N1
        * 
        * 模型的命名规则
        * 模型文件名称：素材编号-模型类型_制作人.后缀名  如：20141117N1-GMAX_XXX.MAX
        * 模 型 名 称： 素材编号-模型类型  如：20141117N1-GMAX
        * 模 型 型 号： 品牌域名-型号-模型类型  如：FB-GBY307-GMAX
        * 
        * 贴图命名规则
        * 有模型的贴图
        * 模型名称_颜色_长-宽-高_透明通道(N=无,Y=有)_贴图类型_制作人.后缀
        * 如：20141117N1-GMAX_BW_0-0-0_N_TS(GG YY DS TS)_XXX.( JPG PNG)
        * 模型名称_颜色_长-宽-高_透明通道(N=无,Y=有)_贴图类型-序列_制作人.后缀
        * 如：20141117N1-GMAX_BW_0-0-0_N_UV(MUV FX MFX)-1_XXX.( JPG PNG)
        * 无模型的贴图
        * 素材编号_颜色_长-宽-高_透明通道(N=无,Y=有)_贴图类型_制作人.后缀
        * 如：20141117N1_BW_0-0-0_N_TS(GG DS)_XXX.( JPG PNG)
        * 素材编号_颜色_长-宽-高_透明通道(N=无,Y=有)_贴图类型-序列_制作人.后缀
        * 如：20141117N1_BW_0-0-0_N_UV(MUV FX MFX)-0_XXX.( JPG PNG)
        * 
        * @author zhangyong  2014.11.22
        */
       public function actionAutoCreate()
       {
           ini_set("max_execution_time", 3600);  //用此function才能真正在运行时设置
           try{
               ignore_user_abort();//关掉浏览器，PHP脚本也可以继续执行.
				//正式机模型路径
 				$source=$this->root."/source";//上传的源文件
 				$backup=$this->root."/backup";//备份文件
                $GMFile=$this->root.'/'.$this->GMFile;//存放高模信息的文件
				$connection=Yii::app()->db;
				$transaction=$connection->beginTransaction();
                $filenames=array();//记录文件名，用于失败时的回滚
                $upYun = array();//记录需要上传到云盘的文件(图片文件)
                $unUpG = array();//记录未上传(不符合规范)的高模
                //处理高模
                $Gmolds = array();
                if(file_exists($GMFile))
				{
                    $GMMessage=file_get_contents($GMFile);
                    if(!empty($GMMessage)){
                        $Gmolds = explode("\r\n", $GMMessage);
                    }
                }
                if(!empty($Gmolds)){
                    foreach ($Gmolds as $Gmold){
                        if(empty($Gmold))
                            continue;
                        $GContent = explode('##', $Gmold);
                        //过滤掉不符合规范的上传文件
                        if(false===strpos($Gmold, "##") || false===strpos($GContent[0], ".")){
                            array_push($unUpG, $GContent[0]);
                            continue;
                        }
                        $Gpt=strrpos($GContent[0], ".");
                        $GFileForm = substr($GContent[0], $Gpt+1, strlen($GContent[0]) - $Gpt);//获取文件后缀
                        $GFileForm = strtolower($GFileForm);//把后缀名转换成小写
                        $GFName = substr($GContent[0], 0, $Gpt);//获取文件的名称(不含扩展名)
                        if($GFileForm !='max' || count(explode('_', $GFName)) !=2){
                            array_push($unUpG, $GContent[0]);
                            continue;
                        }
                        $Gmne = explode('_', $GFName);
                        $GmoldNameType = explode('-', $Gmne[0]);
                        if(strtolower(substr($GmoldNameType[1], -3)) != $GFileForm){
                            array_push($unUpG, $GContent[0]);
                            continue;
                        }
                        $pathBind = $GContent[1]."\\".$GContent[0];
                        $this->_createMold($GContent[0],$backup,array('path'=>$pathBind));
                    }
                }
                
                //处理非高模及贴图
				$files=scandir($source);
                foreach ($files as $file)
                {
                    if('.'==$file || '..'==$file || is_dir($file))
						continue;
					$filename=basename($file);
					if(false===strpos($filename,'_') || false===strpos($filename, "."))
						continue;
                    //过滤掉不符合规范的上传文件
                    $pt=strrpos($filename, ".");
                    $fileForm = substr($filename, $pt+1, strlen($filename) - $pt);//获取文件后缀
                    $fileForm = strtolower($fileForm);//把后缀名转换成小写
                    $FName = substr($filename, 0, $pt);//获取文件的名称(不含扩展名)
                    if(in_array($fileForm, $this->imgForms) || in_array($fileForm, $this->moldForms))
                    {
                        if(in_array($fileForm, $this->imgForms)){
                            if(count(explode('_', $FName)) !=6)
                                    continue;
                            $imgNameArr = explode('_', $filename);
                            $imgTypeArr = explode('-', $imgNameArr[4]);
                            if(in_array($imgTypeArr[0], $this->moreTex) && count($imgTypeArr) !=2)
                                    continue;
                        }
                                
                        if(in_array($fileForm, $this->moldForms)){
                            $mne = explode('_', $FName);
                            if(count($mne) !=2)
                                    continue;
                            $moldNameType = explode('-', $mne[0]);
                            if(strtolower(substr($moldNameType[1], -3)) != $fileForm)
                                    continue;
                        }
                                
                    }else{
                        continue;
                    }
					$filenames[]=$filename;
                    if(rename($source.'/'.$file, $backup.'/'.$filename))
					{
                        if(in_array($fileForm, $this->moldForms))
                                $this->_createMold($filename,$backup,array());
                        if(in_array($fileForm, $this->imgForms)){
                            $upPic = $this->_createTexture($filename,$backup);
                            array_push($upYun, $upPic);
                        }
                                
                    }
                }
                //处理图片上传到云盘
                if(!empty($upYun)){
                    if(Yii::app()->params['upYun']){
                        $fileHelper=new FileHelper;
                        foreach ($upYun as $upY){
                            $fileHelper->uploadUpYun($upY);
                        }
                    }
                }
                $transaction->commit();
                //上传成功后，删除作废的元素图片
				if(file_exists($this->root.'/'.$this->successFile))
				{
					$successFileMessage=file_get_contents($this->root.'/'.$this->successFile);
					$arr=explode("\r\n", $successFileMessage);
                    unset($arr[count($arr)-1]);
                    foreach ($arr as $item)
                    {
                        $this->deleteImage($item);
                    }
					unlink($this->root.'/'.$this->successFile);
				}
				if(file_exists($this->root.'/'.$this->errorFile))
                        unlink($this->root.'/'.$this->errorFile);
                if(file_exists($this->root.'/'.$this->unUpFile))
                        unlink($this->root.'/'.$this->unUpFile);
                if(file_exists($this->root.'/'.$this->GMFile))
                        unlink($this->root.'/'.$this->GMFile);
                //查看是否有不符合规范的文件
                if(!empty($unUpG)){
                    foreach ($unUpG as $UUG){
                        $unUpFile=fopen($this->root.'/'.$this->unUpFile, 'a');//追加方式
                        fwrite($unUpFile, $UUG."<br>");
                        fclose($unUpFile);
                    }
                }
                $findFiles=scandir($source);
                if(!empty($findFiles)){
                    foreach ($findFiles as $findFile){
                        if('.'==$findFile || '..'==$findFile || is_dir($findFile))
                            continue;
                        $unUpFile=fopen($this->root.'/'.$this->unUpFile, 'a');//追加方式
                        fwrite($unUpFile, $findFile."<br>");
                        fclose($unUpFile);
                    }
                }
                if(file_exists($this->root.'/'.$this->unUpFile)){
                    $unUpFile=fopen($this->root.'/'.$this->unUpFile, 'a');//追加方式
                    fwrite($unUpFile, "以上为上传失败的文件，可能原因为文件命名不符合规范<br>");
                    fclose($unUpFile);
                    $unUpMessage=file_get_contents($this->root.'/'.$this->unUpFile);
                    header("Content-type: text/html; charset=utf-8");
                    echo $unUpMessage;
                    exit;
                }
                echo "success";
           } catch (Exception $e) {
               $transaction->rollback();
               foreach ($filenames as $filename){
                   if(file_exists($backup.'/'.$filename))
                           rename($backup.'/'.$filename,$source.'/'.$filename);
               }
				//回滚的时候，查询autoCreateElementError.txt，删除记录的图片
				if(file_exists($this->root.'/'.$this->errorFile))
				{
					$errorFileMessage=file_get_contents($this->root.'/'.$this->errorFile);
					$arr=explode("\r\n", $errorFileMessage);
                    unset($arr[count($arr)-1]);
                    foreach ($arr as $item)
                    {
                        $this->deleteImage($item);
                    }
					unlink($this->root.'/'.$this->errorFile);//脚本结束的时候是否要自动删除该文件
				}
				if(file_exists($this->root.'/'.$this->successFile))
                        unlink($this->root.'/'.$this->successFile);
               throw new CHttpException(500,$e->getMessage());
           }
       }
       
       /*
        * 自动上传模型
        * @author zhangyong
        */
       public function _createMold($filename,$backup,$option=array())
       {
           try {
               $root=Yii::app()->params['realPathOfStatic'];
               //模型文件保存路径
               if(isset($option['path']) && !empty($option['path'])){
                   $filePath = $option['path'];
                   $fileType = mb_detect_encoding($filePath, array("ASCII","UTF-8","GB2312","GBK")); 
                   if( $fileType != 'UTF-8')
                       $filePath = mb_convert_encoding($filePath ,'utf-8' , $fileType);
               }else{
                   $filePath = $this->_UpFile($filename,$backup,array('path'=>'mold'));
               }
               $name=substr($filename, 0, strrpos($filename, '_'));
               $moldMaker = substr($filename, strrpos($filename, '_') + 1, strrpos($filename, '.')-strrpos($filename, '_')-1);
               $nameArray = explode('-', $name);
               $infoNum = $nameArray[0];//素材编号
               $moldType = $nameArray[1];//模型类型
               $with=array(
                   'materials'=>array(),
                   'styles'=>array(),
                    'molds'=>array(),
                    'products'=>array(
                        'on'=>'products.parent_id=0'
                    )
                );
               $info = Info::model()->with($with)->findByAttributes(array('number'=>$infoNum,'is_del'=>0));
               if(empty($info))
                   throw new CHttpException(404,'编号为'.$infoNum.'的素材不存在或者已经删除！.');
               $model = Mold::model()->findByAttributes(array('name'=>$name,'is_del'=>0));
               if(empty($model))
               {
                   //创建模型
                   $this->log('create','info','mold.txt');
                   $model = new Mold();
                   $model->name = $name;
                   $model->mold_type = Yii::app()->params['moldNameType'][$moldType];
                   $model->isNewRecord = TRUE;
               }else{
                   //编辑模型
                   $this->log('update','info','mold.txt');
                   if($model->mold_type !=0 && !empty($model->mold)){//非高模
                        //删除原来的图片,正式的脚本不用删除缩略图,在上传成功之后再删除，记录中successFile中
                        $handleSuccess=fopen($this->root.'/'.$this->successFile, 'a');//追加方式
                        fwrite($handleSuccess, $root.$model->mold."\r\n");
                        fclose($handleSuccess);
                   }
               }
               $model->item = $info['item'].'-'.$moldType;
               $model->mold = $filePath;
               $model->type = $info['type'];
               $model->length = $info['length'];
               $model->width = $info['width'];
               $model->height = $info['height'];
               $model->category_id = $info['category_id'];
               $model->label_id = $info['label_id'];
               $model->brand_id = $info['brand_id'];
               $model->brandhall_id = $info['brandhall_id'];
               $model->maker = $moldMaker;
               if($model->save()){
                   //素材关联模型
                   $infoMoldEx = InfoMoldRelation::model()->find('info_id='.$info['id'].' and mold_id='.$model->id);
                   $hasIMT = InfoMoldRelation::model()->find('info_id='.$info['id'].' and mold_id!='.$model->id.' and mold_type='.$model->mold_type);
                   if(!empty($hasIMT) && !empty($info['products'])){//单个素材与单种模型关系一对一，重复的则后一个覆盖前一个，关联的数据也是一样同步
                        $oldEle = Element::model()->findAll("is_del=0 and mold_id=".$hasIMT['mold_id']);
                        if(!empty($oldEle)){
                            $oldEleDel = array();
                            foreach ($oldEle as $oe){
                                if(!in_array($oe['id'], $oldEleDel))
                                        array_push ($oldEleDel, $oe['id']);
                            }
                            ProductElementRelation::model()->deleteAll("product_id=".$info['products'][0]['product_id']." and element_id in (". implode(',',$oldEleDel) .")");
                        }
                        ProductMoldRelation::model()->deleteAll('product_id='.$info['products'][0]['product_id'].' and mold_id='.$hasIMT['mold_id']);
                        InfoMoldRelation::model()->deleteAll('info_id='.$info['id'].' and mold_id='.$hasIMT['mold_id']);
                   }
                   if(empty($infoMoldEx)){
                       $infoMoldRelation = new InfoMoldRelation();
                       $infoMoldRelation->info_id = $info['id'];
                       $infoMoldRelation->mold_id = $model->id;
                       $infoMoldRelation->mold_type = $model->mold_type;
                       $infoMoldRelation->save();
                   }
                   //检查是否有顶视图或者透视图上传,如果有则需要检查该模型是否创建了包含TS或DS图的贴图数据，如果没有，需要创建
                   $moldTexAll = Texture::model()->findAll(array(
                       'select'=>'*',
                       'condition'=>"name like '%".$infoNum."%' and (image is not null or floorplan is not null)",
                       'group'=>'id',
                   ));
                   if(!empty($moldTexAll) && !in_array($moldType, array_values(Yii::app()->params['YYForm']))){
                       foreach ($moldTexAll as $tll){
                           $tllNameArr = explode('_', $tll['name']);
                           $tllColor = $tllNameArr[1];
                           $tllModel = Texture::model()->findByAttributes(array('name'=>$name.'_'.$tllColor));
                           if(empty($tllModel)){
                               $tllModel = new Texture();
                               $tllModel->name = $name.'_'.$tllColor;
                               $tllModel->color_name = $tll['color_name'];
                               $tllModel->color_value = $tll['color_value'];
                               $tllModel->alpha = $tll['alpha'];
                               $tllModel->length = $tll['length'];
                               $tllModel->width = $tll['width'];
                               $tllModel->height = $tll['height'];
                               $tllModel->maker = $tll['maker'];
                               if(!empty($tll['image']))
                                   $tllModel->image = $tll['image'];
                               if(!empty($tll['floorplan']))
                                   $tllModel->floorplan = $tll['floorplan'];
                               $tllModel->save();
                           }
                           
                       }
                   }
                   //模型关联贴图
                   $textures = Texture::model()->findAll("name like '%".$name."%'");
                   if(!empty($textures) && !in_array($moldType, array_values(Yii::app()->params['YYForm']))){
                       $textureIds = array();
                       foreach ($textures as $tk=>$tv){
                           $textureIds[$tv['id']] = $tk + 1;
                       }
                       if(!empty($textureIds) ){
                           $model->texture_id = json_encode($textureIds);
                           $model->image = $textures[0]['image'];
                           $model->floorplan = $textures[0]['floorplan'];
                           $model->save();
                       }
                   }
                   //模型关联材质
                   if(!empty($info['materials'])){
                       foreach ($info['materials'] as $material){
                           $materData = MoldMaterialRelation::model()->find('mold_id='.$model->id.' and material_id='.$material['id']);
                           if(empty($materData)){
                               $moldMaterial = new MoldMaterialRelation();
                               $moldMaterial->mold_id = $model->id;
                               $moldMaterial->material_id = $material['id'];
                               $moldMaterial->save();
                           }
                       }
                   }
                   //模型关联风格
                   if(!empty($info['styles'])){
                       foreach ($info['styles'] as $style){
                           $styleEx = MoldStyleRelation::model()->find('mold_id='.$model->id.' and style_id='.$style['id']);
                           if(empty($styleEx)){
                               $moldStyle = new MoldStyleRelation();
                               $moldStyle->mold_id = $model->id;
                               $moldStyle->style_id = $style['id'];
                               $moldStyle->save();
                           }
                       }
                   }
                   //素材关联商品，商品关联模型，商品关联元素
                   if(!empty($info['item']))
                       Mold::model()->_bindPME($info);
                   //更新素材的贴图信息(img_condition,mold_condition)
                   Info::model()->_countInfoMI($info['id']);
               }
                   
               
           } catch (Exception $e) {
               
               throw new CHttpException(500,$e->getMessage());
           }
        }
       
       /*
        * 自动上传贴图
        * @author zhangyong
        */
       public function _createTexture($filename,$backup)
       {
           $singlePic = array('TS','DS');
           $root=Yii::app()->params['realPathOfStatic'];
           try{
               //贴图文件保存路径
               $filePath = $this->_UpFile($filename,$backup,array('path'=>'texture'));
               //处理贴图数据
               $name=substr($filename, 0, strrpos($filename, '_'));
               $textureMaker = substr($filename, strrpos($filename, '_') + 1, strrpos($filename, '.')-strrpos($filename, '_')-1);
               $nameArray = explode('_', $name);
               if(false===strpos($nameArray[0],'-')){
                   $infoData = Info::model()->find("is_del=0 and number='".$nameArray[0]."'");
                   if(empty($infoData))
                       throw new CHttpException(404,'编号为'.$nameArray[0].'的素材不存在或者已经删除！.');
               }
               $nameArr = explode('-', $nameArray[0]);
               if($nameArray[4] == 'YY'){//阴影贴图
                   $infoNum = $nameArr[0];//素材编号
                   $moldType = $nameArr[1];//模型类型
                   //阴影贴图上传
                   $info = Info::model()->with('materials','styles')->findByAttributes(array('is_del'=>0,'number'=>$infoNum));
                   $yyMold = Mold::model()->findByAttributes(array('name'=>$nameArray[0],'is_del'=>0));
                   if(!empty($yyMold)){
                       //编辑阴影模型
                       $this->log('update','info','mold.txt');
                       if(!empty($yyMold->image)){
                            //删除原来的图片,正式的脚本不用删除缩略图,在上传成功之后再删除，记录中successFile中
                            $handleSuccess=fopen($this->root.'/'.$this->successFile, 'a');//追加方式
                            fwrite($handleSuccess, $root.$yyMold->image."\r\n");
                            fclose($handleSuccess);
                       }
                   }else{
                       //创建阴影模型
                        $this->log('create','info','mold.txt');
                        $yyMold = new Mold();
                        $yyMold->name = $nameArray[0];
                        $yyMold->mold_type = Yii::app()->params['moldNameType'][$moldType];
                        $yyMold->isNewRecord = TRUE;
                   }
                   $yyMold->item = $info['item'].'-'.$moldType;
                   $yyMold->image = $filePath;
                   $yyMold->type = $info['type'];
                   $yyMold->length = $info['length'];
                   $yyMold->width = $info['width'];
                   $yyMold->height = $info['height'];
                   $yyMold->category_id = $info['category_id'];
                   $yyMold->label_id = $info['label_id'];
                   $yyMold->brand_id = $info['brand_id'];
                   $yyMold->brandhall_id = $info['brandhall_id'];
                   $yyMold->maker = $textureMaker;
                   if($yyMold->save()){
                        //更新模型的相关信息，tbl_mold_material_relation,tbl_mold_style_relation
                        if(!empty($info['materials'])){
                            foreach ($info['materials'] as $material){
                                $hasMater = MoldMaterialRelation::model()->find('mold_id='.$yyMold->id.' and material_id='.$material['id']);
                                if(empty($hasMater)){
                                    $moldMaterialRelation = new MoldMaterialRelation();
                                    $moldMaterialRelation->mold_id = $yyMold->id;
                                    $moldMaterialRelation->material_id = $material['id'];
                                    $moldMaterialRelation->save();
                                }
                            }
                        }
                        if(!empty($info['styles'])){
                            foreach ($info['styles'] as $style){
                                $hasStyle = MoldStyleRelation::model()->find('mold_id='.$yyMold->id.' and style_id='.$style['id']);
                                if(empty($hasStyle)){
                                    $moldStyleRelation = new MoldStyleRelation();
                                    $moldStyleRelation->mold_id = $yyMold->id;
                                    $moldStyleRelation->style_id = $style['id'];
                                    $moldStyleRelation->save();
                                }
                            }
                        }
                        //关联素材模型数据
                        $infoYYM = InfoMoldRelation::model()->find('info_id='.$info['id'].' and mold_id='.$yyMold->id);
                        $infoYYMTT = InfoMoldRelation::model()->find('info_id='.$info['id'].' and mold_id!='.$yyMold->id.' and mold_type='.$yyMold->mold_type);
                        if(!empty($infoYYMTT))
                            InfoMoldRelation::model()->deleteAll('info_id='.$info['id'].' and mold_id='.$infoYYMTT['mold_id']);
                        if(empty($infoYYM)){
                            $infoMoldRelation = new InfoMoldRelation();
                            $infoMoldRelation->info_id = $info['id'];
                            $infoMoldRelation->mold_id = $yyMold->id;
                            $infoMoldRelation->mold_type = $yyMold->mold_type;
                            $infoMoldRelation->save();
                        }
                        //素材关联商品，商品关联模型，商品关联元素
                        if(!empty($info['item']))
                            Mold::model()->_bindPME($info);
                        //更新素材的模型信息
                        $moldCondition = json_decode($info['mold_condition'],true);
                        if(empty($info['mold_condition']) || empty($moldCondition))
                            $moldCondition = array();
                        $moldCondition[$yyMold->mold_type] = $yyMold->id;
                        $info->mold_condition = json_encode($moldCondition);
                        $info->save();
                   }
                       
               }else{//非阴影贴图
                   $texName = $nameArray[0].'_'.$nameArray[1];//贴图名称
                   $texture = Texture::model()->findByAttributes(array('name'=>$texName));
                   if(empty($texture))
                       $texture = new Texture();
                   if(in_array($nameArray[4], $singlePic))
                   {
                        $column = Yii::app()->params['imgColumn'][$nameArray[4]];//获取字段名
                        if(!empty($texture))
                        {
                             //编辑贴图
                             $this->log('update','info','texture.txt');
                             if(!empty($texture->$column)){
                                //删除原来的图片,正式的脚本不用删除缩略图,在上传成功之后再删除，记录中successFile中
                                $handleSuccess=fopen($this->root.'/'.$this->successFile, 'a');//追加方式
                                fwrite($handleSuccess, $root.$texture->$column."\r\n");
                                fclose($handleSuccess);
                             }
                        }
                        $texture->$column = $filePath;

                   }else{
                        $texTypeArr = explode('-', $nameArray[4]);
                        $column = Yii::app()->params['imgColumn'][$texTypeArr[0]];//获取字段名
                        $oldTex = array();
                        if(!empty($texture))
                        {
                            //编辑贴图
                            $this->log('update','info','texture.txt');
                            $TexImgUpdate = $texture->$column;
                            $oldTex = json_decode($texture->$column,true);
                            if(count($texTypeArr)>1 && !empty($oldTex)){
                                $TexImgUpdate = null;
                                if(isset($oldTex[$texTypeArr[1]]))
                                    $TexImgUpdate = $oldTex[$texTypeArr[1]];
                            }
                            if(!empty($TexImgUpdate)){
                               //删除原来的图片,正式的脚本不用删除缩略图,在上传成功之后再删除，记录中successFile中
                               $handleSuccess=fopen($this->root.'/'.$this->successFile, 'a');//追加方式
                               fwrite($handleSuccess, $root.$TexImgUpdate."\r\n");
                               fclose($handleSuccess);
                            }
                        }
                        $UpImgFile = $filePath;
                        if(count($texTypeArr)>1){
                            $oldTex[$texTypeArr[1]] = $filePath;
                            $UpImgFile = json_encode($oldTex);
                        }
                        $texture->$column = $UpImgFile;
                   }
                   $texture->name = $nameArray[0].'_'.$nameArray[1];
                   //颜色
                   $colorsObj = new COLORS();
                   $colors = $colorsObj->colorsControl();
                   $colorArray = explode('|', $colors[$nameArray[1]]);
                   $texture->color_name = $colorArray[0];
                   $texture->color_value = $colorArray[1];
                   //长宽高
                   $lwh = explode('-', $nameArray[2]);
                   $texture->length = $lwh[0];
                   $texture->width = $lwh[1];
                   $texture->height = $lwh[2];
                   //是否透明通道
                   $isAlpha = array('Y'=>1,'N'=>0);
                   if(!in_array($nameArray[4], $singlePic))
                           $texture->alpha = $isAlpha[$nameArray[3]];
                   $texture->maker = $textureMaker;
                   if($texture->save()){
                       //上传的贴图为顶视图和透视图,更新该素材下模型(高模和低模)的顶视图和透视图
                       if(strpos($nameArray[0],'-')){
                           $condTex = "name like '%".$nameArr[0]."%' and name like '%".$nameArray[1]."%'";
                           if(in_array($nameArray[4], $singlePic)){//上传TS、DS，更新所有的DS、TS图
                               $moldTypeAll = Info::model()->with('molds')->find("number='".$nameArr[0]."'");
                               if(!empty($moldTypeAll['molds'])){
                                   $moldNameType = array_flip(Yii::app()->params['moldNameType']);
                                   //上传TS、DS图时检查该素材下所有模型是否都有TS或DS图，如果没有则需要创建模型对应颜色的贴图数据
                                   foreach ($moldTypeAll['molds'] as $mta){
                                       if(!in_array($mta['mold_type'], array_keys(Yii::app()->params['YYForm'])) && $mta['is_old']==0){
                                           $texExtModel = Texture::model()->findByAttributes(array('name'=>$nameArr[0]."-".$moldNameType[$mta['mold_type']]."_".$nameArray[1]));
                                           if(empty($texExtModel))
                                               $texExtModel = new Texture();
                                           $texExtModel->name = $nameArr[0].'-'.$moldNameType[$mta['mold_type']].'_'.$nameArray[1];
                                           $texExtModel->color_name = $colorArray[0];
                                           $texExtModel->color_value = $colorArray[1];
                                           $texExtModel->$column = $filePath;
                                           $texExtModel->length = $lwh[0];
                                           $texExtModel->width = $lwh[1];
                                           $texExtModel->height = $lwh[2];
                                           $texExtModel->alpha = $isAlpha[$nameArray[3]];
                                           $texExtModel->maker = $textureMaker;
                                           if($texExtModel->save()){
                                                $moldTexSave = Mold::model()->findByPk($mta['id'],'is_del=0');
                                                $mtsId = json_decode($moldTexSave['texture_id'],true);
                                                if(empty($moldTexSave['texture_id']) || empty($mtsId))
                                                    $mtsId = array();
                                                 $moldTexSave->$column = $filePath;
                                                 if(!in_array($texExtModel->id, array_keys($mtsId))){
                                                     $mtsId[$texExtModel->id] = count($mtsId) + 1;
                                                     $moldTexSave->texture_id = json_encode($mtsId);
                                                 }
                                                 $moldTexSave->save();
                                            }
                                       }
                                       
                                   }
                               }
                               Texture::model()->updateAll(array($column=>$filePath),$condTex);
                           }else{//上传其他贴图时，更新所有的DS、TS图
                               $allInfoTexImg = Texture::model()->findAll($condTex." and image is not null");
                               if(!empty($allInfoTexImg))
                                   Texture::model()->updateAll(array('image'=>$allInfoTexImg[0]['image']),$condTex);
                               $allInfoTexFloor = Texture::model()->findAll($condTex." and floorplan is not null");
                               if(!empty($allInfoTexFloor))
                                   Texture::model()->updateAll(array('floorplan'=>$allInfoTexFloor[0]['floorplan']),$condTex);
                           }
                       }
                       //处理与该贴图的素材及模型关联数据
                       $texInfo = Info::model()->with('molds')->findByAttributes(array('number'=>$nameArr[0],'is_del'=>0));
                       if(false===strpos($nameArray[0],'-')){
                           //纯贴图
                           $infoTex = array();
                           if(!empty($infoTex))
                               $infoTex = json_decode($texInfo['texture_id'],true);
                           if(!in_array($texture->id, array_keys($infoTex))){
                               $infoTex[$texture->id] = count($infoTex) + 1;
                               $texInfo->texture_id = json_encode($infoTex);
                               $texInfo->save();
                           }
                           if(!empty($texInfo['item'])){
                               //素材关联商品
                               Mold::model()->_bindPME($texInfo);
                           }
                       }else{
                           //模型贴图
                           $texMold = Mold::model()->findByAttributes(array('name'=>$nameArray[0]));
                           if(!empty($texMold)){
                                $moldTex = array();
                                if(!empty($moldTex))
                                    $moldTex = json_decode($texMold['texture_id'],true);
                                if(!in_array($texture->id, array_keys($moldTex))){
                                    $moldTex[$texture->id] = count($moldTex) + 1;
                                    $texMold->texture_id = json_encode($moldTex);
                                    $texMold->save();
                                }
                           }
                           
                       }
                       //更新素材的贴图信息(img_condition,mold_condition)
                       Info::model()->_countInfoMI($texInfo['id']);
                   }
               }
               
               return $filePath;
           } catch (Exception $e) {
               throw new CHttpException(500,$e->getMessage());
           }
       }
       
       /*
        * 上传图片到static
        * @author zhangyong
        */
       public function _UpFile($filename,$backup,$option=array())
       {
            $root=Yii::app()->params['realPathOfStatic'];
            $dest='/upload/mold/'.date('Y/m/d').'/';
            if(isset($option['path']) && !empty($option['path']))
                $dest='/upload/'.$option['path'].'/'.date('Y/m/d').'/';
            if(!is_dir($root.$dest))
            {
                if(!mkdir($root.$dest, 0777, true))
                        throw new CHttpException(500,'创建文件夹失败...');
            }
            $pt=strrpos($filename, ".");
            $fileForm = substr($filename, $pt+1, strlen($filename) - $pt);//获取文件后缀
            $fileForm = strtolower($fileForm);//把后缀名转换成小写
            $moldFile = uniqid().'.'.$fileForm;//新的文件名
            //copy发生错误该如何处理
            if(false===copy($backup.'/'.$filename,$root.$dest.$moldFile))
                    throw new CHttpException(500,'copy失败...');
            //记录已经复制到mold目录下的文件，用于在上传失败时回滚
            if(isset($option['errorPath'])){
                $handleError=fopen($this->root.'/'.$option['errorPath'], 'a');//追加方式
            }else{
                $handleError=fopen($this->root.'/'.$this->errorFile, 'a');//追加方式
            }
            fwrite($handleError, $root.$dest.$moldFile."\r\n");
            fclose($handleError);
            return $dest.$moldFile;
       }
       
       /**
		 * 更新旧的贴图
         * 贴图命名规则
         * 贴图名称 如：M-2005-T-0 2050为模型ID，0为贴图序列
         * 没有颜色的用0代替
         * 贴图名称_颜色_长-宽-高_透明通道(N=无,Y=有)_贴图类型_制作人.后缀
         * 如：M-2005-T-0_BW_0-0-0_N_TS(GG DS)_XXX.( JPG PNG)
         * 贴图名称_颜色_长-宽-高_透明通道(N=无,Y=有)_贴图类型-序列_制作人.后缀
         * 如：M-2005-T-0_BW_0-0-0_N_UV(MUV FX MFX)-0_XXX.( JPG PNG)
		 * @author zhangyong
		 */
		public function actionUpOldTexture()
		{
            ini_set("max_execution_time", 3600);  //用此function才能真正在运行时设置
           try{
               ignore_user_abort();//关掉浏览器，PHP脚本也可以继续执行.
				//正式机模型路径
 				$source=$this->root."/updateTexture";//上传的源文件
 				$backup=$this->root."/backup";//备份文件
				$connection=Yii::app()->db;
				$transaction=$connection->beginTransaction();
                $filenames=array();//记录文件名，用于失败时的回滚
                $upYun = array();//记录需要上传到云盘的文件(图片文件)
                
                //更新贴图
				$files=scandir($source);
                foreach ($files as $file)
                {
                    if('.'==$file || '..'==$file || is_dir($file))
						continue;
					$filename=basename($file);
					if(false===strpos($filename,'_') || false===strpos($filename, "."))
						continue;
                    //过滤掉不符合规范的上传文件
                    $pt=strrpos($filename, ".");
                    $fileForm = substr($filename, $pt+1, strlen($filename) - $pt);//获取文件后缀
                    $fileForm = strtolower($fileForm);//把后缀名转换成小写
                    $FName = substr($filename, 0, $pt);//获取文件的名称(不含扩展名)
                    if(in_array($fileForm, $this->imgForms)){
                        if(count(explode('_', $FName)) !=6)
                                continue;
                        $imgNameArr = explode('_', $filename);
                        $imgTypeArr = explode('-', $imgNameArr[4]);
                        if(in_array($imgTypeArr[0], $this->moreTex) && count($imgTypeArr) !=2)
                                continue;
                    }
					$filenames[]=$filename;
                    if(rename($source.'/'.$file, $backup.'/'.$filename))
					{
                        $upPic = $this->_upOldT($filename,$backup);
                        array_push($upYun, $upPic);
                    }
                }
                //处理图片上传到云盘
                if(!empty($upYun)){
                    if(Yii::app()->params['upYun']){
                        $fileHelper=new FileHelper;
                        foreach ($upYun as $upY){
                            $fileHelper->uploadUpYun($upY);
                        }
                    }
                }
                $transaction->commit();
                //上传成功后，删除作废的元素图片
				if(file_exists($this->root.'/'.$this->textureSuccessFile))
				{
					$successFileMessage=file_get_contents($this->root.'/'.$this->textureSuccessFile);
					$arr=explode("\r\n", $successFileMessage);
                    unset($arr[count($arr)-1]);
                    foreach ($arr as $item)
                    {
                        $this->deleteImage($item);
                    }
					unlink($this->root.'/'.$this->textureSuccessFile);
				}
				if(file_exists($this->root.'/'.$this->textureErrorFile))
                        unlink($this->root.'/'.$this->textureErrorFile);
                if(file_exists($this->root.'/'.$this->unUpTexture))
                        unlink($this->root.'/'.$this->unUpTexture);
                //查看是否有不符合规范的文件
                $findFiles=scandir($source);
                if(!empty($findFiles)){
                    foreach ($findFiles as $findFile){
                        if('.'==$findFile || '..'==$findFile || is_dir($findFile))
                            continue;
                        $unUpFile=fopen($this->root.'/'.$this->unUpTexture, 'a');//追加方式
                        fwrite($unUpFile, $findFile."<br>");
                        fclose($unUpFile);
                    }
                }
                if(file_exists($this->root.'/'.$this->unUpTexture)){
                    $unUpFile=fopen($this->root.'/'.$this->unUpTexture, 'a');//追加方式
                    fwrite($unUpFile, "以上为上传失败的文件，可能原因为文件命名不符合规范<br>");
                    fclose($unUpFile);
                    $unUpMessage=file_get_contents($this->root.'/'.$this->unUpTexture);
                    header("Content-type: text/html; charset=utf-8");
                    echo $unUpMessage;
                    exit;
                }
                echo "success";
           } catch (Exception $e) {
               $transaction->rollback();
               foreach ($filenames as $filename){
                   if(file_exists($backup.'/'.$filename))
                           rename($backup.'/'.$filename,$source.'/'.$filename);
               }
				//回滚的时候，查询autoCreateElementError.txt，删除记录的图片
				if(file_exists($this->root.'/'.$this->textureErrorFile))
				{
					$errorFileMessage=file_get_contents($this->root.'/'.$this->textureErrorFile);
					$arr=explode("\r\n", $errorFileMessage);
                    unset($arr[count($arr)-1]);
                    foreach ($arr as $item)
                    {
                        $this->deleteImage($item);
                    }
					unlink($this->root.'/'.$this->textureErrorFile);//脚本结束的时候是否要自动删除该文件
				}
				if(file_exists($this->root.'/'.$this->textureSuccessFile))
                        unlink($this->root.'/'.$this->textureSuccessFile);
               throw new CHttpException(500,$e->getMessage());
           }
        }
        
        /**
		 * 更新旧的模型对应的贴图数据
		 * @author zhangyong
		 */
		public function _upOldT($filename,$backup)
		{
            $singlePic = array('TS','DS');
            $root=Yii::app()->params['realPathOfStatic'];
            try {
               //贴图文件保存路径
               $filePath = $this->_UpFile($filename,$backup,array('path'=>'texture','errorPath'=>$this->textureErrorFile));
               //处理贴图数据
               $name=substr($filename, 0, strrpos($filename, '_'));
               $textureMaker = substr($filename, strrpos($filename, '_') + 1, strrpos($filename, '.')-strrpos($filename, '_')-1);
               $nameArray = explode('_', $name);
               $texName = $nameArray[0];//贴图名称
               $texture = Texture::model()->findByAttributes(array('name'=>$texName));
               if(empty($texture))
                   $texture = new Texture();
               if(in_array($nameArray[4], $singlePic))
                {
                     $column = Yii::app()->params['imgColumn'][$nameArray[4]];//获取字段名
                     if(!empty($texture))
                     {
                          //编辑贴图
                          $this->log('update','info','texture.txt');
                          if(!empty($texture->$column)){
                             //删除原来的图片,正式的脚本不用删除缩略图,在上传成功之后再删除，记录中successFile中
                             $handleSuccess=fopen($this->root.'/'.$this->textureSuccessFile, 'a');//追加方式
                             fwrite($handleSuccess, $root.$texture->$column."\r\n");
                             fclose($handleSuccess);
                          }
                     }
                     $texture->$column = $filePath;
                }else{
                     $texTypeArr = explode('-', $nameArray[4]);
                     $column = Yii::app()->params['imgColumn'][$texTypeArr[0]];//获取字段名
                     $oldTex = array();
                     if(!empty($texture))
                     {
                         //编辑贴图
                         $this->log('update','info','texture.txt');
                         $TexImgUpdate = $texture->$column;
                         $oldTex = json_decode($texture->$column,true);
                         if(count($texTypeArr)>1 && !empty($oldTex)){
                             $TexImgUpdate = null;
                             if(isset($oldTex[$texTypeArr[1]]))
                                 $TexImgUpdate = $oldTex[$texTypeArr[1]];
                         }
                         if(!empty($TexImgUpdate)){
                            //删除原来的图片,正式的脚本不用删除缩略图,在上传成功之后再删除，记录中successFile中
                            $handleSuccess=fopen($this->root.'/'.$this->textureSuccessFile, 'a');//追加方式
                            fwrite($handleSuccess, $root.$TexImgUpdate."\r\n");
                            fclose($handleSuccess);
                         }
                     }
                     $UpImgFile = $filePath;
                     if(count($texTypeArr)>1){
                         $oldTex[$texTypeArr[1]] = $filePath;
                         $UpImgFile = json_encode($oldTex);
                     }
                     $texture->$column = $UpImgFile;
                }
                $texture->name = $nameArray[0];
                //颜色
                if(!empty($nameArray[1])){
                    $colorsObj = new COLORS();
                    $colors = $colorsObj->colorsControl();
                    $colorArray = explode('|', $colors[$nameArray[1]]);
                    $texture->color_name = $colorArray[0];
                    $texture->color_value = $colorArray[1];
                }
                //长宽高
                $lwh = explode('-', $nameArray[2]);
                $texture->length = $lwh[0];
                $texture->width = $lwh[1];
                $texture->height = $lwh[2];
                //是否透明通道
                $isAlpha = array('Y'=>1,'N'=>0);
                if(!in_array($nameArray[4], $singlePic))
                        $texture->alpha = $isAlpha[$nameArray[3]];
                $texture->maker = $textureMaker;
                if($texture->save()){
                    $textureNameArray = explode('-', $nameArray[0]);
                    $mold = Mold::model()->findByPk($textureNameArray[1],'t.is_del=0');
                    if(empty($mold))
                        throw new CHttpException(404,'id为'.$textureNameArray[1].'的模型不存在或者已经删除！.');
                    $texture_ids = json_decode($mold['texture_id'],true);
                    if(empty($mold['texture_id']) || empty($texture_ids))
                        $texture_ids = array();
                    if(!in_array($texture->id, array_keys($texture_ids))){
                        $texture_ids[$texture->id] = count($texture_ids) + 1;
                        $mold->texture_id = json_encode($texture_ids);
                        $mold->save();
                    }
                    //修改模型的透视图和顶视图
                    if(in_array($nameArray[4], $singlePic)){
                        $mold->$column = $filePath;
                        $mold->save();
                    }
                }
                return $filePath;
            } catch (Exception $e) {
               throw new CHttpException(500,$e->getMessage());
           }
        }
    }