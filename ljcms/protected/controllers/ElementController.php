<?php
	/**
	 * 
	 * @author zhangyong
	 * @ps:元素管理  2014/10/25 更新
	 */
	class ElementController extends CmsController
	{
        private $root='/mnt/static/elements/';//source的上级目录，放置待上传元素和备份元素图片
//        private $root='D:/www/static/elements';//测试时使用
		private $errorFile='autoCreateElementError.txt';//发生错误，用于回滚用的文件
		private $successFile='autoCreateElementSuccess.txt';//上传成功后，用于删除作废图片的文件
        
		/**
		 * 元素列表
		 * @author zhangyong
		 */
		public function actionIndex()
		{
            $like = '';//查询元素条件
            $name=null;
            $status=5;//5=已审核，2=未审核，3=审核不通过
            $params=$_GET;
            $seachData = array('name'=>'','status'=>'','timeStart'=>'','timeEnd'=>'');
            if(isset($params['name']) && !empty($params['name'])){
                $like = " and name like '%".trim($params['name'])."%'";
                $seachData['name']=$params['name'];
            }
            if(isset($params['status']) && !empty($params['status'])){
                $status=$params['status'];
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
            $criteria=new CDbCriteria();
            $criteria->condition = 'is_del=0'.$like.' order by create_time desc';
            if($status!=5){
                $like .=' and status='.$status;
                $criteria->condition = 'is_del=0'.$like.' order by create_time desc';
                $ul=$this->findAll('ElementTemp',$criteria,array('page'=>true));
            }else{
                $ul=$this->findAll('Element',$criteria,array('page'=>true));
            }  
            $this->render('index',array('ul'=>$ul,'seachData'=>$seachData,'status'=>$status));
        }
        
        /**
		 * 批量审核元素
		 * @author zhangyong
		 */   
        function actionCheckElement(){
            $connection=Yii::app()->db;
            $data['status']=false;
            $upYunData = array();
            $num=null;//用于计算通过审核的元素
            try{
                if(isset($_POST['id']) && !empty($_POST['id'])){
                    $transaction=$connection->beginTransaction();
                    $idArray=$_POST['id'];
                    foreach ($idArray as $id){
                        $elementData=  ElementTemp::model()->findByAttributes(array('id'=>$id,'is_del'=>0));
                        if(!empty($elementData)){
                            $model=Element::model()->findByAttributes(array('name'=>$elementData['name'],'is_del'=>0));
                            if(empty($model)){//创建元素
                                $model=new Element();
                                $model->isNewRecord = TRUE;
                            }
                            $model->name=$elementData['name'];
                            $model->image=$elementData['image'];
                            $model->pics=$elementData['pics'];
                            $model->pics_night=$elementData['pics_night'];
                            $model->type=$elementData['type'];
                            $model->summary=$elementData['summary'];
                            $model->category_id=$elementData['category_id'];
                            $model->label_id=$elementData['label_id'];
                            $model->brand_id=$elementData['brand_id'];
                            $model->rank=$elementData['rank'];
                            $model->mold_id=$elementData['mold_id'];
                            $model->brandhall_id=$elementData['brandhall_id'];
                            if($model->save()){
                                //记录已审核元素的图片数据
                                $imgSync = array($model->pics,$model->pics_night);
                                foreach ($imgSync as $img){
                                    $upFile = json_decode($img,true);
                                    if(!empty($upFile)){
                                        foreach ($upFile as $uf){
                                            if(!empty($uf)){
                                                foreach ($uf as $uimg){
                                                    if(!in_array($uimg, $upYunData))
                                                        array_push ($upYunData, $uimg);
                                                }
                                            }
                                        }
                                    }
                                }
                                $ENameArray=explode('_', $model->name);
                                //存储没有模型元素的缩略图，供上传云盘使用
                                if($ENameArray[1] == 'N' && !in_array($model->image, $upYunData))
                                        array_push ($upYunData, $model->image);
                                //关联层级表
                                $nodeName=$ENameArray[5];
                                $spaceIds=array();//记录该元素关联的空间，由tbl_element_temp中的layer得来
                                if(!empty($elementData['layer'])){
                                    $sals=  json_decode($elementData['layer'],true);
                                    $spaceIds=  array_keys($sals);
                                    foreach ($sals as $s=>$als){
                                        if(!empty($als)){
                                            foreach ($als as $a=>$l){
                                                //判断是否创建层级数据
                                                $existNode=Node::model()->findByAttributes(array('space_id'=>$s,'layer'=>json_encode(array($a=>$l)),'type'=>$model->type));
                                                if(empty($existNode)){
                                                    $nodeModel=new Node();
                                                    $nodeModel->space_id=$s;
                                                    $nodeModel->layer=  json_encode(array($a=>$l));
                                                    $nodeModel->name=$nodeName;
                                                    $nodeModel->type=$model->type;
                                                    $nodeModel->save();
                                                }
                                                $elementLayer = ElementLayer::model()->find("element_id=".$model->id." and space_id=".$s." and angle='".$a."' and layer='".$l."'");
                                                if(empty($elementLayer)){
                                                    $ElementLayer = new ElementLayer();
                                                    $ElementLayer->element_id = $model->id;
                                                    $ElementLayer->space_id = $s;
                                                    $ElementLayer->angle = $a;
                                                    $ElementLayer->layer = $l;
                                                    $ElementLayer->name = $nodeName;
                                                    $ElementLayer->save();
                                                }
                                            }
                                        }
                                        //顺便关联房间功能，即表tbl_element_room_category
                                        $spaceData=  Space::model()->find(array('select'=>'room_category','condition'=>'is_del=0 and id='.$s));
                                        if(empty($spaceData)){
                                            throw new CHttpException(404,'id为'.$s.'的空间不存在或者已经删除！.');
                                        }else{
                                            $elementRC = ElementRoomCategory::model()->find("element_id=".$model->id." and room_category=".$spaceData['room_category']);
                                            if(empty($elementRC)){
                                                $ElementRoomCategory = new ElementRoomCategory();
                                                $ElementRoomCategory->element_id = $model->id;
                                                $ElementRoomCategory->room_category = $spaceData['room_category'];
                                                $ElementRoomCategory->save();
                                            }
                                            //空间自动绑定元素
                                            $spaceElement = SpaceElementRelation::model()->find('space_id='.$s.' and element_id='.$model->id.' and room_category='.$spaceData['room_category']);
                                            if(empty($spaceElement)){
                                                $SpaceElementRelation = new SpaceElementRelation();
                                                $SpaceElementRelation->space_id = $s;
                                                $SpaceElementRelation->element_id = $model->id;
                                                $SpaceElementRelation->room_category = $spaceData['room_category'];
                                                $SpaceElementRelation->save();
                                            }
                                        }
                                    }
                                }
                                //关联风格
                                if(!empty($elementData['style_id'])){
                                    ElementStyleRelation::model()->deleteAll("element_id=".$model->id);
                                    $styles=  explode(',', $elementData['style_id']);
                                    foreach ($styles as $style){
                                        $ElementStyleRelation = new ElementStyleRelation();
                                        $ElementStyleRelation->element_id = $model->id;
                                        $ElementStyleRelation->style_id = $style;
                                        $ElementStyleRelation->save();
                                    }
                                }
                                //关联材质
                                if(!empty($elementData['material_id'])){
                                    ElementMaterialRelation::model()->deleteAll("element_id=".$model->id);
                                    $materials=  explode(',', $elementData['material_id']);
                                    foreach ($materials as $material){
                                        $ElementMaterialRelation = new ElementMaterialRelation();
                                        $ElementMaterialRelation->element_id = $model->id;
                                        $ElementMaterialRelation->material_id = $material;
                                        $ElementMaterialRelation->save();
                                    }
                                }
                                
                                if(!empty($model->mold_id)){
                                    //存储tbl_render,表示模型已经渲染成功
                                    if(!empty($spaceIds)){
                                        Render::model()->deleteAll("mold_id=".$model->mold_id." and space_id in (". implode(',', $spaceIds) .")");
                                        foreach ($spaceIds as $space_id){
                                            $Render = new Render();
                                            $Render->mold_id = $model->mold_id;
                                            $Render->space_id = $space_id;
                                            $Render->save();
                                        }
                                    }
                                    //获取与该模型关联的商品
                                    Element::model()->_PE($model->id,$model->mold_id);
                                }
                                
                                //审核通过后修改临时元素表的status
                                $elementData->status=5;//审核通过,已写入元素表
                                $elementData->save();
                                $num += 1;
                            }else{
                                $elementData->status=3;//审核不通过
                                $elementData->save();
                            }
                        }    
                    }
                    //图片上传到云盘
                    if(!empty($upYunData) && Yii::app()->params['upYun']){
                        foreach ($upYunData as $uyd){
                            $fileHelper=new FileHelper;
                            $fileHelper->subFolder='element';
                            $fileHelper->uploadUpYun($uyd);
                        }
                    }
                    $transaction->commit();
                    
                    if(count($idArray)==$num){
                        $data['status']=true;
                        echo CJSON::encode($data);
                    }
                }
            }  catch (Exception $e) {
                //出错时回滚数据
                isset($transaction) && $transaction->rollback();
                throw new CHttpException(500,$e->getMessage());//测试时使用
            }
        }
        
         /**
		 * 删元素操作（status区分已审核和未审核元素）
		 * @author zhangyong
		 */
        public function actionDelete()
        {
            $result =array('status'=>false,'info'=>'删除元素失败！') ;
            if(isset($_POST['status']) && !empty($_POST['status'])){
                $model=intval($_POST['status'])==2 ? 'ElementTemp':'Element';
            }
            if(isset($_POST['id']) && !empty($_POST['id'])){
                $id = intval($_POST['id']);
                $model = $model::model()->findByPk($id);
                $model->is_del = 1;
                if($model->save()){
                    $result =array('status'=>TRUE,'info'=>'删除元素成功！') ;
                     $result['id'] = $model->id;
                 }
            }
            echo CJSON::encode($result);
        }
        
		/**
		 * 家具(JJ)、硬装(YZ)、电器(DQ)、配饰(PS)、热点元素(H)
		 * 根据图片自动生成元素，写入元素临时表，审核通过后才能写入元素表
         * 
		 * 有模型元素
         * 
         * 单视角情况下的命名规则
         * 如果没有颜色，材质，则以0填充
         * 分类_有模型_空间名称_空间视角_层级_层级名称_模型名称_颜色-颜色_材质-材质_公用or私用_制作人_图片用途.ext
         * JJ_Y_KT0002_A_01_TH_20141117N1-GMAX_HS_MZ_G_YH_D.png	    搭配图
         * JJ_Y_KT0002_A_01_TH_20141117N1-GMAX_HS_MZ_G_YH_H.png	    热区图
         * 有模型元素的多视角(必须是同一个元素的多视角)
         * 如果没有颜色，材质，则以0填充
         * 未上传元素空间视角_未上传元素层级&分类_有模型_空间名称_已上传元素空间视角_层级_层级名称_模型名称_颜色-颜色_材质-材质_公用or私用_制作人_图片用途.ext
         * B_01&JJ_Y_KT0002_A_01_TH_20141117N1-GMAX_HS_MZ_G_YH_D.png	  搭配图
         * B_01&JJ_Y_KT0002_A_01_TH_20141117N1-GMAX_HS_MZ_G_YH_H.png	  热区图
         * 
         * 无模型元素
         * 
         * 单视角情况下的命名规则
         * 如果没有颜色，材质，则以0填充
         * 分类_无模型_空间名称_空间视角_层级_层级名称_颜色-颜色_材质-材质_公用or私用_制作人_图片用途.ext
         * JJ_N_KT0002_A_01_DT_HS_MZ_G_LSQ_S.jpg		缩略图
         * JJ_N_KT0002_A_01_DT_HS_MZ_G_LSQ_D.png		搭配图
         * JJ_N_KT0002_A_01_DT_HS_MZ_G_LSQ_H.png		热区图
         * 无模型元素的多视角(必须是同一个元素的多视角)
         * 未上传元素空间视角_未上传元素层级&分类_无模型_空间名称_已上传元素空间视角_层级_层级名称_颜色-颜色_材质-材质_公用or私用_制作人_图片用途.ext
         * B_01&JJ_N_KT0002_A_01_DT_HS_MZ_G_LSQ_S.jpg		缩略图
         * B_01&JJ_N_KT0002_A_01_DT_HS_MZ_G_LSQ_D.png		搭配图
         * B_01&JJ_N_KT0002_A_01_DT_HS_MZ_G_LSQ_H.png		热区图
		 * 
		 * 有模型元素的缩略图使用模型的缩略图
		 * @todo:记录日志，后期考虑单个元素对应多空间的情况
         * @author zhangyong
		 */
		public function actionAutoCreate()
		{
            ini_set("max_execution_time", 3600);  //用此function才能真正在运行时设置
            try{
				ignore_user_abort();//关掉浏览器，PHP脚本也可以继续执行.
				//正式机元素路径
 				$source=$this->root."/source";
 				$backup=$this->root."/backup";
				
				$connection=Yii::app()->db;
				$transaction=$connection->beginTransaction();
				$files=scandir($source);
				$filenames=array();//记录文件名，用于失败时的回滚
				foreach ($files as $file)
				{
					if('.'==$file || '..'==$file || is_dir($file))
						continue;
					$filename=basename($file);
					if(false===strpos($filename,'_') || false===strpos($filename, "."))
						continue;
                    $pt=strrpos($filename, ".");
                    $fileForm = substr($filename, $pt+1, strlen($filename) - $pt);//获取文件后缀
                    $fileForm = strtolower($fileForm);//把后缀名转换成小写
                    $FName = substr($filename, 0, $pt);//获取文件的名称(不含扩展名)
                    $newFileName = $FName.'.'.$fileForm;//组合成最新的元素名称（目的让图片后缀全部变成小写）
					$filenames[]=$newFileName;
					if(rename($source.'/'.$file, $backup.'/'.$newFileName))
					{
						$name=substr($filename, 0, strrpos($filename, '_'));
						$prepared=$this->_prepareAutoCreate($name);
						if($prepared)
						{//已经准备好生成一个元素所需的图片
                            if(!isset($name) || empty($name)) continue;
							$element=$this->_autoCreateElement($name,$backup);
							$this->_finishAutoCreate($name);
						}
					}
				}
				$transaction->commit();
				//上传成功后，删除作废的元素图片
				if(file_exists($this->root.'/'.$this->successFile))
				{
					$successFileMessage=file_get_contents($this->root.'/'.$this->successFile);
					$arr=explode("\r\n", $successFileMessage);
                    if(count($arr)>2){
                        unset($arr[count($arr)-1]);
                        foreach ($arr as $item)
                        {
                            $temp=explode('::', $item);
                            $this->deleteImage($temp[1]);
                        }
                    }
					unlink($this->root.'/'.$this->successFile);
				}
				if(file_exists($this->root.'/'.$this->errorFile))
					unlink($this->root.'/'.$this->errorFile);
				echo "success";
			}catch(Exception $e){
				$transaction->rollback();
				foreach ($filenames as $filename)
					if(file_exists($backup.'/'.$filename))
						rename($backup.'/'.$filename,$source.'/'.$filename);
				//回滚的时候，查询autoCreateElementError.txt，删除记录的图片
				if(file_exists($this->root.'/'.$this->errorFile))
				{
					$errorFileMessage=file_get_contents($this->root.'/'.$this->errorFile);
					$arr=explode("\r\n", $errorFileMessage);
					if(count($arr)>2){
						unset($arr[count($arr)-1]);
                        foreach ($arr as $item)
                        {
                            $temp=explode('::', $item);
                            $this->deleteImage($temp[1]);
                        }
                    }
					unlink($this->root.'/'.$this->errorFile);//脚本结束的时候是否要自动删除该文件
				}
				if(file_exists($this->root.'/'.$this->successFile))
					unlink($this->root.'/'.$this->successFile);
				throw new CHttpException(500,$e->getMessage());
			}
        }
        
        /**
		 * 准备生成元素
         * @PS：有模型的上传两张图片，没有模型的上传三张图片
		 * @author zhangyong
		 */
        private function _prepareAutoCreate($name)
		{
			//查找是否有未审核的该元素数据，并计算其上传的张数
			$model=ElementPrepare::model()->findByAttributes(array('name'=>$name,'status'=>2));
			if(empty($model))
			{
				$model=new ElementPrepare();
				$model->name=$name;
				$model->num=1;
				$model->status=2;//2=未生成元素，生成元素之后要把此值改为4
			}
			else
				$model->num++;
			$model->save();
			//有模型的元素上传两张图片，没有模型的元素上传三张图片
            if(strpos($name,'&'))
                    $name = substr($name, strrpos($name, '&') + 1);//获取文件名称（去除双视角信息）
            $nameArray=  explode('_', $name);
            if($nameArray[1]=='Y'){
                return 2==$model->num;//有模型的元素
            }else{
                return 3==$model->num;//没有模型的元素
            }
		}
        
        /**
		 * 生成元素之后，改变准备准备生成元素的状态
		 * @author zhangyong
		 */
        private function _finishAutoCreate($name)
		{
			$model=ElementPrepare::model()->findByAttributes(array('name'=>$name,'status'=>2));
			$model->status=4;
			$model->update();
		}
        
        /**
		 * 自动创建或更新元素
		 * @param 元素名称 $name
		 * @param 渲染图片的备份路径 $folder
		 * @todo:发生异常的时候要删除已经copy到element文件夹的图片
		 */
		private function _autoCreateElement($name,$folder)
		{
			try{
                $hasMold = false;//默认元素是没有模型的
                $isMoreAngle = false;//默认单视角
                $EName = $name;//$EName为存储的元素名称
                $moreAngle = array();//多视角信息
                if(strpos($name,'&')){
                    $isMoreAngle = true;//多视角
                    $EName = substr($name, strrpos($name, '&') + 1);
                    $moreAngle = explode('_',substr($name, 0,strrpos($name, '&')));
                }
				$nameArray=explode("_", $EName);
                //判断模型及空间是否存在
                if($nameArray[1] == 'Y'){
                    $hasMold = true;//元素有模型
                    $moldName = $nameArray[6];
                    $moldData = Mold::model()->with('materials','styles')->findByAttributes(array('is_del'=>0),"(t.name='".$moldName."' or t.item='".$moldName."')");
                    if(empty($moldData))
                        throw new CHttpException(404,'名称为'.$moldName.'的模型不存在或者已经删除！.');
                }
                $spaceName = $nameArray[2];
                $spaceData = Space::model()->findByAttributes(array('name'=>$spaceName,'is_del'=>0)); 
                if(empty($spaceData))
                    throw new CHttpException(404,'名称为'.$spaceName.'的空间不存在或者已经删除！.');
				//元素图片保存路径
				$root=Yii::app()->params['realPathOfStatic'];
				$dest='/upload/element/'.date('Y/m/d').'/';
				if(!is_dir($root.$dest))
				{
					if(!mkdir($root.$dest, 0777, true))
						throw new CHttpException(500,'创建文件夹失败...');
				}
				//创建和更新的时候都必须是全部的图片（不接受单张替换）
				$picD=$folder.'/'.$name.'_D.png';//搭配图
				$picH=$folder.'/'.$name.'_H.png';//热区图
				$dapei=uniqid().'.png';
				$hot=uniqid().'.png';
                //copy发生错误该如何处理
				if(false===copy($picD,$root.$dest.$dapei))
					throw new CHttpException(500,'copy失败...');
				if(false===copy($picH,$root.$dest.$hot))
					throw new CHttpException(500,'copy失败...');
                if(!$hasMold){
                    $picS=$folder.'/'.$name.'_S.jpg';//硬装的缩略图
                    $image=uniqid().'.jpg';
                    if(false===copy($picS,$root.$dest.$image))
                            throw new CHttpException(500,'copy失败...');
                }
				//记录已经复制到element目录下的图片，用于在上传失败时回滚
				$handleError=fopen($this->root.'/'.$this->errorFile, 'a');//追加方式
				fwrite($handleError, 'dapei::'.$root.$dest.$dapei."\r\n");
				fwrite($handleError, 'hot::'.$root.$dest.$hot."\r\n");
                if(!$hasMold){
                    fwrite($handleError, 'image::'.$root.$dest.$image."\r\n");
                }
				fclose($handleError);
                //查找该元素是否已存在
				$model=ElementTemp::model()->findByAttributes(array('name'=>$EName,'is_del'=>0));
                //当前要处理的视角及层级信息
                if($isMoreAngle){//多视角
                    $angle = $moreAngle[0];//视角
                    $node = $moreAngle[1];//层级
                }else{//单视角
                    $angle = $nameArray[3];
                    $node = $nameArray[4];
                }
				if(empty($model))
				{//创建元素
					$this->log('create','info','element.txt');
					$model=new ElementTemp();
					$model->name=$EName;
                    $model->isNewRecord = TRUE;
				}
				else 
				{//编辑元素
					$this->log('update','info','element.txt');
					$delPics=json_decode($model->pics,true);
					//删除原来的图片,正式的脚本不用删除缩略图,在上传成功之后再删除，记录中successFile中
					$handleSuccess=fopen($this->root.'/'.$this->successFile, 'a');//追加方式
                    if(isset($delPics[$angle]))
                    {
                        fwrite($handleSuccess, 'dapei::'.$root.$delPics[$angle]['dapei_pic']."\r\n");
                        fwrite($handleSuccess, 'hot::'.$root.$delPics[$angle]['hot_pic']."\r\n");
                    }
                    if(!$hasMold && !empty($model->image)){
                        fwrite($handleSuccess, 'image::'.$root.$model->image."\r\n");
                    }
					fclose($handleSuccess);
				}
                //根据$name对比元素的相关数据
                $layer=json_decode($model['layer'],true);
                if(empty($model['layer']) || empty($layer))
                    $layer = array();
                $layer[$spaceData['id']][$angle] = $node;
                $model->layer=json_encode($layer);
                $model->type=Yii::app()->params['namwPType'][$nameArray[0]];
                if($hasMold){
                    $model->category_id=$moldData['category_id'];
                    $model->label_id=$moldData['label_id'];
                    $model->brand_id=$moldData['brand_id'];
                    $model->mold_id=$moldData['id'];
                    $model->image=$moldData['image'];//缩略图
                    $model->brandhall_id=$moldData['brandhall_id'];//定制生产此元素的品牌馆id
                    if(!empty($moldData['styles'])){
                        foreach ($moldData['styles'] as $st){
                            $styleTemp[]=$st['id'];
                        }
                        $model->style_id=implode(',', $styleTemp);
                    }
                    if(!empty($moldData['materials'])){
                        foreach ($moldData['materials'] as $ms){
                            $materialTemp[]=$ms['id'];
                        }
                        $model->material_id=implode(',', $materialTemp);
                    }
                }
                $picsData=json_decode($model['pics'],true);
                if(empty($model['pics']) || empty($picsData))
                    $picsData = array();
                $picsData[$angle]=array('dapei_pic'=>$dest.$dapei,'hot_pic'=>$dest.$hot);
				$model->pics=json_encode($picsData);//flash使用图
                if(!$hasMold)
                    $model->image=$dest.$image;//缩略图
                $model->status=2;//未审核
				$model->save();
				return $model;
			}catch(Exception $e){
				throw new CHttpException(500,$e->getMessage());
			}
		}
        
        /**
		 * 加载选中的元素
		 * @auth zhangyong
		 */
		public function actionDealEids()
		{
            $data = array();
            if(isset($_POST['eles'])){
                $data = $_POST['eles'];
            }
            echo CJSON::encode($data);
        }
        
        /**
		 * 加载弹框空的模型（包含索引模型）
		 * @auth zhangyong
		 */
		public function actionLoadMold()
		{
            $con = '';
            $params = $_POST;
            if(isset($params['name']) && isset($params['type']) && isset($params['eids'])){
                $name = trim($params['name']);
                $type = $params['type'];
                $eids = $params['eids'];
                if(!empty($name))
                    $con .= " and ( name like '%".$name."%' or item like '%".$name."%')";
                    
                if($type == 'bind'){
                    $con .= " and id not in (select mold_id from tbl_element where id in (".$eids.") )";
                }else{
                    $con .= " and id in (select mold_id from tbl_element where id in (".$eids.") )";
                }
                //获取空间数据
                $mold=  Mold::model()->findAll(array('select'=>'id,name,item,image',
                'condition'=>'is_del=0'.$con,
                'order'=>'create_time desc',
                ));
                
                $this->renderPartial('loadMold',array('mold'=>$mold,'eids'=>$eids));    
            }
        }
        
        /**
		 * 元素绑定模型
		 * @auth zhangyong
		 */
		public function actionBindMold()
		{
            $data = array('status'=>false,'info'=>'操作失败！');
            $connection = Yii::app()->db;
            $params = $_POST;
            try{
                $transaction=$connection->beginTransaction();
                if(isset($params['eids']) && !empty($params['mid']) && isset($params['type'])){
                    $mold = Mold::model()->findByPk($params['mid'],'t.is_del=0');
                    if(empty($mold))
                        throw new CHttpException(500,'id为'.$params['mid'].'的模型不存在或者已经删除！');
                    if(!empty($params['eids']))
                        $data = Element::model()->_EMP(array('eids'=>$params['eids'],'mid'=>$params['mid'],'type'=>$params['type']));
                }
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollback();
                throw new CHttpException(500,$e->getMessage());//测试时使用
            }
            
            echo CJSON::encode($data);
        }
    }