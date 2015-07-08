<?php
/**
 * 
 * 生产系统接口
 * @author fengchuan <gezlife@foxmail.com>
 */
class ScxtFlashController extends Controller
{
    private $mod=array(
                    '1'=>array('1'=>'_login'),
                    '2'=>array('1'=>'_getSpaces', '2'=>'_getSpaceImage', '3'=>'_getNodes', '4'=>'_saveNodes'),
                    '3'=>array('1'=>'_getSpacesPlaned', '2'=>'_getNodesPlaned', '3'=>'_getConditions', '4'=>'_search'),
                    '9'=>array('1'=>'_getError')//错误模块
                );
    /**
     * flash访问入口
     */
    public function actionMain()
    {
        try {
            $params=$_POST;
//            $params=$_GET;//测试
            if(!isset($params['mod']) || !isset($params['act']))
            {
                throw new CException('缺少必要参数...');
            }
            if(isset($this->mod[$params['mod']]) && isset($this->mod[$params['mod']][$params['act']]))
            {
                $name=$this->mod[$params['mod']][$params['act']];
                $this->$name($params);
            }
        } catch (Exception $e) {
           // echo $e->getTraceAsString();
           $this->_getError($params);
        }
    }
    
    /**
     * 用户登录
     */
    private function _login($params)
    {
        try {
            $user=new User();
            $user->username=trim($params['uname']);
            $user->password=trim($params['pw']);
            $identity = new UserIdentity($params['uname'], $params['pw']);
            $identity->authenticate();
            if ($identity->errorCode === UserIdentity::ERROR_NONE) {//登录成功
                $duration = isset($params['rememberMe']) ? 3600 * 24 * 30 : 0; // 30 days
                Yii::app()->user->login($identity, $duration);
            }
            $node=array('attr'=>array('event'=>1001,'result'=>1,'code'=>1,'uid'=>'','tagName'=>'node'));
            $isExisted=$user->isExisted(2);
            if($isExisted)
            {//存在
                $user=User::model()->find("username='".$user->username."' and password='".$user->encrypt($user->password)."'");
                $node['attr']['uid']=$user->id;
            }
            else
            {
            	$node['attr']['result']=0;
                $node['attr']['code']=0;
            }
        } catch (Exception $e) {
           $node['attr']['result']=0;
           $node['attr']['code']=$e->getMessage();
        } 
        $root['attr']=array('reqTime'=>time(),'uid'=>$node['attr']['uid'],'tagName'=>'root');
        $root['node']=array($node);
        header('Content-Type:text/xml;charset=utf-8');
        echo $this->_formatData($root);
    }
    /**
     * 获取空间分类
     */
    private function _getSpaces($params)
    {
        try {
            $root['attr']=array('reqTime'=>time(),'uid'=>'','tagName'=>'root');
            $node['attr']=array('event'=>'2001','result'=>1,'code'=>1,'edit'=>$params['edit'], 'tagName'=>'node');
            if(!isset($params['uid']) || intval($params['uid'])<0)
            {
                throw new CException('未登录...');
            }
            $root['attr']['uid']=$params['uid'];
            if(0!=$params['edit'] && 1!=$params['edit'])
            {
                throw new CException('参数错误...');
            }
            switch ($params['edit'])
            {
                case 0:
                    $spaces=Space::model()->findAll('status=0 and is_del=0');//未规划空间
                    break;
                case 1:
                    $spaces=Space::model()->findAll('status>0 and is_del=0');//已规划空间
                    break;
            }
            
            if(!empty($spaces))
            {
                $roomCategories=array();
                for ($i=0;$i<count($spaces);$i++)
                {
                    if(!isset($roomCategories[$spaces[$i]['room_category']]))
                        $roomCategories[$spaces[$i]['room_category']]=array();
                    $roomCategories[$spaces[$i]['room_category']][]=$spaces[$i];
                }
                foreach ($roomCategories as $roomCategory=>$spaces)
                {
                    $type[$roomCategory]=array();
                    $type[$roomCategory]['attr']=array('id'=>$roomCategory, 'name'=>Yii::app()->params['roomCategories'][$roomCategory], 'tagName'=>'type');
                    $type[$roomCategory]['node']=array();
                    for($i=0;$i<count($spaces);$i++)
                    {
                        $space=array();
                        $space['attr']=array('id'=>$spaces[$i]['id'], 'name'=>$spaces[$i]['name'], 'tagName'=>'space');
                        //获取层级数据
                        !empty($spaces[$i]['showpics'])? $spaces[$i]['showpics']=CJSON::decode($spaces[$i]['showpics']):$spaces[$i]['showpics']=array();
                        !empty($spaces[$i]['floorplan'])? $spaces[$i]['floorplan']=CJSON::decode($spaces[$i]['floorplan']):$spaces[$i]['floorplan']=array();
                        $layerData=  Node::model()->findAll(array('select'=>'layer','condition'=>'space_id='.$spaces[$i]['id']));
                        $layerArray=array();
                        if(!empty($layerData)){
                            foreach ($layerData as $lr){
                                $lda=CJSON::decode($lr['layer']);
                                if(!empty($lda)){
                                    foreach ($lda as $kl=>$vda){
                                        if(!in_array($kl, $layerArray)){
                                            $layerArray[]=$kl;
                                        }
                                    }
                                }
                            }
                        }
                        $visual=array();
                        if(!empty($spaces[$i]['showpics'])){
                            foreach ($spaces[$i]['showpics'] as $angle=>$sp){
                                $visualTemp=array();
                                if(intval($params['edit'])==0){
                                    $visualTemp['attr']=array('id'=>$angle, 'name'=>$angle, 'tagName'=>'visual', 'previewURL'=>$sp);
                                    $visual[]=$visualTemp;//未规划层级
                                }else{
                                    if(!empty($layerArray) && in_array($angle, $layerArray)){
                                        $visualTemp['attr']=array('id'=>$angle, 'name'=>$angle, 'tagName'=>'visual', 'previewURL'=>$sp);
                                        $visual[]=$visualTemp; //已规划层级
                                    }
                                }
                            }
                        }
                        $space['node']=$visual;
                        $type[$roomCategory]['node'][]=$space;
                    }
                }
                $node['node']=$type;
            }
        } catch (Exception $e) {
            $node['attr']['result']=0;
            $node['attr']['code']=$e->getMessage();
        }
        $root['node']=array($node);
        header('Content-Type:text/xml;charset=utf-8'); 
        echo $this->_formatData($root);
    }
    /**
     * 获取空间规划原图（新建规划）
     */
    private function _getSpaceImage($params)
    {
        try {
            $root['attr']=array('reqTime'=>time(),'uid'=>'','tagName'=>'root');
            $node['attr']=array('event'=>'2002','result'=>1,'code'=>1, 'tagName'=>'node');
            if(!isset($params['uid']) || intval($params['uid'])<0)
            {
                throw new CException('未登录...');
            }
            $root['attr']['uid']=$params['uid'];
            if(!isset($params['typeid']) || !isset($params['spaceid']) || !isset($params['visualid']))
            {
                throw new CException('缺少参数...');
            }
            $space=Space::model()->find('id='.$params['spaceid'].' and is_del=0');
            if(empty($space))
            {
                throw new CException('空间不存在');
            }
            !empty($space['floorplan'])?$space['floorplan']=CJSON::decode($space['floorplan']):$space['floorplan']=array();
            if(empty($space['floorplan']))
            {
                throw new CException('空间平面布局图不存在');
            }
            if(empty($space['floorplan'][$params['visualid']]))
            {
                throw new CException('该视角没有对应的图片...');
            }
            $node['attr']['width']=$space['max_width'];
            $node['attr']['height']=$space['max_height'];
            $node['attr']['picURL']=$space['floorplan'][$params['visualid']];
        } catch (Exception $e) {
            $node['attr']['result']=0;
            $node['attr']['code']=$e->getMessage();
        }
        $root['node']=array($node);
        header('Content-Type:text/xml;charset=utf-8'); 
        echo $this->_formatData($root);
    }
    /**
     * 获取空间规划图以及规划信息（编辑规划）
     */
    private function _getNodes($params)
    {
        try {
            $root['attr']=array('reqTime'=>time(),'uid'=>'','tagName'=>'root');
            $node['attr']=array('event'=>'2003','result'=>1,'code'=>1, 'tagName'=>'node');
            if(!isset($params['uid']) || intval($params['uid'])<0)
            {
                throw new CException('未登录...');
            }
            $root['attr']['uid']=$params['uid'];
            if(!isset($params['typeid']) || !isset($params['spaceid']) || !isset($params['visualid']))
            {
                throw new CException('缺少参数...');
            }
            $space=Space::model()->find('id='.$params['spaceid'].' and is_del=0');
            if(empty($space))
            {
                throw new CException('空间不存在');
            }
            !empty($space['floorplan'])?$space['floorplan']=CJSON::decode($space['floorplan']):$space['floorplan']=array();
            if(empty($space['floorplan']))
            {
                throw new CException('空间平面布局图不存在');
            }
            if(empty($space['floorplan'][$params['visualid']]))
            {
                throw new CException('该视角没有对应的图片...');
            }
            $node['attr']['width']=$space['max_width'];
            $node['attr']['height']=$space['max_height'];
            $node['attr']['picURL']=$space['floorplan'][$params['visualid']];
            $node['node']=array();
            $sql='select n.id as nid, n.name, n.layer as deep, n.distance, a.x, a.y, a.width, a.height, a.id, n.type
                    from tbl_node as n left join tbl_area as a on n.area_id=a.id
                        where n.space_id='.$params['spaceid'];
            $areas=Yii::app()->db->createCommand($sql)->queryAll();
            foreach($areas as $area)
            {
                $area['deep']=json_decode($area['deep'], true);
                if(1!=$area['type'])
                    $area['type']=0;
                $temp['attr']=array('tagName'=>'area', 'id'=>$area['id'], 'type'=>$area['type'], 'nid'=>$area['nid'], 
                                    'x'=>$area['x'], 'y'=>$area['y'], 'w'=>$area['width'], 'h'=>$area['height'], 
                                    'distance'=>$area['distance'], 'deep'=>$area['deep'][$params['visualid']], 'name'=>$area['name']
                                );
                $node['node'][]=$temp;
            }
        } catch (Exception $e) {
            $node['attr']['result']=0;
            $node['attr']['code']=$e->getMessage();
        }
        $root['node']=array($node);
        header('Content-Type:text/xml;charset=utf-8'); 
        echo $this->_formatData($root);
    }
    /**
     * 提交空间规划信息
     */
    private function _saveNodes($params)
    {
        try {
            $root['attr']=array('reqTime'=>time(),'uid'=>'','tagName'=>'root');
            $nodeTag['attr']=array('event'=>'2004','result'=>1,'code'=>1, 'tagName'=>'node');
            if(!isset($params['uid']) || intval($params['uid'])<0)
            {
                throw new CException('未登录...');
            }
            $root['attr']['uid']=$params['uid'];
            if(!isset($params['typeid']) || !isset($params['spaceid']) || !isset($params['visualid']) || !isset($params['jsondata']))
            {
                throw new CException('缺少参数...');
            }
            $space=Space::model()->find('id='.$params['spaceid'].' and is_del=0');
            if(empty($space))
            {
                throw new CException('空间不存在');
            }
            $transaction=Yii::app()->db->beginTransaction();
            $data=json_decode($params['jsondata'], true);
            $space->status=1;
            $space->save();
            $nodeIds=array();
            foreach ($data['data'] as $soft)
            {//软装区域
                $soft['id']=  intval($soft['id']);
                $area=new Area();
                if(isset($soft['id']) && intval($soft['id'])>0)
                {
                    $area=Area::model()->findByPk($soft['id']);
                }
                
                $area->x=$soft['x'];
                $area->y=$soft['y'];
//                $area->length=$soft['l'];//后期需要加上
                $area->width=$soft['w'];
                $area->height=$soft['h'];
                $area->distance=$soft['distance'];
                if(!isset($soft['id']) || intval($soft['id'])<=0)
                    $area->isNewRecord = TRUE;
                if(!$area->save())
                    throw new CException('保存区域失败...');
                if(isset($soft['nid']) && intval($soft['nid'])>0)
                {
                    $node=Node::model()->findByPk($soft['nid']);
                    $layer=json_decode($node->layer, true);
                }
                else
                {
                    $node=new Node();
                    $layer=array();
                }
                $layer[$params['visualid']]=$soft['deep'];
                $node->layer=json_encode($layer);
                $node->area_id=$area->id;
                $node->space_id=$params['spaceid'];
                $node->distance=$soft['distance'];
                $node->type=0;
                $node->name=$soft['name'];
                if(!isset($soft['nid']) || intval($soft['nid'])<=0)
                    $node->isNewRecord = TRUE;  
                if(!$node->save())
                    throw new CException('保存层级失败...');
                $nodeIds[]=$node->id;
            }
            foreach ($data['hard'] as $hard)
            {//硬装区域
                if(empty($hard))
                    continue;
                if(isset($hard['nid']) && intval($hard['nid'])>0)
                {
                    $node=Node::model()->findByPk($hard['nid']);
                    $layer=json_decode($node->layer, true);
                }
                else
                {
                    $node=new Node();
                    $layer=array();
                }
                $layer[$params['visualid']]=$hard['deep'];
                $node->layer=json_encode($layer);
                $node->area_id=0;
                $node->space_id=$params['spaceid'];
                $node->distance=$hard['distance'];
                $node->type=1;
                $node->name=$hard['name'];
                if(!isset($hard['nid']) || intval($hard['nid'])<=0)
                    $node->isNewRecord = TRUE;
                if(!$node->save())
                    throw new CException('保存层级失败...');
                $nodeIds[]=$node->id;
            }
            //查询非新添加的层级
            if(!empty($nodeIds))
            {
                $delNodes=Node::model()->findAll(array('select'=>'id,layer','condition'=>"id not in (".implode(',', $nodeIds).") and space_id=".$params['spaceid']));
            }
            else
            {
                $delNodes=Node::model()->updateAll(array('select'=>'id,layer','condition'=>'space_id='.$params['spaceid']));
            }
            if(!empty($delNodes)){
                $nids=array();
                $delNodes=CHtml::listData($delNodes,'id','layer');
                foreach ($delNodes as $kid=>$vly){
                    $temp=json_decode($vly, true);
                    if(in_array($params['visualid'], array_keys($temp)))
                            $nids[]= $kid;
                }
                Node::model()->deleteAll('id in ('.implode(',', $nids).')');//删除被遗弃的层级
            }
            $transaction->commit();
        } catch (Exception $e) {
            $nodeTag['attr']['result']=0;
            $nodeTag['attr']['code']=$e->getMessage();
            if(isset($transaction))
                $transaction->rollback();
        }
        $root['node']=array($nodeTag);
        header('Content-Type:text/xml;charset=utf-8'); 
        echo $this->_formatData($root);
    }
    /**
     * 获取可下单空间分类
     */
    private function _getSpacesPlaned($params)
    {
        try {
            $root['attr']=array('reqTime'=>time(),'uid'=>'','tagName'=>'root');
            $node['attr']=array('event'=>'3001','result'=>1,'code'=>1, 'tagName'=>'node');
            if(!isset($params['uid']) || intval($params['uid'])<0)
            {
                throw new CException('未登录...');
            }
            $root['attr']['uid']=$params['uid'];
            $spaces=Space::model()->findAll('status>0 and is_del=0');
            
            if(!empty($spaces))
            {
                $roomCategories=array();
                for ($i=0;$i<count($spaces);$i++)
                {
                    if(!isset($roomCategories[$spaces[$i]['room_category']]))
                        $roomCategories[$spaces[$i]['room_category']]=array();
                    $roomCategories[$spaces[$i]['room_category']][]=$spaces[$i];
                }
                foreach ($roomCategories as $roomCategory=>$spaces)
                {
                    $type[$roomCategory]=array();
                    $type[$roomCategory]['attr']=array('id'=>$roomCategory, 'name'=>Yii::app()->params['roomCategories'][$roomCategory], 'tagName'=>'type');
                    $type[$roomCategory]['node']=array();
                    for($i=0;$i<count($spaces);$i++)
                    {
                        $space=array();
                        $space['attr']=array('id'=>$spaces[$i]['id'], 'name'=>$spaces[$i]['name'], 'tagName'=>'space');
                        //获取层级数据
                        !empty($spaces[$i]['showpics'])? $spaces[$i]['showpics']=CJSON::decode($spaces[$i]['showpics']):$spaces[$i]['showpics']=array();
                        !empty($spaces[$i]['floorplan'])? $spaces[$i]['floorplan']=CJSON::decode($spaces[$i]['floorplan']):$spaces[$i]['floorplan']=array();
                        $layerData=  Node::model()->findAll(array('select'=>'layer','condition'=>'space_id='.$spaces[$i]['id']));
                        $layerArray=array();
                        if(!empty($layerData)){
                            foreach ($layerData as $lr){
                                $lda=CJSON::decode($lr['layer']);
                                if(!empty($lda)){
                                    foreach ($lda as $kl=>$vda){
                                        if(!in_array($kl, $layerArray)){
                                            $layerArray[]=$kl;
                                        }
                                    }
                                }
                            }
                        }
                        $visual=array();
                        if(!empty($spaces[$i]['showpics'])){
                            foreach ($spaces[$i]['showpics'] as $angle=>$sp){
                                $visualTemp=array();
                                if(!empty($layerArray) && in_array($angle, $layerArray)){
                                    $visualTemp['attr']=array('id'=>$angle, 'name'=>$angle, 'tagName'=>'visual', 'previewURL'=>$sp);
                                    $visual[]=$visualTemp; //已规划层级
                                }
                            }
                        }
                        $space['node']=$visual;
                        $type[$roomCategory]['node'][]=$space;
                    }
                }
                $node['node']=$type;
            }
        } catch (Exception $e) {
            $node['attr']['result']=0;
            $node['attr']['code']=$e->getMessage();
        }
        $root['node']=array($node);
        header('Content-Type:text/xml;charset=utf-8'); 
        echo $this->_formatData($root);
    }
    
