<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	public $layout='column1';
    
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
    
    protected $_partial=false;//设置是否局部渲染
    
    public $request;
    public $params;
    
    public $pageSize=24;//默认一页显示的数量
    public $page; //string页码
    public $pages=array(); //array页码,后期用于扩展一个页面有多个页码的情况
    //特殊权限控制
    public $OAuth = array('update','delete','upMold','bindProduct');
    
	/**
     * 初始化
     */
	public function init()
	{
		parent::init();
        $this->request = Yii::app()->request;
        $this->params=Yii::app()->params;
        $uid=Yii::app()->user->getId();
        $this->parentChild($uid);
	}
    
    /**
     * 重载render方法
     * @param type $view
     * @param type $data
     * @param type $return
     */
    public function render($view,$data=null,$return=false)
    {
        $partial=$this->request->getParam('partial');
        if(null!==$partial)
            $this->_partial=true;
        if($this->_partial)//局部渲染
            $this->renderPartial($view,$data,$return);
        else
            parent::render($view,$data,$return);
    }
    
    /**
	 * Performs the AJAX validation.
	 * @param  $model the model to be validated
	 */
	public function performAjaxValidation($model)
	{
        $ajax=$this->request->getParam('ajax');
		if(!empty($ajax))
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
    
    /*
     * 获取用户权限子父级节点并转换为全局变量
     * @author zhangyong
     */
    protected function parentChild($uid){
        if(!empty($uid)){//用户存在
            //获取用户是否有编辑和删除的权限
            if(!empty($this->OAuth)){
                foreach ($this->OAuth as $auth){
                    Yii::app()->session[$auth]='';
                    $Allocation = AuthAssignment::model()->find("userid=".$uid." and (itemname='".$auth."' or itemname='administrator')");
                    if(!empty($Allocation))
                        Yii::app()->session[$auth]=$auth;
                }
            }
            //获取该用户父级导航
            $sql='select itemname from cms_auth_assignment where userid='.$uid;
            $userControlData=Yii::app()->db->createCommand($sql)->queryAll();
            $adminArray=array();
            $brandhallArray=array();
            $parentChild=array();
            foreach ($userControlData as $key=>$ucd){
                $adminArray[$key]=$ucd['itemname'];//该用户平台后台父级导航
            }
            //判断用户是否有超管权限（作用域为平台后台）
            if(in_array('administrator',$adminArray)){
                Yii::app()->session['topNav']='admin';
                Yii::app()->session['leftArray']='admin';
            }else{
                Yii::app()->session['topNav']=$adminArray;
                foreach($adminArray as $parentNav){
                    $sql="select child from cms_auth_item_child where parent='".$parentNav."'";
                    $childData=Yii::app()->db->createCommand($sql)->queryAll();
                    foreach($childData as $ky=>$chd){
                        $parentChild[$parentNav][$ky]=$chd['child'];
                    }
                }
                Yii::app()->session['leftArray']=$parentChild;
            }
        }
    }
    
    /*
     * 返回后台导航(控制显示的导航)
     * @author zhangyong
     */
    protected function navlist($tempArray){
        $adminAllNav=new AllNav;
        if(isset($tempArray) && !empty($tempArray)){
            if($tempArray[0]=='admin_top'){
                $topNav=$tempArray[1];
                //单个分组涉及多个控制器的情况
                $tncArray=array();
                if(!empty($topNav) && $topNav != 'admin'){
                    $sql="select child from cms_auth_item_child where parent in "
                            . "(select itemname from cms_auth_assignment where userid=".Yii::app()->user->getId().") group by child";
                    $tnChild=Yii::app()->db->createCommand($sql)->queryAll();
                    if(!empty($tnChild)){
                        foreach ($tnChild as $tnc){
                            $tncA=  explode('_', $tnc['child']);
                            if(!in_array($tncA[0], $tncArray))
                                    array_push($tncArray, $tncA[0]);
                        }
                    }
                }
                $data[]=null;
                $topNav_array=$adminAllNav->adminControl();
                $nav=array();
                $controlName=array();
                if(!empty($topNav_array)){
                    foreach($topNav_array as $key=>$val){
                        $compareControl=$key;
                        if($topNav == 'admin'){//用户拥有超管权限
                            if(isset($val['style']) && $val['style']=='top'){
                                $nav[$val['url']]=$val['cname'];
                                $controlName[$val['url']]=$compareControl;
                            }   
                        }elseif(!empty($tncArray) && isset($val['style']) && $val['style']=='top' && in_array($key,$tncArray)){
                            $nav[$val['url']]=$val['cname'];
                            $controlName[$val['url']]=$compareControl;
                        }
                    }
                }
                $data['nav']=$nav;
                $data['controlName']=$controlName;
                return $data;
            }elseif($tempArray[0]=='admin_left'){
                $left=$tempArray[1];
                $leftArray=$tempArray[2];
                $adminAllNav=new AllNav;
                $left_array=$adminAllNav->adminControl();
                $leftNav=array();
                if(!empty($left_array)){
                    if(isset($left) && !empty($left)){
                        if(isset($left_array[$left]['leftList']) && !empty($left_array[$left]['leftList'])){
                            foreach ($left_array[$left]['leftList'] as $url=>$name){
                                if($leftArray=='admin'){//用户拥有超管权限
                                    $leftNav[$url]=$name;
                                }elseif(!empty($leftArray) && $leftArray!='admin'){
                                    if(!empty($url)){
                                       $leftCompareUrl=explode('/', $url);
                                        $leftControlAct=$leftCompareUrl[1].'_'.$leftCompareUrl[2];
                                        if(!empty($leftControlAct)){
                                            foreach($leftArray as $leftlist){
                                                if(in_array($leftControlAct,$leftlist)){
                                                    $leftNav[$url]=$name;
                                                }
                                            }
                                        } 
                                    }
                                    
                                }
                            }   
                        }
                    }
                }
                return $leftNav;
            }
        }
    }
    
    /**
     * 获取列表信息方法
     * $sql 搜索条件
     * @author zhangyong
     */
    public function getIndex($sql)
    {
        $data=array();
        $connect=Yii::app()->db;
        $criteria=new CDbCriteria();
        $result = $connect->createCommand($sql)->query();
        $count = $result->rowCount;
        $pages=new CPagination($count);
        $pages->pageSize= intval(Yii::app()->params['pageSize']);
        $pages->applyLimit($criteria);
        $result=$connect->createCommand($sql." LIMIT :offset,:limit");
        $result->bindValue(':offset', $pages->currentPage*$pages->pageSize);
        $result->bindValue(':limit', $pages->pageSize);
        $ul=$result->queryAll();
        $data['list']=$ul;
        $data['pages']=$pages;
        $data['count']=$count;
        return $data;
    }
    
       /**
        * 获取默认的数据（标签，分类，品牌，风格，颜色，材质）
        * @author zhangyong
        */
       public function getDefault()
       {
           $default=array();
           //颜色数据
           $colors=array();
           $colorObj = new COLORS;
           $colorData = $colorObj->colorsControl();
           if(!empty($colorData)){
               foreach ($colorData as $co){
                   $coArray=explode("|",$co);
                   $colors[trim($co)]=$coArray[0];
               }
           }
           //品类数据
           $category=array();
           $category['second']['empty'] = '请选择';
           $category['third']['empty'] = '请选择';
           $categorys=  Category::model()->findAll(array('select'=>'id,name','condition'=>'is_show=1 and ( parent_id is null or parent_id=0 ) and (brandhall_id is null or brandhall_id=0)'));
           $category['top']=CHtml::listData($categorys,'id','name');
           //品牌数据
           $brands=array();
           $brandsData=Brand::model()->findAll('is_del=0 and is_show=1 and ( parent_id is null or parent_id=0 )');
           $brands['top']=CHtml::listData($brandsData,'id','name');
           $brands['second']['empty'] = '请选择';
           //品牌馆数据
            $brandhall=array();
            $brandhallData=  Brandhall::model()->findAll(array('select'=>'id,name',
                'condition'=>'is_del=0 and is_show=1 and is_check=1'));
            $brandhall['top']=CHtml::listData($brandhallData,'id','name');
           //风格数据
           $styles=array();
           $styleData=Style::model()->findAll();
           $styles=CHtml::listData($styleData,'id','name');
           //材质数据
           $materials=array();
           $materialData=  Material::model()->findAll(array('select'=>'id,name','condition'=>'(parent_id is null or parent_id=0)'));
           $materials['top']=CHtml::listData($materialData,'id','name');
           $materials['second']['empty'] = '请选择';

           return $default=array('colors'=>$colors,'category'=>$category,'brands'=>$brands,'styles'=>$styles,'materials'=>$materials,'brandhall'=>$brandhall);;
       }
       
       /**
        * 
        * 写日志
        * @param string $message
        * @param string $level info warning error
        * @param string $filename
        */
       protected function log($message,$level='info',$filename='info.txt')
       {
           $now=date('Y/m/d H:i:s');
           $root=YiiBase::getPathOfAlias('webroot')."/protected/messages/";
           if(!is_dir($root))
           {
               mkdir($root);
           }	
           $filename=$root.$filename;
           $handle=fopen($filename, 'a');//追加方式
           fwrite($handle, $now." [".$level."] : ".$message."\r\n");
           fclose($handle);
       }
       
       /**
        * 
        * 删除单张图片
        * @param string $image 图片的绝对地址
        */
       protected function deleteImage($image)
       {
           if(file_exists($image))
               unlink($image);
           $this->deleteDefaultThumb($image);
       }
       
       /**
        * 
        * 删除默认生成的缩略图
        * @param string $image
        */
       protected function deleteDefaultThumb($image)
       {
           $ext=substr($image, strrpos($image, '.')+1);
           $filename=$image.'_thumb.'.$ext;
           if(file_exists($filename))
               unlink($filename);
       }
       
       /**
        * 
        * 删除多个尺寸的缩略图
        * @param string $image
        * @param array $imageSizes array(array('width'=>300,'height'=>300),array('width'=>150,'height'=>150))
        */
       protected function deleteThumbs($image,$imageSizes=array())
       {
           $ext=substr($image, strrpos($image, '.')+1);
           foreach ($imageSizes as $imageSize)
           {
               if(isset($imageSize['width']) && isset($imageSize['height']))
               {
                   $filename=$image.'_'.$imageSize['width'].'X'.$imageSize['height'].'.'.$ext;
               }
               elseif(isset($imageSize['width']) && !isset($imageSize['height']))
               {
                   $filename=$image.'_w'.$imageSize['width'].'.'.$ext;
               }
               elseif(!isset($imageSize['width']) && isset($imageSize['height']))
               {
                   $filename=$image.'_h'.$imageSize['height'].'.'.$ext;
               }
               if(file_exists($filename))
                   unlink($filename);
           }
       }
       
       /**
        * 获取一组对象
        * @param string $ar
        * @param CDbCriteria $criteria 搜索条件
        * @param array $options page=>true 分页
        * 
        * @author fengchuan
        */
       public function findAll($ar, $criteria=null, $options=array())
       {
           if(null===$criteria)
           {
               $criteria=new CDbCriteria();
           }
           if(isset($options['page']) && true===$options['page'])
           {//分页
               $count=$ar::model()->count($criteria);
               $this->page=new CPagination($count);
               $this->page->pageSize=$this->pageSize;
               $this->page->applyLimit($criteria);
           }
           $models=$ar::model()->findAll($criteria);
           return $models;
       }
       
       /**
        * 获取已选的品牌、材质
        * @author zhangyong
        * $id 为已保存的brand_id或者material_id
        * $params为存储model数据的数组，如:array('model'=>'Brand')
        */
       public function BMsel($id,$params)
       {
           $second=array();$pid='';$secid='';
           $data=array('second'=>$second,'pid'=>$pid,'secid'=>$secid);
           $model=trim($params['model']);
           $model=ucfirst($model);//首字母大写
           $model=new $model;
           $selData= $model->find(array('select'=>'parent_id','condition'=>'id='.$id));//存储的品牌/材质数据
           if(!empty($selData)){
               if(empty($selData['parent_id'])){
                    $secondData=  $model->findAll(array('select'=>'id,name','condition'=>'parent_id='.$id));
                    $second=CHtml::listData($secondData,'id','name');//二级分类数据
                    $pid=$id;//父级id
                    $secid='';
                }else{
                    $secondData=  $model->findAll(array('select'=>'id,name','condition'=>'parent_id='.$selData['parent_id']));
                    $second=CHtml::listData($secondData,'id','name');//二级分类数据
                    $pid=$selData['parent_id'];//父级id
                    $secid=$id;
                }
           }
           
           return $data=array('second'=>$second,'pid'=>$pid,'secid'=>$secid);
       }
        
        /**
		 * 模型/素材已选分类
		 * @author zhangyong
		 */
        public function selCat($category_id)
        {
            $data=array();
            $selectCat=array();
            $top_id=null;
            $second_id=null;
            $second=  Category::model()->find(array('select'=>'parent_id','condition'=>'id='.$category_id));
            if(!empty($second)){
                $second_id=$second['parent_id'];//二级分类id
                $thirdData=Category::model()->findAll(array('select'=>'id,name','condition'=>'parent_id='.$second_id));
                $thirds=CHtml::listData($thirdData,'id','name');//该二级分类下的子类
                if(!empty($thirds))
                    $selectCat[$category_id]=$thirds;
                $top=Category::model()->find(array('select'=>'parent_id','condition'=>'id='.$second_id));
                $top_id=$top['parent_id'];//一级分类id
                $secondData=Category::model()->findAll(array('select'=>'id,name','condition'=>'parent_id='.$top_id));
                $seconds=CHtml::listData($secondData,'id','name');//该一级分类下的二级分类
                if(!empty($thirds))
                    $selectCat[$top_id]=$seconds;
            }
            return $data=array('selectCat'=>$selectCat,'top_id'=>$top_id,'second_id'=>$second_id);
        }
        
        
}