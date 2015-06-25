<?php
class AllNav
{
   private $admin=array(
        'order' => array(
            'cname'=>'信息管理',
            'style'=>'top',//用于区分是否是顶部导航
            'url'=>'/order/index',
            'leftList'=>array(
                 "/order/index"=>'订单管理',
//                 "/info/index"=>'素材管理',
                 "/label/index"=>'标签列表',
//                 "/product/index"=>'商品列表',
             )
          ),
        'mold' => array(
            'cname'=>'模型管理',
            'style'=>'top',//用于区分是否是顶部导航
            'url'=>'/mold/index',
            'leftList'=>array(
                 "/mold/index"=>'模型列表',
                 "/task/order/mold"=>'订单列表',
                 "/task/index/mold"=>'任务列表',
             )
          ),
        'space' => array(
            'cname'=>'效果管理',
            'style'=>'top',//用于区分是否是顶部导航
            'url'=>'/space/index',
            'leftList'=>array(
                 "/element/index"=>'元素列表',
                 "/space/index"=>'空间列表',
//                 "/space/create"=>'创建空间',
                 "/task/order/space"=>'订单列表',
                 "/task/index/space"=>'任务列表',
             )
          ),
        'user' => array(
            'cname'=>'用户管理',
            'style'=>'top',//用于区分是否是顶部导航
            'url'=>'/user/index',
            'leftList'=>array(
                "/user/index"=>'用户列表',
                "/user/create"=>'创建用户',
             )
          ),
        'authority' => array(
            'cname'=>'权限管理',
             'style'=>'top',//用于区分是否是顶部导航
            'url'=>'/authority/index',
            'leftList'=>array(
                 "/authority/index"=>'分组列表',
                 "/authority/create"=>'创建分组',
             )
          ),
        'message' => array(
            'cname'=>'站内信管理',
             'style'=>'top',//用于区分是否是顶部导航
            'url'=>'/message/index',
            'leftList'=>array(
                 "/message/index"=>'站内信列表',
             )
          ),
//        'sync' => array(
//            'cname'=>'其他管理',
//            'style'=>'top',//用于区分是否是顶部导航
//            'url'=>'/sync/index',
//            'leftList'=>array(
//                "/sync/index"=>'数据库',
//             )
//          ),
        'info' => array(
            'cname'=>'',
            'url'=>'',
            'leftList'=>array(
                 "/order/index"=>'订单管理',
//                 "/info/index"=>'素材管理',
             )
          ),
         'product' => array(
            'cname'=>'商品管理',
            'url'=>'',
            'leftList'=>array(
                 "/order/index"=>'订单管理',
//                 "/info/index"=>'素材管理',
                 "/label/index"=>'标签列表',
//                 "/product/index"=>'商品列表',
             )
          ),
         'showroom' => array(
            'cname'=>'',
            'url'=>'',
            'leftList'=>array(
                 "/element/index"=>'元素列表',
                 "/space/index"=>'空间列表',
                 "/task/order/space"=>'订单列表',
                 "/task/index/space"=>'任务列表',
             )
          ),
         'default' => array(
            'cname'=>'',
            'url'=>'',
            'leftList'=>array(
                 "/default/index"=>'系统信息',
                 "/message/index"=>'站内信管理',
             )
          ),
         'node' => array(
            'cname'=>'',
            'url'=>'',
            'leftList'=>array(
                 "/element/index"=>'元素列表',
                 "/space/index"=>'空间列表',
//                 "/space/create"=>'创建空间',
                 "/task/order/space"=>'订单列表',
                 "/task/index/space"=>'任务列表',
             )
          ),
          'element' => array(
            'cname'=>'',
            'url'=>'',
            'leftList'=>array(
                 "/element/index"=>'元素列表',
                 "/space/index"=>'空间列表',
//                 "/space/create"=>'创建空间',
                 "/task/order/space"=>'订单列表',
                 "/task/index/space"=>'任务列表',
             )
          ),
          'label' => array(
            'cname'=>'标签管理',
            'url'=>'',
            'leftList'=>array(
                 "/order/index"=>'订单管理',
//                 "/info/index"=>'素材管理',
                 "/label/index"=>'标签列表',
//                 "/product/index"=>'商品列表',
             )
          ),
       
    );
   
   function adminControl(){
       return $this->admin;
   }
}
?>

