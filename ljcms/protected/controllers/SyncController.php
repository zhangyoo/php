<?php
	/**
	 * 
	 * @author zhangyong
	 * @ps:此类作为数据同步使用
	 */
	class SyncController extends Controller
	{
        /**
		 * 初始化对象
		 * @author zhangyong
		 */
        public $CtoG=array(
                //cms同步到gezlife的数据库
                0=>array(
                    'tbl_mold_material_relation', 'tbl_mold_style_relation', 'tbl_space_element_relation','tbl_element_layer',  
                    'tbl_element_room_category', 'tbl_element_style_relation','tbl_element_product_relation'
                ),
                //gezlife同步到cms的数据库
                1=>array(
                    'sp_attribute', 'sp_goods_attr','sp_product', 'tbl_brandhall','tbl_style','tbl_brand','tbl_category','tbl_material',
                    'tbl_product_category_relation', 'tbl_product_material_relation', 'tbl_product_style_relation'
                ),
                //cms和gezlife互相同步数据库
                2=>array(
                    //@PS 这里的表只能在cms上添加数据
                    'tbl_mold', 'tbl_space', 'tbl_showroom','tbl_element'
                )
            );
        //同步实时更新的数据表
//        public $batchTable=array(
//            'tbl_showroom_element_relation','tbl_showroom_brand_relation','tbl_showroom_category_relation','tbl_product_element_relation'
//        );
        
        //同步图片
        public $batchImages=array(
            'tbl_mold', 'tbl_space', 'tbl_showroom','tbl_element','sp_product'
        );
        
        /**
		 * 数据表列表
		 * @author zhangyong
		 */
		public function actionIndex()
		{
            $dbArray=$this->CtoG;
            $this->render('index',array('dbArray'=>$dbArray));
        }
        
        /**
		 * 批量同步数据
		 * @author zhangyong
		 */
		public function actionBatchSync()
		{
            $data=array('status'=>false,'info'=>'数据同步失败！');
            $dbCms=Yii::app()->db;//cms的数据库连接
            $dbGezlife=Yii::app()->db2;//gezlife的数据库连接
            $dbArray=$this->CtoG;//所有要更新的数据
            $batchImages=$this->batchImages;//所有要同步的图片
            $CimgDomain='http://static.local-ljcms.com/';//cms的图片域名,如：http://static.local-ljcms.com/
            $GimgDomain='http://static.leju.com/';//gezlife的图片域名,如：http://static.leju.com/
            try{
                if(isset($_POST['tables'])){
                    $tables=$_POST['tables'];
                    foreach ($tables as $table){
                        if(in_array($table, $dbArray[0])){
                            //cms同步到gezlife的数据库
                            $this->singleSync($table,array($dbCms,$dbGezlife));  
                            //同步图片
//                            if(in_array($table, $batchImages)){
//                               $this->batchImages($table,array($CimgDomain,$GimgDomain)); 
//                            }
                        }elseif(in_array($table, $dbArray[1])){
                            //gezlife同步到cms的数据库
                            $this->singleSync($table,array($dbGezlife,$dbCms)); 
                            //同步图片
//                            if(in_array($table, $batchImages)){
//                               $this->batchImages($table,array($GimgDomain,$CimgDomain)); 
//                            }
                        }else{
                            //cms和gezlife互相同步数据库
                            $this->bothSync($table,array($dbCms,$dbGezlife));
                            //同步图片
//                            if(in_array($table, $batchImages)){
//                               $this->batchImages($table,array($CimgDomain,$GimgDomain)); 
//                            }
//                            if(in_array($table, $batchImages)){
//                               $this->batchImages($table,array($GimgDomain,$CimgDomain)); 
//                            }
                        }
                    }
                }
            }  catch (Exception $e){
                throw new CHttpException(500,$e->getMessage());
            }
        }
        
        /**
		 * 单方向同步数据
         * @PS:$table覆盖的表(主库)
         * @PS:$options[0]=主库(覆盖表的数据库连接配置)
         * @PS:$options[1]=副库(被覆盖表的数据库连接配置)
		 * @author zhangyong
		 */
		public function singleSync($table,$options=array())
		{
            $A=$options[0];
            $B=$options[1];
            $sql="select * from ".$table." ";
            $tableA=$A->createCommand($sql)->queryAll();
            if(!empty($tableA)){
                //删除B数据库$table表的数据
                $sql="delete from ".$table." ";
                $tableB=$B->createCommand($sql)->execute();
                foreach ($tableA as $ta){
                    $k=implode(',',array_keys($ta));//表的所有字段组成的字符串
                    $v=array_values($ta);//单条记录的值组成的数组
                    $valString='';//存储转换之后的键值
                    //@todo 改进时判断数据的类型，整型空为0，字符串空为''，改进为在原有数据基础上更改，而不是整个删除然后覆盖
                    foreach ($v as $kk=>$vv){
                        if($kk==0){
                           $valString .="'".$vv."'"; 
                        }else{
                            if($vv==null){
                                $valString .=",0";
                            }else{
                                $valString .=",'".$vv."'";
                            }
                        }
                    }
                    //覆盖B数据库$table表
                    $sql="replace into ".$table." (".$k.") values (".$valString.")";
                    $B->createCommand($sql)->execute();
                }
            }
        }
        
        /**
		 * 双向同步数据
         * @PS:$table覆盖的表
         * @PS:$options[0]=cms数据库配置
         * @PS:$options[1]=gezlife数据库配置
		 * @author zhangyong
		 */
		public function bothSync($table,$options=array())
		{
            $A=$options[0];
            $B=$options[1];
            $sql="select * from ".$table." ";
            $tableC=$A->createCommand($sql)->queryAll();
            $sql="select * from ".$table." ";
            $tableG=$B->createCommand($sql)->queryAll();
            $tabNameArray=explode('_', $table);
            $tabModel='';
            //获取$table表的model
            foreach ($tabNameArray as $kna=>$tabNA){
                if($kna>0){
                    $tabModel .= ucfirst($tabNA);
                }
            }
            //获取$table表的主键
            $pri=CActiveRecord::model($tabModel)->tableSchema->primaryKey;
            if(!empty($pri)){
                $primary=array();
                if(is_array($pri)){
                    $primary=$pri;
                }else{
                    $primary[]=$pri;
                }
                if(!empty($tableC)){
                    foreach ($tableC as $vc){
                        $tabcon="";//按主键搜索条件
                        foreach ($primary as $ky=>$py){
                            if($ky==0){
                                $tabcon .= "".$py."=".$vc[$py];
                            }else{
                                $tabcon .= " and ".$py."=".$vc[$py];
                            }
                        }
                        $colum=  array_keys($vc);//表的所有字段组成的数组
                        $sql="select * from ".$table." where ".$tabcon;
                        $tabGdata=$B->createCommand($sql)->queryRow();
                        if(!empty($tabGdata)){
                            //更新数据
                            if($vc['update_time'] > $tabGdata['update_time']){
                                foreach ($colum as $cm){
                                    $upString='';
                                    if($vc[$cm]!=$tabGdata[$cm]){
                                        //@todo 改进时判断数据的类型，整型空为0，字符串空为''
                                        if($vc[$cm]==null){
                                            $upString .="".$cm."=0";
                                        }else{
                                            $upString .="".$cm."='".$vc[$cm]."'";
                                        }
                                        $sql="update ".$table." set ".$upString." where ".$tabcon;
                                        $B->createCommand($sql)->execute();
                                    }
                                }
                            }elseif($vc['update_time'] < $tabGdata['update_time']){
                                foreach ($colum as $cm){
                                    $upString='';
                                    if($vc[$cm]!=$tabGdata[$cm]){
                                        //@todo 改进时判断数据的类型，整型空为0，字符串空为''
                                        if($vc[$cm]==null){
                                            $upString .="".$cm."=0";
                                        }else{
                                            $upString .="".$cm."='".$tabGdata[$cm]."'";
                                        }
                                        $sql="update ".$table." set ".$upString." where ".$tabcon;
                                        $A->createCommand($sql)->execute();
                                    }
                                }
                            }
                        }else{
                            //添加数据
                            $nk=implode(',',array_keys($vc));//表的所有字段组成的字符串
                            $vk=array_values($vc);//单条记录的值组成的数组
                            $addValString='';
                            //@todo 改进时判断数据的类型，整型空为0，字符串空为''
                            foreach ($vk as $kkn=>$vvn){
                                if($kkn==0){
                                   $addValString .="'".$vvn."'"; 
                                }else{
                                    if($vvn==null){
                                        $addValString .=",0";
                                    }else{
                                        $addValString .=",'".$vvn."'";
                                    }
                                }
                            }  
                            $sql="replace into ".$table." (".$nk.") values (".$addValString.")";
                            $B->createCommand($sql)->execute();
                        }
                    }
                }
            }
        }
        
        /**
		 * 同步图片
         * @PS:$table同步图片的表
         * @PS:$options[0]=被同步图片的域名,如：http://static.local-ljcms.com/
         * @PS:$options[1]=图片同步到的域名,如：http://static.leju.com/
		 * @author zhangyong
		 */
		public function actionBatchImages($table,$options=array())
		{
//            $table,$options=array();
            $from_dir=$options[0];
            $to_dir=$options[0];
//            $table='tbl_test';
//            $from_dir='http://static.local-ljcms.com/upload/';
//            $to_dir='http://static.leju.com/upload/';
            $table=explode('_', $table);
            $table=$table[1];//数据表的图片文件夹
            if(!is_dir($from_dir.$table)){
                throw new CHttpException(404,'目录名'.$from_dir.$table.'不存在或者已经删除！.');
            }else{
                if(!is_dir($to_dir.$table)){
                    mkdir($to_dir.$table,0777);
                }else{
                    $handleY=dir($from_dir.$table);
                    while($entryY=$handleY->read()) {
                        if(($entryY!=".")&&($entryY!="..")){
                            if(!is_dir($to_dir.$table."/".$entryY)){
                                xCopy($from_dir.$table."/".$entryY,$to_dir.$table."/".$entryY,true);
                            }else{
                                $handleM=dir($from_dir.$table."/".$entryY);
                                while($entryM=$handleM->read()) {
                                    if(($entryM!=".")&&($entryM!="..")){
                                        if(!is_dir($to_dir.$table."/".$entryY."/".$entryM)){
                                            xCopy($from_dir.$table."/".$entryY."/".$entryM,$to_dir.$table."/".$entryY."/".$entryM,true);
                                        }else{
                                            $handleD=dir($from_dir.$table."/".$entryY."/".$entryM);
                                            while($entryD=$handleD->read()) {
                                                if(($entryD!=".")&&($entryD!="..")){
                                                    if(!is_dir($to_dir.$table."/".$entryY."/".$entryM."/".$entryD)){
                                                        xCopy($from_dir.$table."/".$entryY."/".$entryM."/".$entryD,$to_dir.$table."/".$entryY."/".$entryM."/".$entryD,true);
                                                    }else{
                                                        $handleImg=dir($from_dir.$table."/".$entryY."/".$entryM."/".$entryD);
                                                        while($entryImg=$handleImg->read()) {
                                                            if(($entryImg!=".")&&($entryImg!="..")){
                                                                if(!is_dir($to_dir.$table."/".$entryY."/".$entryM."/".$entryD."/".$entryImg)){
                                                                    Copy($from_dir.$table."/".$entryY."/".$entryM."/".$entryD."/".$entryImg,$to_dir.$table."/".$entryY."/".$entryM."/".$entryD."/".$entryImg);
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                } 
                
            }
        }
        
    }