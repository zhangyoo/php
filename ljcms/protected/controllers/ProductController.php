<?php
	/**
	 * 
	 * @author zhangyong
     * 商品管理 2014/10/29 
	 */
	class ProductController extends CmsController
	{
        /**
		 * 商品列表
		 * @author zhangyong
		 */
		public function actionIndex()
		{
            $like = '';//查询订单条件
            $params=$_POST;
            $seachData=array('name'=>'','brandhall'=>'','timeStart'=>'','timeEnd'=>'');
            if(isset($_POST['brandhall']) && intval($_POST['brandhall'])>0){
                $like.= " and brandhall_id = ".intval($_POST['brandhall']);
                $seachData['brandhall']=$_POST['brandhall'];
            }
            if(isset($params['name']) && !empty($params['name'])){
                $like .= " and ( product_name like '%".trim($params['name'])."%' or product_sn like '%".trim($params['name'])."%' )";
                $seachData['name']=$params['name'];
            }
            if(isset($_POST['timeStart']) && !empty($_POST['timeStart'])){
                $like.= " and add_time >= '".strtotime(trim($_POST['timeStart']))."'";
                $seachData['timeStart']=$_POST['timeStart'];
            }
            if(isset($_POST['timeEnd']) && !empty($_POST['timeEnd']))
            {
                $like.= " and add_time <= '".strtotime(trim($_POST['timeEnd']))."'";
                $seachData['timeEnd']=$_POST['timeEnd'];
            }
            $sql = "select * from sp_product where is_delete =0 and is_show=1 $like order by add_time desc";
            $data=$this->getIndex($sql);
            $ul=$data['list'];
            $pages=$data['pages'];
            //品牌馆初始数据
            $brandhalls = Brandhall::model()->findAll(array('select'=>'id,name','condition'=>'is_del=0 and is_show=1 and is_check=1'));
            if(!empty($brandhalls)){
                $brandhalls=CHtml::listData($brandhalls,'id','name');
            }
            //品牌系列
            $brands = Brand::model()->findAll(array('select'=>'id,name','condition'=>'is_del=0 and is_show=1 and is_check=1'));
            if(!empty($brands)){
                $brands=CHtml::listData($brands,'id','name');
            }
            if(!empty($ul)){
                foreach ($ul as $k=>$val){
                    $ul[$k]['brandName'] = '';
                    if(!empty($brands)){
                        if(!empty($val['brand_id'])){
                            $Bsel=$this->BMsel($val['brand_id'],array('model'=>'Brand'));
                            if(empty($Bsel['secid'])){
                                $ul[$k]['brandName'] = $brands[$Bsel['pid']];
                            }else{
                                $ul[$k]['brandName'] = $brands[$Bsel['pid']].'-'.$brands[$Bsel['secid']];
                            }
                        }
                    }
                }
            }
            $this->render('index',array('ul'=>$ul,'pages' => $pages,'seachData'=>$seachData,'brandhalls'=>$brandhalls));
        }
        
        
    }