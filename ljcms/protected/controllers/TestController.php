<?php
/**
 * 测试使用
 */
class TestController extends Controller
{
    public function actionIndex()
    {
//        header('content-type:text/html;charset=utf-8');
//        $p1 = new Person("百度",25);
//        $p1=null;
//        $p2 = new Person("新浪",23);
////        echo $p1->name;
//        echo "<br />哈哈哈哈<br />";
//        
//        var_dump(json_encode(array(1,2,3)));
//        var_dump(json_encode(array("/upload/dsds.png","/upload/dsds.png","/upload/dsds.png")));
//        var_dump(json_encode(array('0'=>'2','1'=>'4','2'=>'6')));
//        $info = Info::model()->with('materials','styles')->findByAttributes(array('id'=>1));
//        $info->is_rotation = 1;
//        $info->save();
//        var_dump($info);exit;
//        $moldTypeAll = Info::model()->with('molds')->find("number='20141118N1'");
//        var_dump($moldTypeAll['molds']);
//        if(!empty($moldTypeAll['molds'])){
//            $moldNameType = array_flip(Yii::app()->params['moldNameType']);
//            var_dump($moldNameType);
//            foreach ($moldTypeAll['molds'] as $mta){
//                $texExt = Texture::model()->find("");
//            }
//        }
//        $uv_map = json_decode('fsdfsdfsfsd',true);
//        var_dump(explode('-', 'dsds-dsd'));
//        $albumData = Album::model()->count('type=2 and obj_id=1');
//        var_dump(intval($albumData));
//        $filename = '20141118N1-DMAX_FH_1024-1024-0_N_TS_TL.JPG';
//        $name=substr($filename, strrpos($filename, '_') + 1, strrpos($filename, '.')-strrpos($filename, '_')-1);
//        var_dump($name);
//        $info = Info::model()->with('materials','styles','molds')->findByAttributes(array('number'=>'20141118N1','is_del'=>0));
//        var_dump($info);exit;
//        $temp = 'B_01&JJ_Y_KT0002_A_01_TH_20141117N1-GMAX_HS_MZ_G_YH';
//        $ds = explode('_',substr($temp, 0,strrpos($temp, '&')));
//        var_dump($ds);
//        $filename = 'JJ_Y_KT0002_A_01_TH_20141117N1-GMAX_HS_MZ_G_YH_D.PNG';
//        $pt=strrpos($filename, ".");
//        $fileForm = substr($filename, $pt+1, strlen($filename) - $pt);//获取文件后缀
//        $fileForm = strtolower($fileForm);//把后缀名转换成小写
//        $FName = substr($filename, 0, $pt);//获取文件的名称(不含扩展名)
//        $newFileName = $FName.'.'.$fileForm;
//        var_dump($newFileName);
//        $moldData = Mold::model()->with('materials','styles')->findByAttributes(array('name'=>'20141118N1-GMAX','is_del'=>0));
//        var_dump($moldData['materials']);exit;
//        $mn = array_merge(array('1','2','3'),array('2','3','4'));
//        var_dump(array_unique(array_merge(array('1','2','3'),array('2','3','4'))));exit;
//        $id = '1';
//        $info = Info::model()->with('molds')->findByPk($id,'t.is_del=0');
//        $order = Order::model()->with('infos')->find('t.is_del=0 and t.type in ('. implode(',', Yii::app()->params['allowCinfo']) .') and infos.id='.$id);
//        $textures = array();
//        $moldCondition = array();
//        $imgCondition = array('1'=>'0','2'=>'0','3'=>'0','4'=>'0');
//        if($order['type']==0){//建模素材
//            if(!empty($info['molds'])){
//                foreach ($info['molds'] as $mold){
//                    $texture_id = json_decode($mold['texture_id'],true);
//                    if(!empty($texture_id)){
//                        $tids = array_keys($texture_id);
//                        $textures = array_unique(array_merge($textures,$tids));
//                    }
//                    //处理模型类型
//                    $moldCondition[$mold['mold_type']] = $mold['id'];
//                }
//            }
//        }else{
//            $texture_id = json_decode($info['texture_id'],true);
//            if(!empty($texture_id)){
//                $tids = array_keys($texture_id);
//                $textures = array_unique(array_merge($textures,$tids));
//            }
//        }
//        //处理贴图数量
//        if(!empty($textures)){
//            //透视图和顶视图
//            $tex_single = Texture::model()->findAll("(image is not null or floorplan is not null ) and id in (". implode(',', $textures) .") group by image,floorplan ");
//            if(!empty($tex_single)){
//                foreach ($tex_single as $texs){
//                    if(!empty($texs['image']))
//                        $imgCondition[1]++;
//                    if(!empty($texs['floorplan']))
//                        $imgCondition[2]++;
//                }
//            }
//            //UV贴图和法线图
//            $tex_other = Texture::model()->findAll("(uv_map is not null or mini_uv_map is not null or normal_map is not null or mini_normal_map is not null ) and id in (". implode(',', $textures) .") ");
//            if(!empty($tex_other)){
//                foreach ($tex_other as $texo){
//                    if(!empty($texo['uv_map']) || !empty($texo['mini_uv_map']))
//                        $imgCondition[3]++;
//                    if(!empty($texo['normal_map']) || !empty($texo['mini_normal_map']))
//                        $imgCondition[4]++;
//                }
//            }
//        }
//        if(!empty($moldCondition))
//            $info->mold_condition = json_encode ($moldCondition);
//        if(!empty($imgCondition)){
//            $oldIM = json_decode($info->img_condition,true);
//            if(isset($oldIM[5]))
//                $imgCondition[5] = $oldIM[5];
//            $info->img_condition = json_encode ($imgCondition);
//        }
//        $info->save();    
//        $info = Info::model()->with('molds','materials','styles')->findByPk(1,'molds.is_old=0');
//        var_dump($info['molds']);
//        var_dump(array_keys(array()));
//        var_dump($_SERVER);
//        $infos = Info::model()->with('molds')->findAll(array(
//                             'select'=>'*',
//                             'condition'=>"t.is_del=0 and molds.id=2100",
//                             'group'=>'t.id',
//                         ));
//        var_dump($infos);
//        $orderExist = Order::model()->with('infos')->find("infos.number='20141219N72' and t.type in (".implode(',', Yii::app()->params['allowCinfo']).")");
//        var_dump($orderExist);exit;
//        var_dump(strtolower(substr('DAWD', -3)));
//        $with=array(
//            'molds'=>array(),
//            'products'=>array(
//                'on'=>'products.parent_id=0'
//            )
//        );
//        $res = Info::model()->with($with)->findByPk('155');
//        $with=array(
//            'materials'=>array(),
//            'styles'=>array(),
//             'molds'=>array(),
//             'products'=>array(
//                 'on'=>'products.parent_id=0'
//             )
//         );
//        $info = Info::model()->with($with)->findByAttributes(array('number'=>'20141215N313','is_del'=>0));
//        var_dump($info);
//        $with=array(
//            'children'=>array(
//                'with'=>array(
//                    'infos'=>array(
//                        'on'=>'infos.is_del=0'
//                    )
//                ),
//                'on'=>'children.is_del=0'
//            )
//        );
//        $condition='t.parent_id=0 and t.is_del=0';
//        $infoTypeCondition='(t.type=3 or t.type=4)';
//        $condition.=' and '.$infoTypeCondition;
//        $categories= Label::model()->with($with)->findAll($condition);
//        var_dump($categories[0]['children']);
//        var_dump($_SERVER);
//        $with=array(
//            'products'=>array(
////                'on'=>'products.product_id=1787'
//            ),
//            'molds',
//            'label'
//        );
//        $condition='t.is_del=0 and t.id in (select info_id from tbl_info_product_relation where product_id=1787)';//is_del不能过滤
//        //素材
//        $infos= Info::model()->with($with)->findAll(array(
//            'select'=>'*',
//            'condition'=>$condition,
//            'group'=>'t.id',
//            'order'=>'t.create_time'
//        ));
//        var_dump($infos);
        var_dump('\/');
    }
    
