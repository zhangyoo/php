<?php
namespace common\models;

use yii\db\ActiveRecord;

/**
 * 
 * common下的model的基类
 */
class Common extends ActiveRecord
{
    /**
     * 在调用 save()、insert()、update() 这三个方法时，会自动调用yii\base\Model::validate()方法
     * 在校验之前会调用的方法
     */
    public  function beforeValidate()
    {
        if($this->isNewRecord){
            $this->create_time = time();
        }else{
            $this->update_time = time();
        }
        
        return ActiveRecord::beforeValidate();
    }

    /**
     * 创建数据
     */
    public function _create()
    {
        
    }
    
    /**
     * 更新数据
     */
    public function _update()
    {
        
    }
    
    /**
     * 查询数据
     */
    public function _select()
    {
        
    }
    
    /**
     * 删除数据
     */
    public function _delete()
    {
        
    }
    
    
}