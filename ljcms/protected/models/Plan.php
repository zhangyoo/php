<?php

/**
 * This is the model class for table "{{plan}}".
 *
 * The followings are the available columns in table '{{plan}}':
 * @property string $id
 * @property string $parent_id
 * @property string $set_id
 * @property integer $type
 * @property string $brandhall_id
 * @property string $name
 * @property string $image
 * @property string $coverpic_id
 * @property string $summary
 * @property string $price_soft
 * @property string $price_hard
 * @property string $price_total
 * @property string $price_range
 * @property string $apartment_id
 * @property string $apartment_pic_id
 * @property string $space_id
 * @property string $showroom_id
 * @property integer $room_category
 * @property string $create_time
 * @property string $update_time
 * @property string $creater_id
 * @property string $updater_id
 * @property string $province_id
 * @property string $city_id
 * @property string $property_id
 * @property integer $area
 * @property integer $is_show
 * @property integer $is_del
 * @property integer $is_recommend
 * @property string $element_num
 * @property string $comment_num
 * @property string $browse_num
 * @property string $favor_num
 * @property string $plan_num
 * @property string $point_id
 * @property string $title
 * @property string $x
 * @property string $y
 * @property string $z
 * @property string $sort_num
 */
class Plan extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_plan';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, image, create_time, creater_id', 'required'),
			array('type, room_category, area, is_show, is_del, is_recommend', 'numerical', 'integerOnly'=>true),
			array('parent_id, set_id, brandhall_id, coverpic_id, apartment_id, apartment_pic_id, space_id, showroom_id, create_time, update_time, creater_id, updater_id, province_id, city_id, property_id, element_num, comment_num, browse_num, favor_num, plan_num, point_id, x, y, z', 'length', 'max'=>10),
			array('name, price_range', 'length', 'max'=>128),
			array('image, summary', 'length', 'max'=>255),
			array('price_soft, price_hard, price_total', 'length', 'max'=>64),
			array('title', 'length', 'max'=>100),
			array('sort_num', 'length', 'max'=>8),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, parent_id, set_id, type, brandhall_id, name, image, coverpic_id, summary, price_soft, price_hard, price_total, price_range, apartment_id, apartment_pic_id, space_id, showroom_id, room_category, create_time, update_time, creater_id, updater_id, province_id, city_id, property_id, area, is_show, is_del, is_recommend, element_num, comment_num, browse_num, favor_num, plan_num, point_id, title, x, y, z, sort_num', 'safe', 'on'=>'search'),
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
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'parent_id' => 'Parent',
			'set_id' => 'Set',
			'type' => 'Type',
			'brandhall_id' => 'Brandhall',
			'name' => 'Name',
			'image' => 'Image',
			'coverpic_id' => 'Coverpic',
			'summary' => 'Summary',
			'price_soft' => 'Price Soft',
			'price_hard' => 'Price Hard',
			'price_total' => 'Price Total',
			'price_range' => 'Price Range',
			'apartment_id' => 'Apartment',
			'apartment_pic_id' => 'Apartment Pic',
			'space_id' => 'Space',
			'showroom_id' => 'Showroom',
			'room_category' => 'Room Category',
			'create_time' => 'Create Time',
			'update_time' => 'Update Time',
			'creater_id' => 'Creater',
			'updater_id' => 'Updater',
			'province_id' => 'Province',
			'city_id' => 'City',
			'property_id' => 'Property',
			'area' => 'Area',
			'is_show' => 'Is Show',
			'is_del' => 'Is Del',
			'is_recommend' => 'Is Recommend',
			'element_num' => 'Element Num',
			'comment_num' => 'Comment Num',
			'browse_num' => 'Browse Num',
			'favor_num' => 'Favor Num',
			'plan_num' => 'Plan Num',
			'point_id' => 'Point',
			'title' => 'Title',
			'x' => 'X',
			'y' => 'Y',
			'z' => 'Z',
			'sort_num' => 'Sort Num',
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
		$criteria->compare('set_id',$this->set_id,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('brandhall_id',$this->brandhall_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('coverpic_id',$this->coverpic_id,true);
		$criteria->compare('summary',$this->summary,true);
		$criteria->compare('price_soft',$this->price_soft,true);
		$criteria->compare('price_hard',$this->price_hard,true);
		$criteria->compare('price_total',$this->price_total,true);
		$criteria->compare('price_range',$this->price_range,true);
		$criteria->compare('apartment_id',$this->apartment_id,true);
		$criteria->compare('apartment_pic_id',$this->apartment_pic_id,true);
		$criteria->compare('space_id',$this->space_id,true);
		$criteria->compare('showroom_id',$this->showroom_id,true);
		$criteria->compare('room_category',$this->room_category);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('creater_id',$this->creater_id,true);
		$criteria->compare('updater_id',$this->updater_id,true);
		$criteria->compare('province_id',$this->province_id,true);
		$criteria->compare('city_id',$this->city_id,true);
		$criteria->compare('property_id',$this->property_id,true);
		$criteria->compare('area',$this->area);
		$criteria->compare('is_show',$this->is_show);
		$criteria->compare('is_del',$this->is_del);
		$criteria->compare('is_recommend',$this->is_recommend);
		$criteria->compare('element_num',$this->element_num,true);
		$criteria->compare('comment_num',$this->comment_num,true);
		$criteria->compare('browse_num',$this->browse_num,true);
		$criteria->compare('favor_num',$this->favor_num,true);
		$criteria->compare('plan_num',$this->plan_num,true);
		$criteria->compare('point_id',$this->point_id,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('x',$this->x,true);
		$criteria->compare('y',$this->y,true);
		$criteria->compare('z',$this->z,true);
		$criteria->compare('sort_num',$this->sort_num,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Plan the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