    public function actionTemp()
    {
        $area=new Area();
        $area->x=1;
        $area->y=2;
        $area->length='0';
//                $area->length=$soft['l'];//后期需要加上
        $area->width=101;
        $area->height=344;
        $area->distance=15;
        $area->isNewRecord = TRUE;
        $area->save();
    }
    
    public function actionBindSP()
    {
        $element_id = 40;
        $this->ShowroomBC($element_id);
    }
    
    //转移模型贴图数据
    public function actionToMoldMap()
    {
        $connection=Yii::app()->db;
        $sql = 'select mm.* from tbl_mold_map as mm left join tbl_mold as m on m.id=mm.mold_id '
                . 'where m.is_del=0 ';
        $mm = $connection->createCommand($sql)->queryAll();
        if(!empty($mm)){
            foreach ($mm as $m){
                if(!empty($m['mold_id'])){
                    $mold = Mold::model()->findByPk($m['mold_id']);
                    $texture = array();
                    if(!empty($mold['texture_id']))
                        $texture = json_decode($mold['texture_id'],true);
                    $model = new Texture();
                    $model->type = $m['type'];
                    $model->floorplan = $m['floorplan'];
                    $model->image = $m['image'];
                    $model->uv_map = $m['uv_map'];
                    $model->normal_map = $m['normal_map'];
                    $model->specular_map = $m['specular_map'];
                    $model->alpha = $m['alpha'];
                    if($model->save()){
                        $mold->is_rotation = $m['is_rotation'];
                        if(!in_array($model->id, array_keys($texture))){
                            if(!empty($m['color_sort'])){
                                $texture[$model->id] = $m['color_sort'];
                            }else{
                                $texture[$model->id] = 0;
                            }
                        }
                        $mold->texture_id = json_encode($texture);
                        $mold->save();
                    }
                }
            }
        }
        echo 'success';
    }
    
}
class Person{
    public $name;
    public $age;

    public function __construct($name,$age){
        $this->name = $name;
        $this->age = $age;
    }
 
    function __destruct(){
        echo $this->name."销毁资源，关闭数据库等<br/>";
    }
    
}