    /**
     * 获取选择的可下单空间信息
     */
    private function _getNodesPlaned($params)
    {
        try {
            $root['attr']=array('reqTime'=>time(),'uid'=>'','tagName'=>'root');
            $node['attr']=array('event'=>'3002','result'=>1,'code'=>1, 'tagName'=>'node');
            if(!isset($params['uid']) || intval($params['uid'])<0)
            {
                throw new CException('未登录...');
            }
            $root['attr']['uid']=$params['uid'];
            if(!isset($params['typeid']) || !isset($params['spaceid']) || !isset($params['visualid']))
            {
                throw new CException('缺少参数...');
            }
            $space=Space::model()->find('id='.$params['spaceid'].' and is_del=0');
            if(empty($space))
            {
                throw new CException('空间不存在');
            }
            !empty($space['floorplan'])?$space['floorplan']=CJSON::decode($space['floorplan']):$space['floorplan']=array();
            if(empty($space['floorplan']))
            {
                throw new CException('空间平面布局图不存在');
            }
            if(empty($space['floorplan'][$params['visualid']]))
            {
                throw new CException('该视角没有对应的图片...');
            }
            $node['attr']['file']='\\\\192.168.16.250\\';//@todo:空间模型的存放绝对路径
            $node['attr']['savePath']='\\\\192.168.16.250\\source\\elements';//元素存放的绝对路径
            $node['attr']['width']=$space['max_width'];
            $node['attr']['height']=$space['max_height'];
            $node['attr']['picURL']=$space['floorplan'][$params['visualid']];
            $node['node']=array();
            $sql='select n.id as nid, n.name, n.layer as deep, n.distance, a.x, a.y, a.width, a.height, a.id, n.type
                    from tbl_node as n left join tbl_area as a on n.area_id=a.id
                        where n.space_id='.$params['spaceid'];
            $areas=Yii::app()->db->createCommand($sql)->queryAll();
            foreach($areas as $area)
            {
                $area['deep']=json_decode($area['deep'], true);
                if(1!=$area['type'])
                    $area['type']=0;
                $temp['attr']=array('tagName'=>'area', 'id'=>$area['id'], 'type'=>$area['type'], 'nid'=>$area['nid'], 
                                    'x'=>$area['x'], 'y'=>$area['y'], 'w'=>$area['width'], 'h'=>$area['height'], 
                                    'distance'=>$area['distance'], 'deep'=>$area['deep'][$params['visualid']], 'name'=>$area['name']
                                );
                $node['node'][]=$temp;
            }
        } catch (Exception $e) {
            $node['attr']['result']=0;
            $node['attr']['code']=$e->getMessage();
        }
        $root['node']=array($node);
        header('Content-Type:text/xml;charset=utf-8'); 
        echo $this->_formatData($root);
    }
    /**
     * 获取物件检索条件
     */
    public function _getConditions($params)
    {
        try {
            $root['attr']=array('reqTime'=>time(),'uid'=>'','tagName'=>'root');
            $node['attr']=array('event'=>'3003','result'=>1,'code'=>1, 'tagName'=>'node');
            if(!isset($params['uid']) || intval($params['uid'])<0)
            {
                throw new CException('未登录...');
            }
            $root['attr']['uid']=$params['uid'];
            //品牌系列
            $brandsTag=array();
            $brandsTag['attr']=array('tagName'=>'brands');
            $tempArr=Brand::model()->findAll('is_del=0');
            $brands=array();
            //@todo:此处可以考虑想想是否有更优解决方案
            foreach ($tempArr as $brand)
            {
                if (empty($brand['parent_id'])) 
                {
                    $brands['brand_' . $brand['id']] = array('id' => $brand['id'], 'name' => $brand['name'], 'series' => array());
                } 
                else 
                {
                    !isset($brands['brand_' . $brand['parent_id']]) && $brands['brand_' . $brand['parent_id']] = array();
                    $brands['brand_' . $brand['parent_id']]['series'][] = array('id' => $brand['id'], 'name' => $brand['name']);
                }
            }
            foreach ($brands as $key => $brand) 
            {
                //过滤掉品牌已删除而系列未删除的项
                if (!isset($brand['id'])) 
                {
                    unset($brands[$key]);
                    continue;
                }   
                $brandTag=array();
                $brandTag['attr']=array('id'=>$brand['id'], 'name'=>$brand['name'], 'tagName'=>'brand');
                foreach ($brands[$key]['series'] as $series)
                {
                    $brandTag['node'][]=array('attr'=>array('id'=>$series['id'], 'name'=>$series['name'], 'tagName'=>'series'));
                }
                $brandsTag['node'][]=$brandTag;
            }
            //品类
            $typeTag=array();
            $typeTag['attr']=array('tagName'=>'type');
            $categories=  Category::model()->findAll('is_show=1 and ( parent_id is null or parent_id=0 ) and brandhall_id is null');
            foreach ($categories as $type)
            {
                $temp=array();
                $temp['attr']=array('id'=>$type['id'], 'name'=>$type['name'], 'tagName'=>'item');
                $typeTag['node'][]=$temp;
            }
            //风格
            $styleTag=array();
            $styleTag['attr']=array('tagName'=>'style');
            $styles=  Style::model()->findAll();
            foreach ($styles as $style)
            {
                $temp=array();
                $temp['attr']=array('id'=>$style['id'], 'name'=>$style['name'], 'tagName'=>'item');
                $styleTag['node'][]=$temp;
            }
            
            //颜色
            $colorsTag=array();
            $colorsTag['attr']=array('tagName'=>'colors');
            $defaultData=$this->getDefault();
            $colors=array_values($defaultData['colors']);
            foreach ($colors as $kcr=>$color)
            {
                $temp=array();
                $temp['attr']=array('id'=>$color, 'name'=>$color, 'tagName'=>'color');
                $colorsTag['node'][]=$temp;
            }
            
            $node['node']=array($brandsTag, $typeTag, $styleTag, $colorsTag);
        } catch (Exception $e) {
            $node['attr']['result']=0;
            $node['attr']['code']=$e->getMessage();
        }
        $root['node']=array($node);
        header('Content-Type:text/xml;charset=utf-8'); 
        echo $this->_formatData($root);
    }
    /**
     * 检索查找可添加物件
     */
    private function _search($params)
    {
        try {
            $root['attr']=array('reqTime'=>time(),'uid'=>'','tagName'=>'root');
            $node['attr']=array('event'=>'3004','result'=>1,'code'=>1, 'tagName'=>'node');
            if(!isset($params['uid']) || intval($params['uid'])<0)
            {
                throw new CException('未登录...');
            }
            $root['attr']['uid']=$params['uid'];
            //@todo:检索条件
            $con='';
            if(intval($params['brandid'])>0 || intval($params['seriesid'])>0){
                $con .=' and (brand_id='.intval($params['brandid']).' or brand_id='.intval($params['seriesid']).')';
            }
            if(intval($params['typeid'])>0){
                $con .=' and category_id in (select id from tbl_category where parent_id in ( select id from tbl_category where parent_id='.intval($params['typeid']).'))';
            }
            if(intval($params['styleid'])>0){
                $con .=' and id in (select mold_id from tbl_mold_style_relation where style_id='.intval($params['styleid']).')';
            }
            if($params['colorid']!='0'){
                $con .=" and id in (select mold_id from sp_goods_attr where attr_value like '%".trim($params['colorid'])."%' )";
            }
            if(intval($params['renderid'])>0){
                $con .=' and id in (select mold_id from tbl_render where space_id='.$params['spaceid'].')';//已渲染模型
            }else{
                $con .=' and id not in (select mold_id from tbl_render where space_id='.$params['spaceid'].')';//未渲染模型
            }
            if(trim($params['name'])!='搜索' && !empty($params['name'])){
                $con .=" and (name like '%".trim($params['name'])."%' or item like '%".trim($params['name'])."%')";
            }
            $molds=  Mold::model()->findAll('is_del=0'.$con);//检索符合条件的模型数据
            foreach($molds as $mold)
            {
                $goodsTag=array();
                $goodsTag['attr']=array('id'=>$mold['id'], 'brandID'=>$mold['brand_id'], 
                    'styleID'=>'', 'render'=>0, 'file'=>'\\\\192.168.16.252', 
                    'frame'=>'', 'colors'=>'', 'overLookPic'=>$mold['floorplan'], 'frontPic'=>$mold['image'], 'tagName'=>'goods');
                $node['node'][]=$goodsTag;
            }
        } catch (Exception $e) {
            $node['attr']['result']=0;
            $node['attr']['code']=$e->getMessage();
        }
        $root['node']=array($node);
        header('Content-Type:text/xml;charset=utf-8'); 
        echo $this->_formatData($root);
    }
    
