<?php

/**
 * This is the model class for table "{{mold}}".
 *
 * The followings are the available columns in table '{{mold}}':
 * @property string $id
 * @property string $name
 * @property string $item
 * @property string $floorplan
 * @property string $image
 * @property string $mold
 * @property integer $type
 * @property string $length
 * @property string $width
 * @property string $height
 * @property string $summary
 * @property string $product_id
 * @property string $category_id
 * @property string $label_id
 * @property string $brand_id
 * @property string $create_time
 * @property string $update_time
 * @property string $creater_id
 * @property string $updater_id
 * @property integer $is_del
 * @property integer $status
 * @property string $brandhall_id
 * @property integer $mold_type
 * @property string $texture_id
 * @property string $maker
 * @property integer $is_old
 */
class Mold extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_mold';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, type, create_time, creater_id', 'required'),
			array('type, is_del, status, mold_type, is_old', 'numerical', 'integerOnly'=>true),
			array('name, item, floorplan, image, mold, summary, texture_id', 'length', 'max'=>255),
			array('length, width, height', 'length', 'max'=>8),
			array('product_id, category_id, label_id, brand_id, create_time, update_time, creater_id, updater_id, brandhall_id', 'length', 'max'=>10),
			array('maker', 'length', 'max'=>32),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, item, floorplan, image, mold, type, length, width, height, summary, product_id, category_id, label_id, brand_id, create_time, update_time, creater_id, updater_id, is_del, status, brandhall_id, mold_type, texture_id, maker, is_old', 'safe', 'on'=>'search'),
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
            'materials'=>array(self::MANY_MANY, 'Material', 'tbl_mold_material_relation(mold_id, material_id)'),
            'styles'=>array(self::MANY_MANY, 'Style', 'tbl_mold_style_relation(mold_id, style_id)'),
		);
	}
    
    /**
     * 查看新模型
     * @PS：订单，素材，模型及贴图
     * @author zhangyong
     */
    public function _updateTemp($model,$options = array())
    {
       $record = array();
       $record['model'] =$model;//模型数据
       if(isset($options['model']) && $options['model'] == 'Mold'){
           $mid = $model['id'];//模型id
           $record['info'] = Info::model()->with('molds')->find("t.is_del=0 and molds.is_del=0 and molds.id=".$mid);//素材数据
           if(empty($record['info']))
               throw new CHttpException(500,'id为'.$mid.'的模型对应的素材不存在或者已经删除！');
           //获取模型数据
           $record['moldCondition'] = array();
           $moldCondition = json_decode($record['info']['mold_condition'],true);
           if(!empty($moldCondition)){
               ksort($moldCondition);
               foreach ($moldCondition as $kt=>$mc){
                   $record['moldCondition'][$kt]['name'] = Yii::app()->params['moldType'][$kt];
                   $record['moldCondition'][$kt]['mid'] = $mc;
               }
           }
           $info_id = $record['info']['id'];
       }else{
           $info_id = $model['id'];
       }

       $record['order'] = array();//订单数据
       $record['info'] = $this->_getInfo($info_id);
       $record['order'] = Order::model()->with('infos')->find('t.is_del=0 and t.type in ('. implode(',', Yii::app()->params['allowCinfo']) .') and infos.id='.$info_id);
       if(empty($record['order']))
           throw new CHttpException(500,'id为'.$info_id.'的素材对应的订单不存在或者已经删除！');
       //获取贴图数据
       $textures = array();//存储贴图数据
       $record['lwh'] = array();//存储长宽高
       $texture_id = json_decode($record['model']['texture_id'],true);
       $colors = InfoColorRelation::model()->findAll('info_id='.$info_id);
       if(!empty($texture_id))
           $texture_ids = array_keys($texture_id);
       if(!empty($colors)){
           foreach ($colors as $kr=>$color){
               $textures[$kr] = array('name'=>$color['color_name'],'value'=>$color['color_value'],'texture'=>array());
               if(!empty($texture_ids)){
                   $tex = Texture::model()->find("color_value='".$color['color_value']."' and id in (". implode(',', $texture_ids) .")");
                   if(!empty($tex)){
                       $textures[$kr]['texture'] = $tex;
                       $record['lwh']['length'] = $tex['length'];
                       $record['lwh']['width'] = $tex['width'];
                       $record['lwh']['height'] = $tex['height'];
                   }
               }
           }
       }
       $record['textures'] = $textures;
       //组合名称的准备数据
       $record['reference'] = array('is_alpha'=>array('0'=>'N','1'=>'Y'),'type'=>array_flip(Yii::app()->params['imgColumn']));
       return $record;
    }
    
    /**
     * 素材信息
     * @author zhangyong
     */
    public function _getInfo($id)
    {
       $connection=Yii::app()->db;
       $sql = 'select * from tbl_info where is_del=0 and id in ('.$id.')';
       $ul = $connection->createCommand($sql)->queryAll();
       $brandhalls = Brand::model()->findAll(array('select'=>'id,name','condition'=>'is_del=0 and is_show=1'));
       $materials = Material::model()->findAll(array('select'=>'id,name','condition'=>''));
       if(!empty($brandhalls))
           $brandhalls=CHtml::listData($brandhalls,'id','name');
       if(!empty($materials))
           $materials=CHtml::listData($materials,'id','name');
       if(!empty($ul))
           $ul = Info::model()->findBSCM($ul,array('brandhalls'=>$brandhalls,'materials'=>$materials));
       return $ul;
    }
    
    /**
     * 获取贴图数据
     * $tids array 贴图id数组
     * @author zhangyong
     */
    public function _getTexture($tids)
    {
       $texs = Texture::model()->findAll('id in ('. implode(',', $tids) .')');
       $data = array('textures'=>array(),'reference'=>array());
       if(!empty($texs)){
           foreach ($texs as $k=>$v){
               $data['textures'][$k]['name'] = $v['color_name'];
               $data['textures'][$k]['value'] = $v['color_value'];
               $data['textures'][$k]['texture'] = $v;
           }
       }
       $data['reference'] = array('is_alpha'=>array('0'=>'N','1'=>'Y'),'type'=>array_flip(Yii::app()->params['imgColumn']));
       return $data;
    }
    
    /*
    * 自动上传贴图
    * $params 素材信息
    * @author zhangyong
    */
    public function _bindPME($params)
    {
       //获取该素材下的模型数据
       $infoMold = InfoMoldRelation::model()->findAll('info_id='.$params['id']);
       $moldIds = array();
       if(!empty($infoMold)){
           foreach ($infoMold as $im){
               if(!in_array($im['mold_id'], $moldIds))
                       array_push ($moldIds, $im['mold_id']);
           }
       }
       //获取商品，品牌/分类/货号条件
       $con = " and parent_id=0 and product_sn='".$params['item']."'";//货号
       //如果加品牌馆条件，经销商则绑定不到厂商的商品，故注释掉
//       if(!empty($params['brandhall_id']))
//           $con .= " and brandhall_id=".$params['brandhall_id'];//品牌馆
       if(!empty($params['brand_id']))
           $con .= " and brand_id=".$params['brand_id'];//品牌
       if(!empty($params['category_id']))
           $con .= " and cat_id=".$params['category_id'];//分类
       $product = Product::model()->find(array('select'=>'*','condition'=>'is_delete=0 and is_show=1'.$con,'order'=>'add_time desc'));
       if(!empty($product)){
           //获取素材下模型的相关的元素数据
           if(!empty($moldIds)){
               $elementIds = Element::model()->findAll("is_del=0 and mold_id in (". implode(',',$moldIds) .")");
               $eIds = array();
               if(!empty($elementIds)){
                   foreach ($elementIds as $elementId){
                       if(!in_array($elementId['id'], $eIds))
                               array_push ($eIds, $elementId['id']);
                   }
               }
                //更新新关联的商品与该素材的模型及该素材模型生产出来的元素之间的关系
                ProductMoldRelation::model()->deleteAll("product_id=".$product['product_id']." and mold_id in (". implode(',',$moldIds) .")");
                foreach ($params['molds'] as $pm){
                    $productMoldRelation = new ProductMoldRelation();
                    $productMoldRelation->product_id = $product['product_id'];
                    $productMoldRelation->mold_id = $pm['id'];
                    $productMoldRelation->mold_type = $pm['mold_type'];
                    $productMoldRelation->save();
                }
                if(!empty($eIds)){
                    ProductElementRelation::model()->deleteAll("product_id=".$product['product_id']." and element_id in (". implode(',',$eIds) .")");
                    foreach ($eIds as $e){
                        $productElementRelation = new ProductElementRelation();
                        $productElementRelation->product_id = $product['product_id'];
                        $productElementRelation->element_id = $e;
                        $productElementRelation->save();
                    }
                }
           }
           $products = Product::model()->findAll(array(
               'select'=>'product_id,brandhall_id',
               'condition'=>'is_delete=0 and (product_id='.$product['product_id'].' or parent_id='.$product['product_id'].')',
           ));
           InfoProductRelation::model()->deleteAll("info_id=".$params['id']);
           if(!empty($products)){
               foreach ($products as $ps){
                   $InfoProductRelation = new InfoProductRelation();
                   $InfoProductRelation->product_id = $ps['product_id'];
                   $InfoProductRelation->info_id = $params['id'];
                   $InfoProductRelation->brandhall_id = $ps['brandhall_id'];
                   $InfoProductRelation->save();
               }
           }
           if(!empty($product['product_name']))
               $params->title = $product['product_name'];
           $params->save();
       }
    }
    
    /**
    * 修改模型对应的元素
    * $id 当前修改的模型id
    * @author zhangyong
    */
   public function _updateE($id)
   {
       $namwPType = array_flip(Yii::app()->params['namwPType']);
       $mold = Mold::model()->with('materials','styles')->findByPk($id);
       $elements = Element::model()->findAll("is_del=0 and mold_id=".$id);
        if(!empty($elements)){
            foreach ($elements as $e){
                $element = Element::model()->findByPk($e['id'],'t.is_del=0');
                if(!empty($element)){
                    if($element['type'] != $mold['type']){
                        $element->type = $mold['type'];
                        $name = explode('_', $element['name']);
                        $name[0] = $namwPType[$mold['type']];
                        $element->name = implode('_', $name);
                    }
                    $element->category_id = $mold['category_id'];
                    $element->label_id = $mold['label_id'];
                    $element->brand_id = $mold['brand_id'];
                    $element->brandhall_id = $mold['brandhall_id'];
                    if($element->save()){
                        //更新元素材质
                        if(!empty($mold['materials'])){
                            ElementMaterialRelation::model()->deleteAll('element_id='.$element->id);
                            foreach ($mold['materials'] as $mater){
                                $ElementMaterialRelation = new ElementMaterialRelation();
                                $ElementMaterialRelation->element_id = $element->id;
                                $ElementMaterialRelation->material_id = $mater['id'];
                                $ElementMaterialRelation->save();
                            }
                        }
                        //更新元素风格
                        if(!empty($mold['styles'])){
                            ElementStyleRelation::model()->deleteAll('element_id='.$element->id);
                            foreach ($mold['styles'] as $style){
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

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'item' => 'Item',
			'floorplan' => 'Floorplan',
			'image' => 'Image',
			'mold' => 'Mold',
			'type' => 'Type',
			'length' => 'Length',
			'width' => 'Width',
			'height' => 'Height',
			'summary' => 'Summary',
			'product_id' => 'Product',
			'category_id' => 'Category',
			'label_id' => 'Label',
			'brand_id' => 'Brand',
			'create_time' => 'Create Time',
			'update_time' => 'Update Time',
			'creater_id' => 'Creater',
			'updater_id' => 'Updater',
			'is_del' => 'Is Del',
			'status' => 'Status',
			'brandhall_id' => 'Brandhall',
			'mold_type' => 'Mold Type',
			'texture_id' => 'Texture',
			'maker' => 'Maker',
            'is_old' => 'Is Old',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('item',$this->item,true);
		$criteria->compare('floorplan',$this->floorplan,true);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('mold',$this->mold,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('length',$this->length,true);
		$criteria->compare('width',$this->width,true);
		$criteria->compare('height',$this->height,true);
		$criteria->compare('summary',$this->summary,true);
		$criteria->compare('product_id',$this->product_id,true);
		$criteria->compare('category_id',$this->category_id,true);
		$criteria->compare('label_id',$this->label_id,true);
		$criteria->compare('brand_id',$this->brand_id,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('creater_id',$this->creater_id,true);
		$criteria->compare('updater_id',$this->updater_id,true);
		$criteria->compare('is_del',$this->is_del);
		$criteria->compare('status',$this->status);
		$criteria->compare('brandhall_id',$this->brandhall_id,true);
		$criteria->compare('mold_type',$this->mold_type);
		$criteria->compare('texture_id',$this->texture_id,true);
		$criteria->compare('maker',$this->maker,true);
        $criteria->compare('is_old',$this->is_old);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Mold the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
