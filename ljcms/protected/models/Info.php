<?php

/**
 * This is the model class for table "{{info}}".
 *
 * The followings are the available columns in table '{{info}}':
 * @property string $id
 * @property string $title
 * @property string $item
 * @property string $number
 * @property string $content
 * @property string $image
 * @property string $category_id
 * @property string $label_id
 * @property string $brand_id
 * @property string $length
 * @property string $width
 * @property string $height
 * @property string $texture_id
 * @property integer $type
 * @property string $mold_condition
 * @property string $img_condition
 * @property string $furniture_pics
 * @property string $create_time
 * @property string $update_time
 * @property string $creater_id
 * @property string $updater_id
 * @property integer $status
 * @property integer $is_del
 * @property string $brandhall_id
 * @property integer $is_rotation
 */
class Info extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_info';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, image, create_time, creater_id', 'required'),
			array('type, status, is_del, is_rotation', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>100),
			array('number', 'length', 'max'=>32),
			array('item, image, texture_id, mold_condition, img_condition, furniture_pics', 'length', 'max'=>255),
			array('category_id, label_id, brand_id, create_time, update_time, creater_id, updater_id, brandhall_id', 'length', 'max'=>10),
			array('length, width, height', 'length', 'max'=>8),
			array('content', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, item, number, content, image, category_id, label_id, brand_id, length, width, height, texture_id, type, mold_condition, img_condition, furniture_pics, create_time, update_time, creater_id, updater_id, status, is_del, brandhall_id, is_rotation', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'label'=>array(self::BELONGS_TO, 'Label', 'label_id'),
            'molds'=>array(self::MANY_MANY, 'Mold', 'tbl_info_mold_relation(info_id, mold_id)','on'=>'molds.is_del=0'),
            'orders'=>array(self::MANY_MANY, 'Order', 'tbl_order_info_relation(info_id, order_id)'),
            'materials'=>array(self::MANY_MANY, 'Material', 'tbl_info_material_relation(info_id, material_id)'),
            'styles'=>array(self::MANY_MANY, 'Style', 'tbl_info_style_relation(info_id, style_id)'),
            'products'=>array(self::MANY_MANY, 'Product', 'tbl_info_product_relation(info_id, product_id)','on'=>'products.is_delete=0'),
		);
	}
    
    /**
     * 获取素材的品牌系列/风格/颜色/材质数据
     * @author zhangyong
     * $ul代表素材列表数据
     * $option['brandhalls'] 代表品牌馆数据，$option['materials'] 代表材质数据
     */
    public function findBSCM($ul,$option = array('brandhalls'=>array(),'materials'=>array()))
    {
       $connection=Yii::app()->db;
       foreach ($ul as $k=>$val){
           //品牌系列
           $ul[$k]['brandName'] = '';
           if(!empty($option['brandhalls'])){
               $brandhalls = $option['brandhalls'];
               $Bsel=array('second'=>array(),'pid'=>null,'secid'=>null);
               if(!empty($val['brand_id'])){
                   $Bsel=$this->BMsel($val['brand_id'],array('model'=>'Brand'));
                   if(empty($Bsel['secid'])){
                       if(isset($brandhalls[$Bsel['pid']]))
                           $ul[$k]['brandName'] = $brandhalls[$Bsel['pid']];
                   }else{
                       $ul[$k]['brandName'] = $brandhalls[$Bsel['pid']].'-'.$brandhalls[$Bsel['secid']];
                   }
               }
           }
           //风格
           $ul[$k]['style'] = '';
           $sql = 'select name from tbl_style as s,tbl_info_style_relation as isr,tbl_info as i '
                   . 'where i.id='.$val['id'].' and i.id=isr.info_id and isr.style_id=s.id and i.is_del=0';
           $styleName = $connection->createCommand($sql)->queryAll();
           if(!empty($styleName)){
               foreach ($styleName as $sn){
                   $ul[$k]['style'] .= $sn['name'].' ';
               }
           }
           //颜色
           $ul[$k]['color'] = array();
           $sql = 'select color_name,color_value from tbl_info_color_relation where info_id='.$val['id'].' order by color_sort asc';
           $color = $connection->createCommand($sql)->queryAll();
           if(!empty($color)){
               foreach ($color as $cl){
                   $ul[$k]['color'][$cl['color_name']] = $cl['color_value'];
               }
           }
           //材质
           $ul[$k]['material'] = '';
           if(!empty($option['materials'])){
               $materials = $option['materials'];
               $Msel=array('second'=>array(),'pid'=>null,'secid'=>null);
               $sql = 'select material_id from tbl_info_material_relation where info_id='.$val['id'];
               $material = $connection->createCommand($sql)->queryRow();
               if(!empty($material['material_id'])){
                   $Msel=$this->BMsel($material['material_id'],array('model'=>'Material'));
                   if(empty($Msel['secid'])){
                       $ul[$k]['material'] = $materials[$Msel['pid']];
                   }else{
                       $ul[$k]['material'] = $materials[$Msel['pid']].'-'.$materials[$Msel['secid']];
                   }
               }  
           }
           //是否绑定商品
           $ul[$k]['product_id'] = 0;
           $sql = 'select * from sp_product where is_delete=0 and parent_id=0 and product_id in (select product_id from tbl_info_product_relation where info_id='.$val['id'].')';
           $product = $connection->createCommand($sql)->queryRow();
           if(!empty($product)){
               $ul[$k]['product_id'] = $product['product_id'];
           }
           //是否绑定模型
           $ul[$k]['mold'] = 0;
           $sql = 'select * from tbl_info_mold_relation where info_id='.$val['id'];
           $molds = $connection->createCommand($sql)->queryAll();
           if(!empty($molds)){
               $ul[$k]['mold'] = 1;
           }
           //标签分类名称
           $ul[$k]['label'] = '';
           $infoData = Info::model()->findByPk($val['id']);
           $labels = Label::model()->findByPk($infoData['label_id']);
           if(!empty($labels['parent_id'])){
               $parents = Label::model()->findByPk($labels['parent_id']);
               if(!empty($parents)){
                   $ul[$k]['label'] = $parents['name'].'-'.$labels['name'];
               }
           }else{
               $ul[$k]['label'] = $labels['name'];
           }
       }
       return $ul;    
    }
    
    /**
     * 获取已选的品牌、材质
     * @author zhangyong
     * $id 为已保存的brand_id或者material_id
     * $params为存储model数据的数组，如:array('model'=>'Brand')
     */
    private function BMsel($id,$params)
    {
       $second=array();$pid='';$secid='';
       $data=array('second'=>$second,'pid'=>$pid,'secid'=>$secid);
       $model=trim($params['model']);
       $model=ucfirst($model);//首字母大写
       $model=new $model;
       $selData= $model->find(array('select'=>'parent_id','condition'=>'id='.$id));//存储的品牌/材质数据
       if(!empty($selData)){
           if(empty($selData['parent_id'])){
                $secondData=  $model->findAll(array('select'=>'id,name','condition'=>'parent_id='.$id));
                $second=CHtml::listData($secondData,'id','name');//二级分类数据
                $pid=$id;//父级id
                $secid='';
            }else{
                $secondData=  $model->findAll(array('select'=>'id,name','condition'=>'parent_id='.$selData['parent_id']));
                $second=CHtml::listData($secondData,'id','name');//二级分类数据
                $pid=$selData['parent_id'];//父级id
                $secid=$id;
            }
       }
       
       return $data=array('second'=>$second,'pid'=>$pid,'secid'=>$secid);
    }
    
    /**
    * 统计素材的模型及贴图数据
    * $id string 素材id
    * @author zhangyong
    */
   public function _countInfoMI($id)
   {
       $info = Info::model()->with('molds')->findByPk($id,'t.is_del=0');
       $order = Order::model()->with('infos')->find('t.is_del=0 and t.type in ('. implode(',', Yii::app()->params['allowCinfo']) .') and infos.id='.$id);
       $textures = array();
       $moldCondition = array();
       $imgCondition = array('1'=>'0','2'=>'0','3'=>'0','4'=>'0');
       if($order['type']==0){//建模素材
           if(!empty($info['molds'])){
               foreach ($info['molds'] as $mold){
                   $texture_id = json_decode($mold['texture_id'],true);
                   if(!empty($texture_id)){
                       $tids = array_keys($texture_id);
                       $textures = array_unique(array_merge($textures,$tids));
                   }
                   //处理模型类型
                   $moldCondition[$mold['mold_type']] = $mold['id'];
               }
           }
       }else{
           $texture_id = json_decode($info['texture_id'],true);
           if(!empty($texture_id)){
               $tids = array_keys($texture_id);
               $textures = array_unique(array_merge($textures,$tids));
           }
       }
       //处理贴图数量
       if(!empty($textures)){
           //透视图和顶视图
           $tex_single = Texture::model()->findAll("(image is not null or floorplan is not null ) and id in (". implode(',', $textures) .") group by image,floorplan ");
           if(!empty($tex_single)){
               foreach ($tex_single as $texs){
                   if(!empty($texs['image']))
                       $imgCondition[1]++;
                   if(!empty($texs['floorplan']))
                       $imgCondition[2]++;
               }
           }
           //UV贴图和法线图
           $tex_other = Texture::model()->findAll("(uv_map is not null or m_uv_map is not null or normal_map is not null or m_normal_map is not null ) and id in (". implode(',', $textures) .") ");
           if(!empty($tex_other)){
               foreach ($tex_other as $texo){
                   if(!empty($texo['uv_map']) || !empty($texo['m_uv_map']))
                       $imgCondition[3]++;
                   if(!empty($texo['normal_map']) || !empty($texo['m_normal_map']))
                       $imgCondition[4]++;
               }
           }
       }
       if(!empty($moldCondition))
           $info->mold_condition = json_encode ($moldCondition);
       if(!empty($imgCondition)){
           $oldIM = json_decode($info->img_condition,true);
           if(isset($oldIM[5]))
               $imgCondition[5] = $oldIM[5];
           $info->img_condition = json_encode ($imgCondition);
       }
       $info->save(); 
   }
   
   /**
    * 修改素材对应的模型和元素
    * $id 当前修改的素材id
    * @author zhangyong
    */
   public function _updateME($id)
   {
       $info = Info::model()->with('molds','materials','styles')->findByPk($id,'molds.is_old=0');
       $mids = array();
       $eles = array();
       $moldNameType = array_flip(Yii::app()->params['moldNameType']);
       $namwPType = array_flip(Yii::app()->params['namwPType']);
       if(!empty($info['molds'])){//修改模型
           foreach ($info['molds'] as $m){
               $mold = Mold::model()->findByPk($m['id'],'t.is_del=0');
               if(!empty($mold)){
                   array_push($mids,$mold['id']);
                   if(!empty($info['item']))
                       $mold->item = $info['item'].'-'.$moldNameType[$mold['mold_type']];
                   $mold->type = $info['type'];
                   $mold->length = $info['length'];
                   $mold->width = $info['width'];
                   $mold->height = $info['height'];
                   $mold->category_id = $info['category_id'];
                   $mold->label_id = $info['label_id'];
                   $mold->brand_id = $info['brand_id'];
                   $mold->brandhall_id = $info['brandhall_id'];
                   if($mold->save()){
                       //更新模型材质
                       if(!empty($info['materials'])){
                           MoldMaterialRelation::model()->deleteAll('mold_id='.$mold->id);
                           foreach ($info['materials'] as $mater){
                               $MoldMaterialRelation = new MoldMaterialRelation();
                               $MoldMaterialRelation->mold_id = $mold->id;
                               $MoldMaterialRelation->material_id = $mater['id'];
                               $MoldMaterialRelation->save();
                           }
                       }
                       //更新模型风格
                       if(!empty($info['styles'])){
                           MoldStyleRelation::model()->deleteAll('mold_id='.$mold->id);
                           foreach ($info['styles'] as $style){
                               $MoldStyleRelation = new MoldStyleRelation();
                               $MoldStyleRelation->mold_id = $mold->id;
                               $MoldStyleRelation->style_id = $style['id'];
                               $MoldStyleRelation->save();
                           }
                       }
                   }
               }
           }
       }
       //修改元素
       if(!empty($mids)){
           $elements = Element::model()->findAll("is_del=0 and mold_id in (". implode(',', $mids) .")");
           if(!empty($elements)){
               foreach ($elements as $e){
                   $element = Element::model()->findByPk($e['id'],'t.is_del=0');
                   if(!empty($element)){
                       if($element['type'] != $info['type']){
                           $element->type = $info['type'];
                           $name = explode('_', $element['name']);
                           $name[0] = $namwPType[$info['type']];
                           $element->name = implode('_', $name);
                       }
                       $element->category_id = $info['category_id'];
                       $element->label_id = $info['label_id'];
                       $element->brand_id = $info['brand_id'];
                       $element->brandhall_id = $info['brandhall_id'];
                       if($element->save()){
                           //更新元素材质
                           if(!empty($info['materials'])){
                               ElementMaterialRelation::model()->deleteAll('element_id='.$element->id);
                               foreach ($info['materials'] as $mater){
                                   $ElementMaterialRelation = new ElementMaterialRelation();
                                   $ElementMaterialRelation->element_id = $element->id;
                                   $ElementMaterialRelation->material_id = $mater['id'];
                                   $ElementMaterialRelation->save();
                               }
                           }
                           //更新元素风格
                           if(!empty($info['styles'])){
                               ElementStyleRelation::model()->deleteAll('element_id='.$element->id);
                               foreach ($info['styles'] as $style){
                                   $ElementStyleRelation = new ElementStyleRelation();
                                   $ElementStyleRelation->element_id = $element->id;
                                   $ElementStyleRelation->style_id = $style['id'];
                                   $ElementStyleRelation->save();
                               }
                           }
                       }
                   }
               }
           }
       }
   }
   
   /**
    * 统一处理素材(I)、商品(P)、模型(M)及元素(E)之间的关系
    * $options['type']=>绑定或解绑，$options['model']=>处理的模型（商品/模型），
    * $options['info']=>素材信息，$options['select']=>提交的模型或商品ID数据
    * @author zhangyong
    */
   public function _IPME($options = array())
   {
       if(empty($options))
           throw new CHttpException(500,'缺少必要的参数！');
       $type = $options['type'];
       $model = $options['model'];
       $info = $options['info'];
       $select = $options['select'];
       $moldIds = array();
       $eIds = array();
       if(isset($model) && $model == 'Product'){//素材绑定、解绑商品
           $pid = $select[0];
           $product = Product::model()->findByPk($pid,'is_delete=0');
           if(empty($product)){
               echo CJSON::encode($data = array('status'=>false,'info'=>'id为'.$pid.'的商品不存在或者已被删除！'));
               exit;
           }
           //获取素材下的模型数据
           if(!empty($info['molds'])){
               foreach ($info['molds'] as $m){
                   if(!in_array($m['id'], $moldIds))
                           array_push ($moldIds, $m['id']);
               }
           }
           //获取素材下的模型对应的所有元素
           if(!empty($moldIds)){
               $elements = Element::model()->findAll('is_del=0 and mold_id in ('. implode(',', $moldIds) .')');
               if(!empty($elements)){
                   foreach ($elements as $e){
                       if(!in_array($e['id'], $eIds))
                               array_push ($eIds, $e['id']);
                   }
               }
           }
           if($type == 'bind'){//绑定
               if(!empty($info['products'])){
                   echo CJSON::encode($data = array('status'=>false,'info'=>'请先解绑已绑定的商品！'));
                   exit;
               }
               if(!empty($moldIds)){
                   ProductMoldRelation::model()->deleteAll("product_id=".$pid." and mold_id in (". implode(',',$moldIds) .")");
                   foreach ($info['molds'] as $pm){
                       $haspm = ProductMoldRelation::model()->find('product_id='.$pid.' and mold_id!='.$pm['id'].' and mold_type='.$pm['mold_type']);
                       if(!empty($haspm)){
                           $oldEle = Element::model()->findAll("is_del=0 and mold_id=".$haspm['mold_id']);
                           if(!empty($oldEle)){
                               $oldEleDel = array();
                               foreach ($oldEle as $oe){
                                   if(!in_array($oe['id'], $oldEleDel))
                                           array_push ($oldEleDel, $oe['id']);
                               }
                               ProductElementRelation::model()->deleteAll("product_id=".$pid." and element_id in (". implode(',',$oldEleDel) .")");
                           }
                           ProductMoldRelation::model()->deleteAll('product_id='.$pid.' and mold_id='.$haspm['mold_id']);
                       }
                       $productMoldRelation = new ProductMoldRelation();
                       $productMoldRelation->product_id = $pid;
                       $productMoldRelation->mold_id = $pm['id'];
                       $productMoldRelation->mold_type = $pm['mold_type'];
                       $productMoldRelation->save();
                   }
               }
               if(!empty($eIds)){
                   ProductElementRelation::model()->deleteAll("product_id=".$pid." and element_id in (". implode(',',$eIds) .")");
                   foreach ($eIds as $e){
                       $productElementRelation = new ProductElementRelation();
                       $productElementRelation->product_id = $pid;
                       $productElementRelation->element_id = $e;
                       $productElementRelation->save();
                   }
               }
               //如果素材没有型号，则素材型号=商品的货号
               if(empty($info['item']) && !empty($product['product_sn'])){
                   $info->item = $product['product_sn'];
                   if(!empty($product['product_name']))
                       $info->title = $product['product_name'];
                   $info->save();
               }
           }else{//解绑
               if(!empty($moldIds))
                   ProductMoldRelation::model()->deleteAll("product_id=".$pid." and mold_id in (". implode(',',$moldIds) .")");
               if(!empty($eIds))
                   ProductElementRelation::model()->deleteAll("product_id=".$pid." and element_id in (". implode(',',$eIds) .")");
           }
       }else{//素材绑定、解绑模型
           $mids = $select;
           $elementArray = array();
           if(!empty($mids)){
               //提交的模型数据对应的所有元素
               $elementArray = Element::model()->findAll('is_del=0 and mold_id in ('. implode(',', $mids) .')');
               //提交的模型数据
               $molds = Mold::model()->findAll(
                       array(
                           'select'=>'id,mold_type',
                           'condition'=>'is_del=0 and id in ('. implode(',', $mids) .')',
                       )
               );
           }
           //处理提交上来的模型对应的所有元素数据
           if(!empty($elementArray)){
               foreach ($elementArray as $ele){
                   if(!in_array($ele['id'], $eIds))
                           array_push ($eIds, $ele['id']);
               }
           }
           if($type == 'bind'){//绑定
               if(!empty($info['products']) && !empty($mids)){
                   ProductMoldRelation::model()->deleteAll("product_id=".$info['products'][0]['product_id']." and mold_id in (". implode(',',$mids) .")");
                   foreach ($molds as $pm){
                       $haspm = ProductMoldRelation::model()->find('product_id='.$info['products'][0]['product_id'].' and mold_id!='.$pm['id'].' and mold_type='.$pm['mold_type']);
                       if(!empty($haspm)){
                           $oldEle = Element::model()->findAll("is_del=0 and mold_id=".$haspm['mold_id']);
                           if(!empty($oldEle)){
                               $oldEleDel = array();
                               foreach ($oldEle as $oe){
                                   if(!in_array($oe['id'], $oldEleDel))
                                           array_push ($oldEleDel, $oe['id']);
                               }
                               ProductElementRelation::model()->deleteAll("product_id=".$info['products'][0]['product_id']." and element_id in (". implode(',',$oldEleDel) .")");
                           }
                           ProductMoldRelation::model()->deleteAll('product_id='.$info['products'][0]['product_id'].' and mold_id='.$haspm['mold_id']);
                       }
                       $productMoldRelation = new ProductMoldRelation();
                       $productMoldRelation->product_id = $info['products'][0]['product_id'];
                       $productMoldRelation->mold_id = $pm['id'];
                       $productMoldRelation->mold_type = $pm['mold_type'];
                       $productMoldRelation->save();
                   }
                   if(!empty($eIds)){
                       ProductElementRelation::model()->deleteAll("product_id=".$info['products'][0]['product_id']." and element_id in (". implode(',',$eIds) .")");
                       foreach ($eIds as $e){
                           $productElementRelation = new ProductElementRelation();
                           $productElementRelation->product_id = $info['products'][0]['product_id'];
                           $productElementRelation->element_id = $e;
                           $productElementRelation->save();
                       }
                   }
               }
           }else{//解绑
               if(!empty($info['products']) && !empty($mids)){
                   ProductMoldRelation::model()->deleteAll("product_id=".$info['products'][0]['product_id']." and mold_id in (". implode(',',$mids) .")");
                   if(!empty($eIds))
                       ProductElementRelation::model()->deleteAll("product_id=".$info['products'][0]['product_id']." and element_id in (". implode(',',$eIds) .")");
               }
           }
           //统计素材的模型和贴图情况
           $this->_countInfoMI($info['id']);
       }

   }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Title',
			'item' => 'Item',
			'number' => 'Number',
			'content' => 'Content',
			'image' => 'Image',
			'category_id' => 'Category',
			'label_id' => 'Label',
			'brand_id' => 'Brand',
			'length' => 'Length',
			'width' => 'Width',
			'height' => 'Height',
			'texture_id' => 'Texture',
			'type' => 'Type',
			'mold_condition' => 'Mold Condition',
			'img_condition' => 'Img Condition',
			'furniture_pics' => 'Furniture Pics',
			'create_time' => 'Create Time',
			'update_time' => 'Update Time',
			'creater_id' => 'Creater',
			'updater_id' => 'Updater',
			'status' => 'Status',
			'is_del' => 'Is Del',
			'brandhall_id' => 'Brandhall',
			'is_rotation' => 'Is Rotation',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('item',$this->item,true);
		$criteria->compare('number',$this->number,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('category_id',$this->category_id,true);
		$criteria->compare('label_id',$this->label_id,true);
		$criteria->compare('brand_id',$this->brand_id,true);
		$criteria->compare('length',$this->length,true);
		$criteria->compare('width',$this->width,true);
		$criteria->compare('height',$this->height,true);
		$criteria->compare('texture_id',$this->texture_id,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('mold_condition',$this->mold_condition,true);
		$criteria->compare('img_condition',$this->img_condition,true);
		$criteria->compare('furniture_pics',$this->furniture_pics,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('creater_id',$this->creater_id,true);
		$criteria->compare('updater_id',$this->updater_id,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('is_del',$this->is_del);
		$criteria->compare('brandhall_id',$this->brandhall_id,true);
		$criteria->compare('is_rotation',$this->is_rotation);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Info the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