    /**
     * 
     * 异常处理
     */
    private function _getError($params)
    {
        !isset($params['uid']) && $params['uid']='';
        $root['attr']=array('reqTime'=>time(),'uid'=>$params['uid'],'tagName'=>'root');
        $node['attr']=array('event'=>'9001','result'=>1,'code'=>1,'uid'=>$params['uid'], 'tagName'=>'node');
        $root['node']=array($node);
        header('Content-Type:text/xml;charset=utf-8'); 
        echo $this->_formatData($root);
    }
    
    /**
     * 
     * @param array $data array(array('attr'=>array(), 'node'=>array()))
     * @return string
     */
    private function _formatData($data)
    {
//        //json格式
//        return CJSON::encode($data);
        
        //xml格式
        if(!isset($data['attr']))
        {//抛出异常(直接返回错误文件)
            
        }
        if(!isset($data['node']))
        {//抛出异常
            
        }
        $xml = new DOMDocument('1.0', 'utf-8');
        $xml->formatOutput = true;
        //根节点
        $root=$xml->createElement($data['attr']['tagName']);
        $xml->appendChild($root);
        //递归为root添加子节点
        $this->_appendChild($xml, $root, $data['node']);
        //直接返回字符串   
        return $xml->saveXML();
    }
    /**
     * 
     * 递归生成子节点
     * @param DOMDocument $xml 
     * @param DOMElement $parent 父节点
     * @param array $nodes 子节点
     */
    private function _appendChild($xml, $parent, $nodes)
    {
        foreach ($nodes as $node)
        {
            //根据attr生成子节点
            if(!isset($node['attr']))
                continue;
            $tag=$xml->createElement($node['attr']['tagName']);
            foreach($node['attr'] as $key=>$val )
            {
                if('tagName'==$key)
                    continue;
                $attr=$xml->createAttribute($key);
                $attr->appendChild($xml->createTextNode($val));
                $tag->appendChild($attr);
            }
            //生成子节点
            if(isset($node['attr']['cdata']) && !empty($node['attr']['cdata']))
            {
                $tag->appendChild($xml->createCDATASection($node['attr']['cdata']));
            }
            if(isset($node['node']) && !empty($node['node']))
            {
                $this->_appendChild($xml, $tag, $node['node']);
            }
            else
            {
                //没有子标签就发生嵌套错误
                $tag->appendChild($xml->createTextNode(''));
            }
            $parent->appendChild($tag);
        }
    }
}




