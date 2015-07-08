<?php
	/**
	 * 
	 * @author zhangyong
	 * @ps:标签管理  2014/11/11
	 */
	class LabelController extends CmsController
	{
        /**
		 * 素材标签列表
         * @PS:
		 * @author zhangyong
		 */
		public function actionIndex()
		{
            $record = array();
            $params=$_GET;
            $record['name'] = '';
            $like = '';
            if(isset($params['name']) && !empty($params['name'])){
                $like .= " and ( children.name like '%".trim($params['name'])."%' or t.name like '%".trim($params['name'])."%' )";
                $record['name']=$params['name'];
            }
            $criteria=new CDbCriteria();
            $criteria->with=array(
                'children'=>array(
                    'on'=>'children.is_del=0'.$like,
                )
            );
            $criteria->condition='t.parent_id=0 and t.is_del=0'.$like;
            $criteria->order='t.sort_num asc';
            $parent = Label::model()->findAll($criteria);
            $record['parent'] = $parent;
            //获取分类
            $record['categorys'] = Category::model()->findAll(array('select'=>'id,name','condition'=>'is_show=1'));
            if(!empty($record['categorys']))
                $record['categorys'] = CHtml::listData($record['categorys'],'id','name');
            $this->render('index',$record);
        }
        
        /**
		 * 创建/编辑素材标签
         * @PS:
		 * @author zhangyong
		 */
		public function actionCreate()
		{
            $record = array();
            $connection=Yii::app()->db;
            $params = $_POST;
            $record['model'] = new Label();
            $record['pid'] = array();
            if(isset($_GET['id']) && !empty($_GET['id'])){
                //编辑标签是获取该标签信息
                $record['model'] = Label::model()->findByPk($_GET['id']);
                if(empty($record['model']))
                    throw new CHttpException(404,'该标签不存在或者已经删除！');
                if(!empty($record['model']['parent_id'])){
                    $parentType = Label::model()->findByPk($record['model']['parent_id']);
                    if(empty($parentType))
                        throw new CHttpException(404,'该标签的父级不存在或者已经删除！');
                    $parents = Label::model()->findAll('parent_id=0 and is_del=0 and type='.$record['model']['type']);
                    if(!empty($parents))
                        $record['pid'] = CHtml::listData($parents,'id','name');
                }
            }
            try{
                if(isset($params['Label'])){
                    //存储/编辑标签
                    $transaction=$connection->beginTransaction();
                    $label = $params['Label'];
                    $record['model']->attributes=$label;
                    if(!isset($_GET['id']))
                        $record['model']->isNewRecord = TRUE;
                    $record['model']->save();
                    
                    $transaction->commit();
                    $this->redirect('/label/index');
                }
            } catch (Exception $e) {
                $transaction->rollback();
                throw new CHttpException(500,$e->getMessage());//测试时使用
            }
            $this->render('create',$record);
        }
        
        /**
		 * 删除素材标签
         * @PS:
		 * @author zhangyong
		 */
		public function actionDelLabel()
		{
            $params = $_POST;
            $data = array('status'=>false,'info'=>'删除失败！');
            if(isset($params['laid'])){
                $label = Label::model()->findByPk($params['laid']);
                if(empty($label['parent_id'])){
                    Label::model()->updateAll(array('is_del'=>'1'),'parent_id='.$params['laid']);
                }
                $label->is_del=1;
                if($label->save())
                    $data = array('status'=>true,'info'=>'删除成功！');
            }
            echo CJSON::encode($data);
        }
        
        /**
		 * 获取某类型的父级标签
         * @param string type 标签类别
		 * @author zhangyong
		 */
		public function actionGetLabel()
		{
            $params=$_POST;
            if(isset($params['type']) && intval($params['type'])>0){
                $parents = Label::model()->findAll('parent_id=0 and is_del=0 and type='.$params['type']);
                if(!empty($parents))
                    $parents = CHtml::listData($parents,'id','name');
                echo CHtml::tag('option',array('value'=>'0'),CHtml::encode('默认顶级分类'));
                foreach ($parents as $key=>$value)
                {
                    echo CHtml::tag('option',array('value'=>$key),CHtml::encode($value));
                }
            }else{
                echo CHtml::tag('option',array('value'=>'0'),CHtml::encode('默认顶级分类'));
            }
        }
        
        /**
		 * 获取某类型的父级标签
         * @param string pid 一级标签id
		 * @author zhangyong
		 */
		public function actionGetLabelChild()
		{
            $params=$_POST;
            if(isset($params['pid']) && intval($params['pid'])>0){
                $parents = Label::model()->findAll('is_del=0 and parent_id='.$params['pid']);
                if(!empty($parents))
                    $parents = CHtml::listData($parents,'id','name');
                echo CHtml::tag('option',array('value'=>'0'),CHtml::encode('请选择标签分类'));
                foreach ($parents as $key=>$value)
                {
                    echo CHtml::tag('option',array('value'=>$key),CHtml::encode($value));
                }
            }else{
                echo CHtml::tag('option',array('value'=>'0'),CHtml::encode('请选择标签分类'));
            }
        }
        
        /**
		 * 检索商品
		 * @author zhangyong
		 */
		public function actionResSearchProduct()
		{
            $con = '';
            $params = $_POST;
            if(isset($params['name']) && isset($params['type']) && isset($params['rid']) && isset($params['pm'])){
                $name = trim($_POST['name']);
                $type = $params['pm'];
                if(!empty($name)){
                    if($params['pm'] == 'product'){
                        $con .= " and ( product_name like '%".$name."%' or product_sn like '%".$name."%')";
                    }else{
                        $con .= " and ( name like '%".$name."%' or item like '%".$name."%')";
                    }
                }
                    
                if($params['type'] == 'bind'){
                    $info = Info::model()->findByPk($params['rid']);
                    //如果加品牌馆条件，经销商则绑定不到厂商的商品，故注释掉
//                    if(!empty($info['brandhall_id']))
//                        $con .=' and brandhall_id='.$info['brandhall_id'];
                    if($params['pm'] == 'product'){
                        $con .= " and product_id not in (select product_id from tbl_info_product_relation where info_id=".$params['rid'].")";
                    }else{
                        $con .= " and id not in (select mold_id from tbl_info_mold_relation where info_id=".$params['rid'].")";
                    }
                }else{
                    if($params['pm'] == 'product'){
                        $con .= " and product_id in (select product_id from tbl_info_product_relation where info_id=".$params['rid'].")";
                    }else{
                        $con .= " and id in (select mold_id from tbl_info_mold_relation where info_id=".$params['rid'].")";
                    }
                }
                //获取商品及模型数据
                if($params['pm'] == 'product'){
                    $productData=  Product::model()->findAll(array('select'=>'product_id,product_name,product_img',
                    'condition'=>'is_delete=0 and is_show=1 and parent_id=0'.$con,
                    'order'=>'add_time desc',
                    ));
                }else{
                    $productData=  Mold::model()->findAll(array('select'=>'id,name,item,image',
                    'condition'=>'is_del=0'.$con,
                    'order'=>'create_time desc',
                    ));
                }
                
                $this->renderPartial('productTemp',array('productData'=>$productData,'type'=>$type));    
            }
        }
        
        /**
		 * 素材绑定商品/模型
		 * @author zhangyong
		 */
		public function actionRProduct()
		{
            $data = array('status'=>false,'info'=>'操作失败！');
            $params = $_POST;
            $connection=Yii::app()->db;
            if(isset($params['rid']) && isset($params['selectArray']) && isset($params['type']) && isset($params['pm'])){
                try{
                    $transaction=$connection->beginTransaction();
                    $with=array(
                        'molds'=>array(),
                        'products'=>array(
                            'on'=>'products.parent_id=0'
                        )
                    );
                    $res = Info::model()->with($with)->findByPk($params['rid'],'t.is_del=0');
                    if(empty($res))
                        throw new CHttpException(500,'id为'.$params['rid'].'的素材不存在或者已经删除！');
                    if($params['pm'] == 'product'){
                        Info::model()->_IPME(array('type'=>$params['type'],'model'=>'Product','info'=>$res,'select'=>$params['selectArray']));//必须放在此处
                        $products = Product::model()->findAll(array(
                            'select'=>'product_id,brandhall_id',
                            'condition'=>'is_delete=0 and (product_id='.$params['selectArray'][0].' or parent_id='.$params['selectArray'][0].')',
                        ));
                        if(!empty($products)){
                            InfoProductRelation::model()->deleteAll("info_id=".$res['id']);
                            if($params['type'] == 'bind'){
                                foreach ($products as $pd){
                                    $InfoProductRelation = new InfoProductRelation();
                                    $InfoProductRelation->info_id = $res['id'];
                                    $InfoProductRelation->product_id = $pd['product_id'];
                                    $InfoProductRelation->brandhall_id = $pd['brandhall_id'];
                                    $InfoProductRelation->save();
                                }
                            }
                        }
                        $data = array('status'=>true,'info'=>'操作成功！');
                    }else{
                        $molds = Mold::model()->findAll(
                                array(
                                    'select'=>'id,mold_type',
                                    'condition'=>'is_del=0 and id in ('. implode(',', $params['selectArray']) .')',
                                )
                        );
                        if(!empty($molds))
                            $molds = CHtml::listData($molds,'id','mold_type');
                        if($params['type'] == 'bind'){
                            foreach ($params['selectArray'] as $se){
                                $hasim = InfoMoldRelation::model()->find('info_id='.$res['id'].' and mold_id!='.$se.' and mold_type='.$molds[$se]);
                                if(!empty($hasim))
                                    InfoMoldRelation::model()->deleteAll('info_id='.$res['id'].' and mold_id='.$hasim['mold_id']);
                                $rmr = new InfoMoldRelation();
                                $rmr->info_id = $res['id'];
                                $rmr->mold_id = $se;
                                $rmr->mold_type = $molds[$se];
                                $rmr->save();
                            }
                        }else{
                            InfoMoldRelation::model()->deleteAll('info_id='.$params['rid'].' and mold_id in ('.implode(',', $params['selectArray']).')');
                        }
                        Info::model()->_IPME(array('type'=>$params['type'],'model'=>'Mold','info'=>$res,'select'=>$params['selectArray']));
                        $data = array('status'=>true,'info'=>'操作成功！');
                    }    
                    $transaction->commit();
                } catch (Exception $e) {
                    $transaction->rollback();
                    throw new CHttpException(500,$e->getMessage());//测试时使用
                }
                
            }
            echo CJSON::encode($data);
        }
        
        /**
		 * 获取真实商品分类
		 * @author zhangyong
		 */
		public function actionGetCategory()
		{
            $params = $_POST;
            $records = array('catIds'=>array(),'lid'=>'0','cats'=>array());
            if(isset($params['lid'])){
                $records['lid'] = $params['lid'];
                $label = Label::model()->findByPk($params['lid'],'t.is_del=0');
                $records['catIds'] = json_decode($label['category_id'],true);
                if(empty($label['category_id']) || empty($records['catIds']))
                    $records['catIds'] = array();
            }
            $sql = "select * from tbl_category where is_show = 1 and (brandhall_id is null or brandhall_id = 0) order by sort_num desc";
            $dataTrue = Yii::app()->db->createCommand($sql)->queryAll();
            $records['cats'] = $this->getTree($dataTrue);
            $this->renderPartial('showCategory',$records);
        }
        
        /**
		 * 绑定真实商品分类
		 * @author zhangyong
		 */
		public function actionSaveLabelCat()
		{
            $data = array('status'=>false,'info'=>'操作失败！');
            $params = $_POST;
            if(isset($params['lid']) && !empty($params['lid'])){
                $label = Label::model()->findByPk($params['lid'],'t.is_del=0');
                if(!empty($label)){
                    if(isset($params['temp'])){
                        $label->category_id = json_encode ($params['temp']);
                    }else{
                        $label->category_id = '';
                    }
                    if($label->save())
                        $data = array('status'=>true,'info'=>'操作成功！');
                }
            }
            echo CJSON::encode($data);
        }
        
        /**
        * @param array $list 要转换的结果集
        * @param string $pid parent标记字段
        * @param string $level level标记字段
        * @author xmj
        */
        public function getTree($list, $pk='id', $pid = 'parent_id', $child = 'listCate', $root = 0) {
            //创建Tree
            $tree = array();

            if (is_array($list)) {
                //创建基于主键的数组引用
                $refer = array();

                foreach ($list as $key => $data) {
                $refer[$data[$pk]] = &$list[$key];
                }

                foreach ($list as $key => $data) {
                    //判断是否存在parent
                    $parantId = $data[$pid];
                    if ($root == $parantId) {
                        $tree[] = &$list[$key];
                    } else {
                        if (isset($refer[$parantId])) {
                            $parent = &$refer[$parantId];
                            $parent[$child][] = &$list[$key];
                        }
                    }
                }
            }
            return $tree;
        }
        
    }