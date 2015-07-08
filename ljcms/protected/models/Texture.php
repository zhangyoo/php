<?php

/**
 * This is the model class for table "{{texture}}".
 *
 * The followings are the available columns in table '{{texture}}':
 * @property string $id
 * @property string $name
 * @property string $color_name
 * @property string $color_value
 * @property string $floorplan
 * @property string $image
 * @property string $uv_map
 * @property string $m_uv_map
 * @property string $normal_map
 * @property string $m_normal_map
 * @property string $specular_map
 * @property integer $alpha
 * @property string $length
 * @property string $width
 * @property string $height
 * @property string $maker
 */
class Texture extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_texture';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('alpha', 'numerical', 'integerOnly'=>true),
			array('name, color_value, floorplan, image, uv_map, m_uv_map, normal_map, m_normal_map, specular_map', 'length', 'max'=>255),
			array('color_name', 'length', 'max'=>60),
			array('length, width, height', 'length', 'max'=>10),
			array('maker', 'length', 'max'=>32),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, color_name, color_value, floorplan, image, uv_map, m_uv_map, normal_map, m_normal_map, specular_map, alpha, length, width, height, maker', 'safe', 'on'=>'search'),
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
			'color_name' => 'Color Name',
			'color_value' => 'Color Value',
			'floorplan' => 'Floorplan',
			'image' => 'Image',
			'uv_map' => 'Uv Map',
			'm_uv_map' => 'M Uv Map',
			'normal_map' => 'Normal Map',
			'm_normal_map' => 'M Normal Map',
			'specular_map' => 'Specular Map',
			'alpha' => 'Alpha',
			'length' => 'Length',
			'width' => 'Width',
			'height' => 'Height',
			'maker' => 'Maker',
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
		$criteria->compare('color_name',$this->color_name,true);
		$criteria->compare('color_value',$this->color_value,true);
		$criteria->compare('floorplan',$this->floorplan,true);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('uv_map',$this->uv_map,true);
		$criteria->compare('m_uv_map',$this->m_uv_map,true);
		$criteria->compare('normal_map',$this->normal_map,true);
		$criteria->compare('m_normal_map',$this->m_normal_map,true);
		$criteria->compare('specular_map',$this->specular_map,true);
		$criteria->compare('alpha',$this->alpha);
		$criteria->compare('length',$this->length,true);
		$criteria->compare('width',$this->width,true);
		$criteria->compare('height',$this->height,true);
		$criteria->compare('maker',$this->maker,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Texture the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
