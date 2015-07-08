<?php

/**
 * This is the model class for table "{{brandhall}}".
 *
 * The followings are the available columns in table '{{brandhall}}':
 * @property string $id
 * @property string $name
 * @property string $banner
 * @property string $summary
 * @property string $domain
 * @property string $brand_id
 * @property string $category_id
 * @property string $business_license
 * @property string $logo_square
 * @property string $logo_rectangle
 * @property string $square_link
 * @property string $rectangle_link
 * @property integer $version
 * @property integer $vip_level
 * @property integer $region_level
 * @property string $default_style
 * @property string $default_region
 * @property string $property_id
 * @property integer $background_id
 * @property string $create_time
 * @property string $update_time
 * @property string $creater_id
 * @property string $updater_id
 * @property integer $is_show
 * @property integer $is_del
 * @property integer $deadline
 * @property string $max_collect_apartment_num
 * @property string $collect_apartment_num
 * @property integer $max_nav
 * @property integer $is_check
 */
class Brandhall extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_brandhall';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, domain, business_license, version, create_time, creater_id', 'required'),
			array('version, vip_level, region_level, background_id, is_show, is_del, deadline, max_nav, is_check', 'numerical', 'integerOnly'=>true),
			array('name, banner', 'length', 'max'=>64),
			array('summary, brand_id, category_id, business_license, logo_square, logo_rectangle, square_link, rectangle_link', 'length', 'max'=>255),
			array('domain', 'length', 'max'=>128),
			array('default_style', 'length', 'max'=>32),
			array('create_time, update_time, creater_id, updater_id, max_collect_apartment_num, collect_apartment_num', 'length', 'max'=>10),
			array('default_region, property_id', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, banner, summary, domain, brand_id, category_id, business_license, logo_square, logo_rectangle, square_link, rectangle_link, version, vip_level, region_level, default_style, default_region, property_id, background_id, create_time, update_time, creater_id, updater_id, is_show, is_del, deadline, max_collect_apartment_num, collect_apartment_num, max_nav, is_check', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'banner' => 'Banner',
			'summary' => 'Summary',
			'domain' => 'Domain',
			'brand_id' => 'Brand',
			'category_id' => 'Category',
			'business_license' => 'Business License',
			'logo_square' => 'Logo Square',
			'logo_rectangle' => 'Logo Rectangle',
			'square_link' => 'Square Link',
			'rectangle_link' => 'Rectangle Link',
			'version' => 'Version',
			'vip_level' => 'Vip Level',
			'region_level' => 'Region Level',
			'default_style' => 'Default Style',
			'default_region' => 'Default Region',
			'property_id' => 'Property',
			'background_id' => 'Background',
			'create_time' => 'Create Time',
			'update_time' => 'Update Time',
			'creater_id' => 'Creater',
			'updater_id' => 'Updater',
			'is_show' => 'Is Show',
			'is_del' => 'Is Del',
			'deadline' => 'Deadline',
			'max_collect_apartment_num' => 'Max Collect Apartment Num',
			'collect_apartment_num' => 'Collect Apartment Num',
			'max_nav' => 'Max Nav',
			'is_check' => 'Is Check',
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
		$criteria->compare('banner',$this->banner,true);
		$criteria->compare('summary',$this->summary,true);
		$criteria->compare('domain',$this->domain,true);
		$criteria->compare('brand_id',$this->brand_id,true);
		$criteria->compare('category_id',$this->category_id,true);
		$criteria->compare('business_license',$this->business_license,true);
		$criteria->compare('logo_square',$this->logo_square,true);
		$criteria->compare('logo_rectangle',$this->logo_rectangle,true);
		$criteria->compare('square_link',$this->square_link,true);
		$criteria->compare('rectangle_link',$this->rectangle_link,true);
		$criteria->compare('version',$this->version);
		$criteria->compare('vip_level',$this->vip_level);
		$criteria->compare('region_level',$this->region_level);
		$criteria->compare('default_style',$this->default_style,true);
		$criteria->compare('default_region',$this->default_region,true);
		$criteria->compare('property_id',$this->property_id,true);
		$criteria->compare('background_id',$this->background_id);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('creater_id',$this->creater_id,true);
		$criteria->compare('updater_id',$this->updater_id,true);
		$criteria->compare('is_show',$this->is_show);
		$criteria->compare('is_del',$this->is_del);
		$criteria->compare('deadline',$this->deadline);
		$criteria->compare('max_collect_apartment_num',$this->max_collect_apartment_num,true);
		$criteria->compare('collect_apartment_num',$this->collect_apartment_num,true);
		$criteria->compare('max_nav',$this->max_nav);
		$criteria->compare('is_check',$this->is_check);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Brandhall the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
