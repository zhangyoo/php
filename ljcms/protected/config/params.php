<?php

return array(
    'adminEmail'=>'gezlife@foxmail',
    'pageSize'=>24,//默认一页显示的数量
    'adminRole'=>1,
    'upYun'=>false,//是否同步上传云盘，true=是，false=否
    'roomCategories'=>array('1'=>'客厅','2'=>'卧室','3'=>'厨房','4'=>'卫生间','5'=>'书房','6'=>'儿童房','7'=>'餐厅','8'=>'特殊房'),
    'schoolType'=>array('1'=>'幼儿园','2'=>'小学','3'=>'初中','4'=>'高中','5'=>'大学'),
    'productType'=>array('0'=>'家具','1'=>'硬装','2'=>'电器','3'=>'配饰'),//元素的类型
    'namwPType'=>array('JJ'=>'0','YZ'=>'1','DQ'=>'2','PS'=>'3','H'=>'4'),//字母对应的元素类型
    'moldType'=>array('0'=>'高模(MAX)','1'=>'低模(AWD)','2'=>'阴影模型(AWD)','3'=>'阴影模型(FBX)','4'=>'低模(MAX)','5'=>'低模(FBX)','6'=>'低模(MD2/5)','7'=>'低模(U3D)'),
    'moldNameType'=>array('GMAX'=>'0','DAWD'=>'1','YAWD'=>'2','YFBX'=>'3','DMAX'=>'4','DFBX'=>'5','DMD'=>'6','DU3D'=>'7'),
    'imgCondition'=>array('1'=>'透视图','2'=>'顶视图','3'=>'UV贴图','4'=>'法线贴图','5'=>'360度'),
    'imgColumn'=>array('TS'=>'image','DS'=>'floorplan','UV'=>'uv_map','FX'=>'normal_map','GG'=>'specular_map','MUV'=>'m_uv_map','MFX'=>'m_normal_map'),
    'orderType'=>array('0'=>'建模型单','1'=>'渲染订单','2'=>'新空间渲染订单','3'=>'贴图订单'),
    'orderStatus'=>array('0'=>'未提交','1'=>'未开始','2'=>'进行中','3'=>'已完成'),
    'infoStatus'=>array('0'=>'待制作','1'=>'进行中','2'=>'已完成'),
    'allowCinfo'=>array(0,3),//可创建素材的订单类型
    'typeAllow'=>array('0'=>'mold','1'=>'space','2'=>'space','3'=>'mold'),//区分操作模块，用户获取相关的接受任务用户，0,1,2,3为订单类型
    'YYForm'=>array('2'=>'YAWD','3'=>'YFBX'),//阴影模型的文件格式
    'labelType'=>array('2'=>'基础构建','3'=>'家装家居','4'=>'涂装'),//标签类型
    //配置地图调用
    'maps'=>'baidu',
    
    
    'cms'=>'www.local-ljcms.com',
    'flash'=>'http://www.leju.cn/extFlash/diy',
    'static'=>'http://static.cms.com',//图片服务器
    'realPathOfStatic'=>'D:/www/static',//上传的静态资源根目录
);

