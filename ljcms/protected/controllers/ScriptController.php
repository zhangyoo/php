<?php
/**
 * 数据脚本，只执行一遍
 */
class ScriptController extends CmsController
{
    //更改贴图的名称、法线图(改为json数据)、UV贴图(改为json数据)、制作人(XXX)
    public function actionUpdateTextureOne()
    {
        $connection=Yii::app()->db;
        try{
            $transaction=$connection->beginTransaction();
            $molds = Mold::model()->findAll("is_del=0 and texture_id is not null and is_old=1");
            if(!empty($molds)){
                foreach ($molds as $mold){
                    $textureIds = json_decode($mold['texture_id'],true);
                    if(empty($mold['texture_id']) || empty($textureIds))
                        continue;
                    foreach ($textureIds as $tid=>$v){
                        $texture = Texture::model()->findByPk($tid);
                        if(!empty($texture)){
                            $texture->name = 'M-'.$mold['id'].'-T-'.$v;
                            $texture->save();
                        }
                    }
                }
            }
            $transaction->commit();
            echo 'successs';
        } catch (Exception $e) {
            $transaction->rollback();
            throw new CHttpException(500,$e->getMessage());//测试时使用
        }
    }
    public function actionUpdateTextureTwo()
    {
        $connection=Yii::app()->db;
        try{
            $transaction=$connection->beginTransaction();
            $textures = Texture::model()->findAll();
            if(!empty($textures)){
                    foreach ($textures as $tid){
                        $texture = Texture::model()->findByPk($tid['id']);
                        if(!empty($texture)){
                            $texture->maker = 'XXX';
                            if(!empty($texture['uv_map'])){
                                if($texture['uv_map'] =='//'){
                                    $texture->uv_map = '';
                                }else{
                                    $texture->uv_map = json_encode (array($texture['uv_map']));
                                }
                            }
                            if(!empty($texture['normal_map'])){
                                if($texture['normal_map'] =='//'){
                                    $texture->normal_map = '';
                                }else{
                                    $texture->normal_map = json_encode (array($texture['normal_map']));
                                }
                            }
                            $texture->save();
                        }
                    }
            }
            $transaction->commit();
            echo 'successs';
        } catch (Exception $e) {
            $transaction->rollback();
            throw new CHttpException(500,$e->getMessage());//测试时使用
        }
    }
    //修改元素的脚本
    public function actionUpdateElement()
    {
        $connection=Yii::app()->db;
        try{
            $transaction=$connection->beginTransaction();
            $elements = Element::model()->findAll("is_del=0");
            $na = array('R','RR','Y','H');
            $nt = array('0'=>'JJ','1'=>'YZ','2'=>'DQ','3'=>'PS','4'=>'H');
            if(!empty($elements)){
                foreach ($elements as $element){
                    $names = explode('_', $element['name']);
                    if(!in_array($names[0], $na))
                            continue;
                    $e = Element::model()->findByPk($element['id']);
                    $isM = '';
                    if($names[0] == 'R' && count($names) == 9)
                        $isM = 'N';
                    if(in_array($names[0], array('RR','Y','H')))
                            $isM = 'N';
                    if($names[0] == 'R' && count($names) == 10){
                        if(empty($names[5])){
                            $isM = 'N';
                            unset($names[5]);
                        }else{
                            $isM = 'Y';
                        }
                    }
                    $names[0] = $nt[$element['type']].'_'.$isM;
                    $e->name = implode('_', $names);
                    $e->save();
                }
            }
            $transaction->commit();
            echo 'successs';
        } catch (Exception $e) {
            $transaction->rollback();
            throw new CHttpException(500,$e->getMessage());//测试时使用
        }
    }
    //修改元素的脚本
    public function actionUpdateElementMaterial()
    {
        $connection=Yii::app()->db;
        try{
            $transaction=$connection->beginTransaction();
            $sql = 'SELECT e.id,e.is_del,mmr.material_id FROM `tbl_element` as e 
                INNER JOIN tbl_mold_material_relation as mmr on e.mold_id=mmr.mold_id 
                where e.mold_id is not null and e.mold_id!=0 and e.is_del=0';
            $em = $connection->createCommand($sql)->queryAll();
            if(!empty($em)){
                foreach ($em as $e){
                    $ElementMaterialRelation = new ElementMaterialRelation();
                    $ElementMaterialRelation->element_id = $e['id'];
                    $ElementMaterialRelation->material_id = $e['material_id'];
                    $ElementMaterialRelation->save();
                }
            }
            $transaction->commit();
            echo 'successs';
        } catch (Exception $e) {
            $transaction->rollback();
            throw new CHttpException(500,$e->getMessage());//测试时使用
        }
    }
    
