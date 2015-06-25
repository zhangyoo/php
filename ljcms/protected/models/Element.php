<?php

/**
 * This is the model class for table "{{element}}".
 *
 * The followings are the available columns in table '{{element}}':
 * @property string $id
 * @property string $name
 * @property string $image
 * @property string $pics
 * @property string $pics_night
 * @property integer $type
 * @property string $summary
 * @property string $category_id
 * @property string $label_id
 * @property string $brand_id
 * @property string $rank
 * @property string $mold_id
 * @property string $create_time
 * @property string $update_time
 * @property string $creater_id
 * @property string $updater_id
 * @property integer $is_show
 * @property integer $is_del
 * @property integer $is_default
 * @property integer $is_recommend
 * @property integer $sort_num
 * @property string $dapei_num
 * @property string $brandhall_id
 */
class Element extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_element';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, image, pics, type, create_time, creater_id', 'required'),
			array('type, is_show, is_del, is_default, is_recommend, sort_num', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>128),
			array('image, summary', 'length', 'max'=>255),
			array('category_id, label_id, brand_id, rank, mold_id, create_time, update_time, creater_id, updater_id, dapei_num, brandhall_id', 'length', 'max'=>10),
			array('pics_night', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, image, pics, pics_night, type, summary, category_id, label_id, brand_id, rank, mold_id, create_time, update_time, creater_id, updater_id, is_show, is_del, is_default, is_recommend, sort_num, dapei_num, brandhall_id', 'safe', 'on'=>'search'),
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
		);
	}
    
    /**
    * 关联元素和商品
    * $eid string 元素id， $mid string 模型id
    * @author zhangyong
    */
   public function _PE($eid,$mid)
   {
       $productDatas = Product::model()->findAll(array('select'=>'product_id,parent_id,brandhall_id',
           'condition'=>'is_delete=0 and is_show=1 and product_id in (select product_id from tbl_product_mold_relation where mold_id='.$mid.')'));
       if(!empty($productDatas)){
           foreach ($productDatas as $productData){
               //关联商品tbl_element_product_relation
               if(!empty($productData) && !empty($productData['parent_id']) && !empty($productData['brandhall_id'])){
                   $ElementProduct = ElementProductRelation::model()->find("element_id=".$eid." and brandhall_id=".$productData['brandhall_id']." and product_id=".$productData['parent_id']);
                   if(empty($ElementProduct)){
                       $ElementProductRelation = new ElementProductRelation();
                       $ElementProductRelation->brandhall_id = $productData['brandhall_id'];
                       $ElementProductRelation->element_id = $eid;
                       $ElementProductRelation->product_id = $productData['parent_id'];
                       $ElementProductRelation->save();
                   }
               }
               //关联商品tbl_product_element_relation
               if(!empty($productData))
               {
                   $ProductElement = ProductElementRelation::model()->find("element_id=".$eid." and product_id=".$productData['product_id']);
                   if(empty($ProductElement)){
                       $ProductElementRelation = new ProductElementRelation();
                       $ProductElementRelation->element_id = $eid;
                       $ProductElementRelation->product_id = $productData['product_id'];
                       $ProductElementRelation->save();
                   }
               }
           }
       }
   }
   
   /**
    * 关联元素和模型以及商品
    * $options['eids'] string 多个元素ID 如 1123,1124,1125， $options['mid'] string 模型ID， 
    * $options['type'] string 处理类型 bind=>绑定，unbind=>解绑
    * @author zhangyong
    */
   public function _EMP($options = array())
   {
       if(isset($options['eids']) && !empty($options['eids']))
           $eids = $options['eids'];
       if(isset($options['mid']) && !empty($options['mid']))
           $mid = $options['mid'];
       if(isset($options['type']) && !empty($options['type']))
           $type = $options['type'];
       $pms = Product::model()->findAll(array('select'=>'product_id,parent_id,brandhall_id',
                    'condition'=>'is_delete=0 and is_show=1 and product_id in (select product_id from tbl_product_mold_relation where mold_id='.$mid.')'));
       if(!empty($eids) && !empty($mid) && !empty($type)){
           $materials = array();//模型id数组
           $styles = array();//风格数组
           $mold = Mold::model()->with('materials','styles')->findByPk($mid,'t.is_del=0');
           if(!empty($mold['materials'])){
               foreach ($mold['materials'] as $material){
                   array_push($materials, $material['id']);
               }
           }
           if(!empty($mold['styles'])){
               foreach ($mold['styles'] as $style){
                   array_push($styles, $style['id']);
               }
           }
           $elements = explode(',',$eids);
           if($type == 'bind'){
               //先判断元素是否已绑定了模型
               $elementIsM = Element::model()->findAll("is_del=0 and mold_id is not null and mold_id !=0 and id in (".$eids.")");
               if(!empty($elementIsM)){
                   echo CJSON::encode($data = array('status'=>false,'info'=>'请先解绑已绑定的模型！'));
                   exit;
               }
               //元素绑定模型
               Element::model()->updateAll(array('mold_id'=>$mid),"is_del=0 and id in (".$eids.")");
               if(!empty($pms)){
                   foreach ($elements as $e){
                       $this->_PE($e,$mid);
                       if(!empty($materials)){
                           ElementMaterialRelation::model()->deleteAll("element_id=".$e);
                           foreach ($materials as $mater){
                               $ElementMaterialRelation = new ElementMaterialRelation();
                               $ElementMaterialRelation->element_id = $e;
                               $ElementMaterialRelation->material_id = $mater;
                               $ElementMaterialRelation->save();
                           }
                       }
                       if(!empty($styles)){
                           ElementStyleRelation::model()->deleteAll("element_id=".$e);
                           foreach ($styles as $sty){
                               $ElementStyleRelation = new ElementStyleRelation();
                               $ElementStyleRelation->element_id = $e;
                               $ElementStyleRelation->style_id = $sty;
                               $ElementStyleRelation->save();
                           }
                       }
                   }
               }
           }else{
               //元素解绑模型
               $unbindE = Element::model()->findAll("is_del=0 and mold_id=".$mid." and id in (".$eids.")");
               ElementMaterialRelation::model()->deleteAll("element_id in (".$eids.")");
               ElementStyleRelation::model()->deleteAll("element_id in (".$eids.")");
               $ueArr = array();
               if(!empty($unbindE)){
                   foreach ($unbindE as $ue){
                       if(!in_array($ue['id'], $ueArr))
                               array_push ($ueArr, $ue['id']);
                   }
                   Element::model()->updateAll(array('mold_id'=>0),'id in ('. implode(',', $ueArr) .')');
                   if(!empty($pms)){
                       foreach ($pms as $p){
                           ProductElementRelation::model()->deleteAll('product_id='.$p['product_id'].' and element_id in ('. implode(',', $ueArr) .')');
                           if(!empty($p['parent_id']) && !empty($p['brandhall_id']))
                               ElementProductRelation::model()->deleteAll('product_id='.$p['parent_id'].' and brandhall_id='.$p['brandhall_id'].' and element_id in ('. implode(',', $ueArr) .')');
                       }
                   }
               }
           }
           return $data = array('status'=>true,'info'=>'操作成功！');
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
			'image' => 'Image',
			'pics' => 'Pics',
			'pics_night' => 'Pics Night',
			'type' => 'Type',
			'summary' => 'Summary',
			'category_id' => 'Category',
            'label_id' => 'Label',
			'brand_id' => 'Brand',
			'rank' => 'Rank',
			'mold_id' => 'Mold',
			'create_time' => 'Create Time',
			'update_time' => 'Update Time',
			'creater_id' => 'Creater',
			'updater_id' => 'Updater',
			'is_show' => 'Is Show',
			'is_del' => 'Is Del',
			'is_default' => 'Is Default',
			'is_recommend' => 'Is Recommend',
			'sort_num' => 'Sort Num',
			'dapei_num' => 'Dapei Num',
			'brandhall_id' => 'Brandhall',
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
		$criteria->compare('image',$this->image,true);
		$criteria->compare('pics',$this->pics,true);
		$criteria->compare('pics_night',$this->pics_night,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('summary',$this->summary,true);
		$criteria->compare('category_id',$this->category_id,true);
        $criteria->compare('label_id',$this->label_id,true);
		$criteria->compare('brand_id',$this->brand_id,true);
		$criteria->compare('rank',$this->rank,true);
		$criteria->compare('mold_id',$this->mold_id,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('creater_id',$this->creater_id,true);
		$criteria->compare('updater_id',$this->updater_id,true);
		$criteria->compare('is_show',$this->is_show);
		$criteria->compare('is_del',$this->is_del);
		$criteria->compare('is_default',$this->is_default);
		$criteria->compare('is_recommend',$this->is_recommend);
		$criteria->compare('sort_num',$this->sort_num);
		$criteria->compare('dapei_num',$this->dapei_num,true);
		$criteria->compare('brandhall_id',$this->brandhall_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Element the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
