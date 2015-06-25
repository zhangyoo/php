<?php

/**
 * This is the model class for table "sp_product".
 *
 * The followings are the available columns in table 'sp_product':
 * @property string $product_id
 * @property string $parent_id
 * @property string $cat_id
 * @property string $cat_alias_id
 * @property string $brand_id
 * @property string $brandhall_id
 * @property string $product_name
 * @property string $product_sn
 * @property integer $product_number
 * @property integer $warn_number
 * @property string $product_weight
 * @property string $product_desc
 * @property string $product_summary
 * @property string $product_img
 * @property integer $is_on_sale
 * @property string $market_price
 * @property string $shop_price
 * @property string $promote_price
 * @property string $promote_discount
 * @property string $promote_start_date
 * @property string $promote_end_date
 * @property string $goods_type
 * @property string $keywords
 * @property string $click_count
 * @property string $like_count
 * @property integer $sort
 * @property integer $is_delete
 * @property integer $is_new
 * @property integer $is_hot
 * @property integer $is_promote
 * @property integer $is_check
 * @property string $add_time
 * @property string $update_time
 * @property integer $is_cod
 * @property integer $element_num
 * @property integer $is_recommend
 * @property integer $is_detachable
 * @property integer $is_show
 * @property integer $is_buy
 * @property string $standard_number
 * @property integer $is_mine
 * @property string $unit
 * @property integer $sales_total
 * @property integer $sales_month
 * @property string $texture_id
 */
class Product extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sp_product';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('product_name, product_sn, product_weight, product_img', 'required'),
			array('product_number, warn_number, is_on_sale, sort, is_delete, is_new, is_hot, is_promote, is_check, is_cod, element_num, is_recommend, is_detachable, is_show, is_buy, is_mine, sales_total, sales_month', 'numerical', 'integerOnly'=>true),
			array('parent_id, cat_id, cat_alias_id, brand_id, goods_type', 'length', 'max'=>11),
			array('brandhall_id, product_weight, market_price, shop_price, promote_price, promote_start_date, promote_end_date, click_count, like_count, add_time, update_time, standard_number', 'length', 'max'=>10),
			array('product_name', 'length', 'max'=>120),
			array('product_sn', 'length', 'max'=>60),
			array('product_summary, product_img, keywords, texture_id', 'length', 'max'=>255),
			array('promote_discount', 'length', 'max'=>5),
			array('unit', 'length', 'max'=>32),
			array('product_desc', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('product_id, parent_id, cat_id, cat_alias_id, brand_id, brandhall_id, product_name, product_sn, product_number, warn_number, product_weight, product_desc, product_summary, product_img, is_on_sale, market_price, shop_price, promote_price, promote_discount, promote_start_date, promote_end_date, goods_type, keywords, click_count, like_count, sort, is_delete, is_new, is_hot, is_promote, is_check, add_time, update_time, is_cod, element_num, is_recommend, is_detachable, is_show, is_buy, standard_number, is_mine, unit, sales_total, sales_month, texture_id', 'safe', 'on'=>'search'),
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
            'molds'=>array(self::MANY_MANY, 'Mold', 'tbl_product_mold_relation(product_id, mold_id)','on'=>'molds.is_del=0'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'product_id' => 'Product',
			'parent_id' => 'Parent',
			'cat_id' => 'Cat',
			'cat_alias_id' => 'Cat Alias',
			'brand_id' => 'Brand',
			'brandhall_id' => 'Brandhall',
			'product_name' => 'Product Name',
			'product_sn' => 'Product Sn',
			'product_number' => 'Product Number',
			'warn_number' => 'Warn Number',
			'product_weight' => 'Product Weight',
			'product_desc' => 'Product Desc',
			'product_summary' => 'Product Summary',
			'product_img' => 'Product Img',
			'is_on_sale' => 'Is On Sale',
			'market_price' => 'Market Price',
			'shop_price' => 'Shop Price',
			'promote_price' => 'Promote Price',
			'promote_discount' => 'Promote Discount',
			'promote_start_date' => 'Promote Start Date',
			'promote_end_date' => 'Promote End Date',
			'goods_type' => 'Goods Type',
			'keywords' => 'Keywords',
			'click_count' => 'Click Count',
			'like_count' => 'Like Count',
			'sort' => 'Sort',
			'is_delete' => 'Is Delete',
			'is_new' => 'Is New',
			'is_hot' => 'Is Hot',
			'is_promote' => 'Is Promote',
			'is_check' => 'Is Check',
			'add_time' => 'Add Time',
			'update_time' => 'Update Time',
			'is_cod' => 'Is Cod',
			'element_num' => 'Element Num',
			'is_recommend' => 'Is Recommend',
			'is_detachable' => 'Is Detachable',
			'is_show' => 'Is Show',
			'is_buy' => 'Is Buy',
			'standard_number' => 'Standard Number',
			'is_mine' => 'Is Mine',
			'unit' => 'Unit',
			'sales_total' => 'Sales Total',
			'sales_month' => 'Sales Month',
			'texture_id' => 'Texture',
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

		$criteria->compare('product_id',$this->product_id,true);
		$criteria->compare('parent_id',$this->parent_id,true);
		$criteria->compare('cat_id',$this->cat_id,true);
		$criteria->compare('cat_alias_id',$this->cat_alias_id,true);
		$criteria->compare('brand_id',$this->brand_id,true);
		$criteria->compare('brandhall_id',$this->brandhall_id,true);
		$criteria->compare('product_name',$this->product_name,true);
		$criteria->compare('product_sn',$this->product_sn,true);
		$criteria->compare('product_number',$this->product_number);
		$criteria->compare('warn_number',$this->warn_number);
		$criteria->compare('product_weight',$this->product_weight,true);
		$criteria->compare('product_desc',$this->product_desc,true);
		$criteria->compare('product_summary',$this->product_summary,true);
		$criteria->compare('product_img',$this->product_img,true);
		$criteria->compare('is_on_sale',$this->is_on_sale);
		$criteria->compare('market_price',$this->market_price,true);
		$criteria->compare('shop_price',$this->shop_price,true);
		$criteria->compare('promote_price',$this->promote_price,true);
		$criteria->compare('promote_discount',$this->promote_discount,true);
		$criteria->compare('promote_start_date',$this->promote_start_date,true);
		$criteria->compare('promote_end_date',$this->promote_end_date,true);
		$criteria->compare('goods_type',$this->goods_type,true);
		$criteria->compare('keywords',$this->keywords,true);
		$criteria->compare('click_count',$this->click_count,true);
		$criteria->compare('like_count',$this->like_count,true);
		$criteria->compare('sort',$this->sort);
		$criteria->compare('is_delete',$this->is_delete);
		$criteria->compare('is_new',$this->is_new);
		$criteria->compare('is_hot',$this->is_hot);
		$criteria->compare('is_promote',$this->is_promote);
		$criteria->compare('is_check',$this->is_check);
		$criteria->compare('add_time',$this->add_time,true);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('is_cod',$this->is_cod);
		$criteria->compare('element_num',$this->element_num);
		$criteria->compare('is_recommend',$this->is_recommend);
		$criteria->compare('is_detachable',$this->is_detachable);
		$criteria->compare('is_show',$this->is_show);
		$criteria->compare('is_buy',$this->is_buy);
		$criteria->compare('standard_number',$this->standard_number,true);
		$criteria->compare('is_mine',$this->is_mine);
		$criteria->compare('unit',$this->unit,true);
		$criteria->compare('sales_total',$this->sales_total);
		$criteria->compare('sales_month',$this->sales_month);
		$criteria->compare('texture_id',$this->texture_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Product the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