    //把有模型的素材归到对应的订单下
    public function actionInfoToOrder()
    {
        $Joid = '1';//建模订单id
        $Toid = '2';//贴图订单id
        $infos = Info::model()->findAll("is_del=0");
        if(!empty($infos)){
            foreach ($infos as $info){
                $OIR = new OrderInfoRelation();
                $OIR->order_id = $Toid;
                if(empty($info['texture_id']))
                    $OIR->order_id = $Joid;
                $OIR->info_id = $info['id'];
                $OIR->save();
            }
            echo 'success';
        }
    }
    //转移和木居的商品
    public function actionToHMJ()
    {
        $Jid = '4';
        $products = Product::model()->with('molds')->findAll("t.brandhall_id=5 and t.is_delete=0 and t.cat_id in (select category_id from tbl_category_extra)");
        $connection=Yii::app()->db;
        try{
            $transaction=$connection->beginTransaction();
            if(!empty($products)){
                foreach ($products as $p){
                    if(count($p['molds'])<2){
                        $info = new Info();
                        $info->title = $p['product_name'];
                        $info->item = $p['product_sn'];
                        $info->image = $p['product_img'];
                        $info->category_id = $p['cat_id'];
                        $info->brand_id = $p['brand_id'];
                        $info->texture_id = $p['texture_id'];
                        $info->type = '0';
                        $info->status = '0';
                        $info->product_id = $p['product_id'];
                        $info->brandhall_id = $p['brandhall_id'];
                        if($info->save()){
                            $info->number = date('Ymd',$p['add_time'])."N".$info->id;
                            if(!empty($p['molds'])){
                                $mold = $p['molds'][0];
                                $info->length = $mold['length'];
                                $info->width = $mold['width'];
                                $info->height = $mold['height'];
                                $info->status = '1';
                                $info->mold_condition = json_encode(array($mold['mold_type']=>$mold['id'])); 
                                $infoMR = new InfoMoldRelation();
                                $infoMR->info_id = $info->id;
                                $infoMR->mold_id = $mold['id'];
                                $infoMR->save();
                            }
                            $info->save();
                            $OIR = new OrderInfoRelation();
                            $OIR->order_id = $Jid;
                            $OIR->info_id = $info->id;
                            $OIR->save();
                        }

                    }
                }
            }
            $transaction->commit();
            echo 'successs';
        } catch (Exception $e) {
            $transaction->rollback();
            throw new CHttpException(500,$e->getMessage());//测试时使用
        }
    }
    //修改已命名的元素名称
    private $root='D:/www/static/test';//测试时使用
    public function actionUpdateName()
    {
        ini_set("max_execution_time", 3600);  //用此function才能真正在运行时设置
           try{
               $na = array('R','RR','Y','H');
               $nt = array('R'=>'JJ','RR'=>'JJ','Y'=>'YZ','PS'=>'PS');
               ignore_user_abort();//关掉浏览器，PHP脚本也可以继续执行.
				//正式机模型路径
 				$source=$this->root."/source";//上传的源文件
 				$backup=$this->root."/rename";//备份文件
                $files=scandir($source);
                foreach ($files as $file)
                {
                    if('.'==$file || '..'==$file || is_dir($file))
						continue;
					$filename=basename($file);
					if(false===strpos($filename,'_') || false===strpos($filename, "."))
						continue;
                    $names = explode('_', $filename);
                    if(!in_array($names[0], $na))
                            continue;
                    $isM = '';
                    if($names[0] == 'R')
                        $isM = 'Y';
                    if($names[0] != 'R')
                        $isM = 'N';
                    $names[0] = $nt[$names[0]].'_'.$isM;
                    $rename = implode('_', $names);
                    rename($source.'/'.$file, $backup.'/'.$rename);
                }
                echo 'success';
           } catch (Exception $e) {
               throw new CHttpException(500,$e->getMessage());
           }
    }
    
