<?php

/**
 * This is the model class for table "sp_goods_attr".
 *
 * The followings are the available columns in table 'sp_goods_attr':
 * @property string $goods_attr_id
 * @property string $product_id
 * @property integer $mold_id
 * @property integer $attr_id
 * @property string $attr_key
 * @property string $attr_name
 * @property string $attr_value
 * @property string $attr_price
 * @property string $attr_show_value
 * @property integer $is_icon
 * @property integer $attr_sort
 */
class GoodsAttr extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sp_goods_attr';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('attr_name', 'required'),
			array('mold_id, attr_id, is_icon, attr_sort', 'numerical', 'integerOnly'=>true),
			array('product_id', 'length', 'max'=>11),
			array('attr_key', 'length', 'max'=>32),
			array('attr_name, attr_value, attr_show_value', 'length', 'max'=>50),
			array('attr_price', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('goods_attr_id, product_id, mold_id, attr_id, attr_key, attr_name, attr_value, attr_price, attr_show_value, is_icon, attr_sort', 'safe', 'on'=>'search'),
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
			'goods_attr_id' => 'Goods Attr',
			'product_id' => 'Product',
			'mold_id' => 'Mold',
			'attr_id' => 'Attr',
			'attr_key' => 'Attr Key',
			'attr_name' => 'Attr Name',
			'attr_value' => 'Attr Value',
			'attr_price' => 'Attr Price',
			'attr_show_value' => 'Attr Show Value',
			'is_icon' => 'Is Icon',
			'attr_sort' => 'Attr Sort',
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

		$criteria->compare('goods_attr_id',$this->goods_attr_id,true);
		$criteria->compare('product_id',$this->product_id,true);
		$criteria->compare('mold_id',$this->mold_id);
		$criteria->compare('attr_id',$this->attr_id);
		$criteria->compare('attr_key',$this->attr_key,true);
		$criteria->compare('attr_name',$this->attr_name,true);
		$criteria->compare('attr_value',$this->attr_value,true);
		$criteria->compare('attr_price',$this->attr_price,true);
		$criteria->compare('attr_show_value',$this->attr_show_value,true);
		$criteria->compare('is_icon',$this->is_icon);
		$criteria->compare('attr_sort',$this->attr_sort);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return GoodsAttr the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
