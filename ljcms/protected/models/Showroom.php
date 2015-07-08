<?php

/**
 * This is the model class for table "{{showroom}}".
 *
 * The followings are the available columns in table '{{showroom}}':
 * @property string $id
 * @property string $parent_id
 * @property string $brandhall_id
 * @property string $name
 * @property string $image
 * @property string $coverpic_id
 * @property string $angle
 * @property string $angles
 * @property string $space_id
 * @property integer $room_category
 * @property string $create_time
 * @property string $update_time
 * @property string $creater_id
 * @property string $updater_id
 * @property integer $is_show
 * @property integer $is_recommend
 * @property integer $is_del
 * @property string $element_num
 * @property string $max_element_num
 * @property string $plan_num
 * @property string $recommend_plan_num
 * @property string $max_recommend_plan_num
 */
class Showroom extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_showroom';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, angle, space_id, room_category, create_time, creater_id', 'required'),
			array('room_category, is_show, is_recommend, is_del', 'numerical', 'integerOnly'=>true),
			array('parent_id, brandhall_id, coverpic_id, space_id, create_time, update_time, creater_id, updater_id, element_num, max_element_num, plan_num, recommend_plan_num, max_recommend_plan_num', 'length', 'max'=>10),
			array('name', 'length', 'max'=>128),
			array('image, angles', 'length', 'max'=>255),
			array('angle', 'length', 'max'=>32),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, parent_id, brandhall_id, name, image, coverpic_id, angle, angles, space_id, room_category, create_time, update_time, creater_id, updater_id, is_show, is_recommend, is_del, element_num, max_element_num, plan_num, recommend_plan_num, max_recommend_plan_num', 'safe', 'on'=>'search'),
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
    * 样板间通过元素关联分类和品牌数据
    * @author zhangyong
    */
   public function ShowroomBC($element_id)
   {
       $connection=Yii::app()->db;
       if(intval($element_id)>0){
           $products = Product::model()->findAll(array(
               'select'=>'cat_id,brand_id,brandhall_id',
               'condition'=>'is_delete=0 and is_show=1 and product_id in '
               . '(select product_id from tbl_product_element_relation where element_id='.$element_id.')'
           ));
           $showrooms = Showroom::model()->findAll(array(
               'select'=>'id',
               'condition'=>'is_del=0 and is_show=1 and id in '
               . '(select showroom_id from tbl_showroom_element_relation where is_show=1 and element_id='.$element_id.')'
           ));
           if(!empty($showrooms) && !empty($products)){
               foreach ($showrooms as $sr){
                   foreach ($products as $p){
                       $sql = 'select * from tbl_showroom_brand_relation where showroom_id='.$sr['id'].' and brandhall_id='.$p['brandhall_id'].' and brand_id='.$p['brand_id'];
                       $hasSBR = $connection->createCommand($sql)->queryRow();
                       if(empty($hasSBR)){
                           $sql = 'replace into tbl_showroom_brand_relation (showroom_id,brand_id,brandhall_id) values ('.$sr['id'].','.$p['brand_id'].','.$p['brandhall_id'].')';
                           $connection->createCommand($sql)->execute();
                       }
                       $sql = 'select * from tbl_showroom_category_relation where showroom_id='.$sr['id'].' and brandhall_id='.$p['brandhall_id'].' and category_id='.$p['cat_id'];
                       $hasSCR = $connection->createCommand($sql)->queryRow();
                       if(empty($hasSCR)){
                           $sql = 'replace into tbl_showroom_category_relation (showroom_id,category_id,brandhall_id) values ('.$sr['id'].','.$p['cat_id'].','.$p['brandhall_id'].')';
                           $connection->createCommand($sql)->execute();
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
			'parent_id' => 'Parent',
			'brandhall_id' => 'Brandhall',
			'name' => 'Name',
			'image' => 'Image',
			'coverpic_id' => 'Coverpic',
			'angle' => 'Angle',
			'angles' => 'Angles',
			'space_id' => 'Space',
			'room_category' => 'Room Category',
			'create_time' => 'Create Time',
			'update_time' => 'Update Time',
			'creater_id' => 'Creater',
			'updater_id' => 'Updater',
			'is_show' => 'Is Show',
			'is_recommend' => 'Is Recommend',
			'is_del' => 'Is Del',
			'element_num' => 'Element Num',
			'max_element_num' => 'Max Element Num',
			'plan_num' => 'Plan Num',
			'recommend_plan_num' => 'Recommend Plan Num',
			'max_recommend_plan_num' => 'Max Recommend Plan Num',
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
		$criteria->compare('parent_id',$this->parent_id,true);
		$criteria->compare('brandhall_id',$this->brandhall_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('coverpic_id',$this->coverpic_id,true);
		$criteria->compare('angle',$this->angle,true);
		$criteria->compare('angles',$this->angles,true);
		$criteria->compare('space_id',$this->space_id,true);
		$criteria->compare('room_category',$this->room_category);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('creater_id',$this->creater_id,true);
		$criteria->compare('updater_id',$this->updater_id,true);
		$criteria->compare('is_show',$this->is_show);
		$criteria->compare('is_recommend',$this->is_recommend);
		$criteria->compare('is_del',$this->is_del);
		$criteria->compare('element_num',$this->element_num,true);
		$criteria->compare('max_element_num',$this->max_element_num,true);
		$criteria->compare('plan_num',$this->plan_num,true);
		$criteria->compare('recommend_plan_num',$this->recommend_plan_num,true);
		$criteria->compare('max_recommend_plan_num',$this->max_recommend_plan_num,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Showroom the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