    //转移素材中的商品关系数据到表tbl_info_product_relation
    public function actionInfoPR()
    {
        $infos = Info::model()->findAll("t.is_del=0 and t.product_id !=0");
        $connection=Yii::app()->db;
        try{
            $transaction=$connection->beginTransaction();
            if(!empty($infos)){
                foreach ($infos as $info){
                    $pd = Product::model()->findByPk($info['product_id']);
                    $con = '';
                    if(isset($pd['parent_id']) && !empty($pd['parent_id']))
                        $con .= ' or parent_id='.$pd['parent_id'];
                    $products = Product::model()->findAll(array(
                        'select'=>'product_id,brandhall_id',
                        'condition'=>'is_delete=0 and (product_id='.$info['product_id'].' or parent_id='.$info['product_id'].$con.')',
                        'group'=>'product_id',
                    ));
                    if(!empty($products)){
                        foreach ($products as $p){
                            $InfoProductRelation = new InfoProductRelation();
                            $InfoProductRelation->info_id = $info['id'];
                            $InfoProductRelation->product_id = $p['product_id'];
                            $InfoProductRelation->brandhall_id = $p['brandhall_id'];
                            $InfoProductRelation->save();
                        }
                    }
                }
            }
            $transaction->commit();
            echo 'successs';
        } catch (Exception $e) {
            $transaction->rollback();
            throw new CHttpException(500,$e->getMessage());//测试时使用
        }
    }
    
    //输出ip地址
    public function actionGetIp()
    {
        header('Content-Type:text/html;Charset=utf-8'); 
//        var_dump(Yii::app()->request->userHostAddress);
        $citys = $this->GetIpLookup(Yii::app()->request->userHostAddress);
//        var_dump($citys);
        $cityName = $citys['city'].'市';
        $with = array(
            'region'=>array(
                'on'=>"region.is_del=0 and region.name='".$cityName."'",
            ),
        );
        $BrandhallRegion = BrandhallRegion::model()->with($with)->findAll();
        var_dump($BrandhallRegion);
    }
    
    function GetIp(){  
        $realip = '';  
        $unknown = 'unknown';  
        if (isset($_SERVER)){  
            if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown)){  
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);  
                foreach($arr as $ip){  
                    $ip = trim($ip);  
                    if ($ip != 'unknown'){  
                        $realip = $ip;  
                        break;  
                    }  
                }  
            }else if(isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP']) && strcasecmp($_SERVER['HTTP_CLIENT_IP'], $unknown)){  
                $realip = $_SERVER['HTTP_CLIENT_IP'];  
            }else if(isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR']) && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown)){  
                $realip = $_SERVER['REMOTE_ADDR'];  
            }else{  
                $realip = $unknown;  
            }  
        }else{  
            if(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), $unknown)){  
                $realip = getenv("HTTP_X_FORWARDED_FOR");  
            }else if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), $unknown)){  
                $realip = getenv("HTTP_CLIENT_IP");  
            }else if(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), $unknown)){  
                $realip = getenv("REMOTE_ADDR");  
            }else{  
                $realip = $unknown;  
            }  
        }  
        $realip = preg_match("/[\d\.]{7,15}/", $realip, $matches) ? $matches[0] : $unknown;  
        return $realip;  
    } 

    function GetIpLookup($ip = ''){  
        if(empty($ip)){  
            $ip = $this->GetIp();  
        }  
        $res = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip);  
        if(empty($res)){ return false; }  
        $jsonMatches = array();  
        preg_match('#\{.+?\}#', $res, $jsonMatches);  
        if(!isset($jsonMatches[0])){ return false; }  
        $json = json_decode($jsonMatches[0], true);  
        if(isset($json['ret']) && $json['ret'] == 1){  
            $json['ip'] = $ip;  
            unset($json['ret']);  
        }else{  
            return false;  
        }  
        return $json;  
    } 
}

