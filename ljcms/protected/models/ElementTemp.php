<?php

/**
 * This is the model class for table "{{element_temp}}".
 *
 * The followings are the available columns in table '{{element_temp}}':
 * @property string $id
 * @property string $name
 * @property string $image
 * @property string $pics
 * @property string $pics_night
 * @property string $layer
 * @property integer $type
 * @property string $summary
 * @property string $category_id
 * @property string $label_id
 * @property string $brand_id
 * @property string $style_id
 * @property string $material_id
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
 * @property integer $status
 * @property integer $sort_num
 * @property string $dapei_num
 * @property string $brandhall_id
 */
class ElementTemp extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{element_temp}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, image, pics, layer, type, create_time', 'required'),
			array('type, is_show, is_del, is_default, is_recommend, status, sort_num', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>128),
			array('image, summary, style_id, material_id', 'length', 'max'=>255),
			array('category_id, label_id, brand_id, rank, mold_id, create_time, update_time, creater_id, updater_id, dapei_num, brandhall_id', 'length', 'max'=>10),
			array('pics_night', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, image, pics, pics_night, layer, type, summary, category_id, label_id, brand_id, style_id, material_id, rank, mold_id, create_time, update_time, creater_id, updater_id, is_show, is_del, is_default, is_recommend, status, sort_num, dapei_num, brandhall_id', 'safe', 'on'=>'search'),
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
			'image' => 'Image',
			'pics' => 'Pics',
			'pics_night' => 'Pics Night',
			'layer' => 'Layer',
			'type' => 'Type',
			'summary' => 'Summary',
			'category_id' => 'Category',
			'label_id' => 'Label',
			'brand_id' => 'Brand',
			'style_id' => 'Style',
			'material_id' => 'Material',
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
			'status' => 'Status',
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
		$criteria->compare('layer',$this->layer,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('summary',$this->summary,true);
		$criteria->compare('category_id',$this->category_id,true);
		$criteria->compare('label_id',$this->label_id,true);
		$criteria->compare('brand_id',$this->brand_id,true);
		$criteria->compare('style_id',$this->style_id,true);
		$criteria->compare('material_id',$this->material_id,true);
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
		$criteria->compare('status',$this->status);
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
	 * @return ElementTemp the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
