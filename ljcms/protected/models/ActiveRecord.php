<?php
/**
 * 
 * model对象基类
 */
class ActiveRecord extends CActiveRecord
{
    public $pages;//分页
    
    /**
     * 保存前校验
     * 调用save方法的时候会自动调用
     * @return boolean whether validation should be executed. Defaults to true.
	 * If false is returned, the validation will stop and the model is considered invalid.
     * @author fengchuan
     */
    protected function beforeValidate()
	{
		if($this->isNewRecord)
		{
			$this->create_time=time();
			empty($this->creater_id) && $this->creater_id=Yii::app()->user->getId();
		}
		else
		{
			$this->update_time=time();
			empty($this->updater_id) && $this->updater_id=Yii::app()->user->getId();
		}
		return CActiveRecord::beforeValidate();
	}
    /**
     * 转换对象为数组
     * @param CActiveRecord $model 
     * @param array/string $columns 要保留的列,数组或‘,’分隔的字符串
     * @return array 
     * @author fengchuan
     */
    protected static function _toArray($model, $columns=null)
    {
        $attributes=$model->attributes;
        //过滤掉不要显示的列
        if(null!==$columns)
        {
            if(is_string($columns))
            {
                $columns=  explode(',', $columns);
            }
            if(is_array($columns))
            {
                foreach ($attributes as $key=>$value)
				{
					if(!in_array($key, $columns))
						unset($attributes[$key]);
				}
            }
        }
        return $attributes;
    }
    /**
     * 转换对象为数组
     * 
     * 使用方式：$criteria->select为要检索的列,如果是全部列就不需要带$criteria->select
     * ActiveRecord::toArray($models, $criteria->select);
     * 
     * @param type $models
     * @param array/string $columns 要保留的列,数组或‘,’分隔的字符串
     * @param string $index 要作为数组key的列名
     * @return array
     * @author fengchuan
     */
    public static function toArray($models, $columns=null, $index=null)
    {
        $ret=array();
        if(!is_array($models))
        {
            $models=array($models);
        }
        if(null!==$index)
        {
            foreach ($models as $model)
            {
                if(!isset($model->$index))
                    continue;
                $ret[$model->$index]=self::_toArray($model, $columns);
            }
        }
        else
        {
            foreach ($models as $model)
            {
                $ret[]=self::_toArray($model, $columns);
            }
        }
        return $ret;
    }
    
    /**
     * 设置排序
     * @param int $sort
     * @return boolean
     * @todo:待完善
     */
    public function sort($sort)
    {
        $this->sort_num=$sort;
        return $this->save();
    }
    
    
    
    /**
     * 
     * @param CActiveRecord $model
     * @param CDbCriteria $criteria 
     * @param int $brandhallId
     * @return boolean whether the saving succeeds
     * @author fengchuan
     */
    public function saveModel($model, $criteria=null, $brandhallId=null)
    {
        $tableId=Table::tableId($model->tableName());
        $this->brandhall_id=$brandhallId;
        $this->obj_id=$model->id;
        $this->table_id=$tableId;
        return $this->save();
    }
    
    /**
     * Finds a single active record that has the specified attribute values.
     * @param CActiveRecord $model
     * @param CDbCriteria $criteria 
     * @param int $brandhallId
     * @return CActiveRecord the record found. Null if none is found.
     * @author fengchuan
     */
    public function findModel($model, $criteria=null, $brandhallId=null)
    {
        $tableId=Table::tableId($model->tableName());
        $attributes=array(
            'brandhall_id'=>$brandhallId,
            'obj_id'=>$model->id,
            'table_id'=>$tableId
        );
        return $this->findByAttributes($attributes);
    }
    
    /**
     * Finds all active records that have the specified attribute values.
     * @param CActiveRecord $model
     * @param CDbCriteria $criteria 
     * @param int $brandhallId
     * @return CActiveRecord[] the records found. An empty array is returned if none is found.
     * @author fengchuan
     */
    public function findAllModel($model, $criteria=null, $brandhallId=null)
    {
        $tableId=Table::tableId($model->tableName());
        $attributes=array(
            'brandhall_id'=>$brandhallId,
            'obj_id'=>$model->id,
            'table_id'=>$tableId
        );
        return $this->findAllByAttributes($attributes);
    }
    
    /**
     * Deletes rows which match the specified attribute values.
     * @param CActiveRecord $model
     * @param CDbCriteria $criteria 
     * @param int $brandhallId
     * @return integer number of rows affected by the execution.
     * @author fengchuan
     */
    public function deleteAllModel($model, $criteria=null, $brandhallId=null)
    {
        $tableId=Table::tableId($model->tableName());
        $attributes=array(
            'brandhall_id'=>$brandhallId,
            'obj_id'=>$model->id,
            'table_id'=>$tableId
        );
        return $this->deleteAllByAttributes($attributes);
    }
}

