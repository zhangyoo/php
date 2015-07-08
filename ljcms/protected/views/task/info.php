<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/info.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/order.js"></script>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/common/kindeditor/themes/default/default.css" />
<script src="<?php echo Yii::app()->request->baseUrl; ?>/common/kindeditor/kindeditor.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/common/kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/res.js"></script>
<div class="sectionTitle-A mb10">
    <h2>素材列表</h2>
</div>
<?php $form = $this->beginWidget('CActiveForm', array(
            'id'=>'infoForm',
            'htmlOptions'=>array('enctype'=>'multipart/form-data'),
  )); 
?>
<div class="clear mb10">
    <div class="sectionBun-A2 L">
        <a class="btn btn-primary" href="javascript:history.go(-1);">返回订单列表</a>
    </div>
    <div class="sectionSearch-A1 sectionForm-A1 sectionForm-A1-2 R sectionFloat-A1">
        <ul class="clear">
            <li>
                <label>素材标题/型号/编号</label>
                <input type="text" value="<?php echo !empty($name) ? $name :''; ?>" name="name" class="text">
            </li>
            <li class="button">                                    
                <input class="btn btn-large btn-primary" type="submit" value="查询">
            </li>
        </ul>
    </div>
</div>
<?php if(!empty($model)): ?>
<div class="sectionTable-A1 mb10">
    <table class="table table table-hover">
        <thead>
            <tr>
                <th class="col-1" width="10%">订单编号</th>
                <th class="col-1" width="10%">订单类型</th>
                <th class="col-1" width="10%">订单标题</th>
                <th class="col-2" width="23%">订单内容</th>
                <th class="col-3" width="17%">创建时间</th>
                <th class="col-4" width="14%">更新时间</th>
                <th class="col-4" width="6%">订单状态</th>
                <th class="col-5">操作</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="col-2"><?php echo $model['number']; ?></td>
                <td class="col-1">
                    <?php echo Yii::app()->params['orderType'][$model['type']]; ?>
                </td>
                <td class="col-2"><?php echo $model['title']; ?></td>
                <td class="col-3"><?php echo mb_substr(strip_tags($model['content']),0,30,'utf8'); ?></td>
                <td class="col-4">
                    起始：<?php echo date("Y-m-d H:i:s", $model["create_time"]);?><br>
                    预结束：<?php echo date("Y-m-d H:i:s", $model["end_time"]);?>
                </td>
                <td class="col-5"><?php echo !empty($model["update_time"])?date("Y-m-d H:i:s",$model["update_time"]):'';?></td>
                <td class="col-6">
                    <?php if(isset($isTA['task']) && !empty($isTA['task'])){ ?>
                        <?php if($isTA['task']['status']==1){ ?>
                            <?php if($isTA['task']['is_check']==0){ ?>
                                <font color="#FF0000">待审核</font>
                            <?php }elseif($isTA['task']['is_check']==1){ ?>
                                <font color="#FF0000">已审核</font>
                            <?php }else{ ?>
                                <font color="#FF0000">审核不通过</font>
                            <?php } ?>
                        <?php }else{ ?>
                            <font color="#FF0000">未完成</font>    
                        <?php } ?>         
                    <?php }else{ ?>
                            <?php echo Yii::app()->params['orderStatus'][$model['status']]; ?>
                    <?php } ?>
                </td>
                <td class="col-7">
                    [<a href="/order/create/id/<?php echo $model['id']; ?>/task">查看订单</a>]<br>
                    <?php if(!isset($_GET['allocation'])): ?>
                    [<a href="javascript:void(0);" onclick="editStatusDia(this)" obj_id="<?php echo $model['id'];?>" type="order"  
                        sid="<?php echo isset($_GET['sid']) ? $_GET['sid'] :""; ?>">编辑状态</a>]<br>
                    <span>
                        <?php
                        if(!isset($isTA['notask']) || empty($isTA['notask'])){
                            if(isset($isTA['task']) && empty($isTA['task'])){
//                                echo CHtml::dropDownList('taskUser','',$taskUser,array(
//                                    'class'=>'taskUser','empty'=>'指定给谁','style'=>'width:auto','allocation_type'=>$model['type']==1 ? "1":"0",
//                                    'obj_id'=>$model['id'],'sid'=>isset($_GET['sid']) && !empty($_GET['sid']) ? $_GET['sid'] : "",
//                                    'task_type'=>'','task_type'=>'0','onchange'=>'bindTask(this)',
//                                    )
//                                );
                            }elseif(isset($isTA['task']) && !empty($isTA['task'])){
                                echo "接单人员：".$isTA['task']['username']."<a href='javascript:void(0);' class='reBindTask' onclick='reBindTask(this)' taskId='".
                                        $isTA['task']['taskId']."' rid='".$isTA['task']['rid']."' type='".Yii::app()->params['typeAllow'][$model['type']]."'>&nbsp;</a>";
                            }
                        }
                        
                        ?>
                    </span>
                    <?php endif; ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<?php endif; ?>
<div class="sectionTable-A1 mb10">
    <table class="table table table-hover">
        <thead>
            <tr>
                <th class="col-1" width="9%">编号</th>
                <th class="col-2" width="8%">品牌型号</th>
                <th class="col-3" width="15%">素材名称</th>
                <th class="col-5" width="14%">素材标签</th>
                <th class="col-6" width="15%">模型情况</th>
                <th class="col-7" width="8%">图片情况</th>
                <th class="col-7" width="8%">是否绑定商品</th>
                <th class="col-7" width="8%">是否绑定模型</th>
                <th class="col-7" width="5%">状态</th>
                <th class="col-8">操作</th>
            </tr>
        </thead>
        <tbody>
            <?php if(isset($ul) && !empty($ul)): ?>
            <?php foreach ($ul as $info):?>
            <tr id="tr_<?php echo $info['id']; ?>">
                <td class="col-1">
                    <?php echo $info['number']; ?>
                </td>
                <td class="col-2">
                    <?php echo $info['brandName']; ?><br>
                    <a href="javascript:void(0);" title="<?php echo $info['item']; ?>"><?php echo mb_substr(strip_tags($info['item']),0,10,'utf8'); ?></a>
                </td>
                <td class="col-3">
                    <div class="L" style="width:45%;">
                        <?php echo CHtml::image(Yii::app()->params['static'].$info['image'],$info['title'],array('width'=>'80')); ?>
                    </div>
                    <div class="L ml5" style="width:50%;">
                        <p><font title="<?php echo $info['title']; ?>"><?php echo mb_substr(strip_tags($info['title']),0,10,'utf8'); ?></font></p>
                        <p><?php echo Yii::app()->params['productType'][$info['type']]; ?></p>
                    </div>
                </td>
                <td class="col-5">
                    <p>风格：<?php echo $info['style']; ?></p>
                    <p>
                        颜色：
                        <?php if(!empty($info['color'])): ?>
                        <?php foreach ($info['color'] as $kco=>$co): ?>
                        <?php echo CHtml::image(Yii::app()->request->BaseUrl.$co,$kco,array('width'=>'15','title'=>$kco))." ".$colorsSN[$kco.'|'.$co]; ?>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </p>
                    <p>尺寸: 长<?php echo $info['length'];?>×宽<?php echo $info['width'];?>×高<?php echo $info['height'];?></p>
                    <p>材质：<?php echo $info['material']; ?></p>
                    <p>标签：<?php echo $info['label']; ?></p>
                </td>
                <td class="col-6 one_select">
                    <?php if($model['type'] == 3): ?>
                    无
                    <?php else: ?>
                    <?php $mCon = json_decode($info['mold_condition'],true); ?>
                    <?php foreach (Yii::app()->params['moldType'] as $kic=>$ic): ?>
                    <span class="mr10 moldSpanStyle">
                        <a class="<?php echo !empty($mCon) && isset($mCon[$kic]) ? "upMoldColor":""; ?>" <?php echo !empty($mCon)&&isset($mCon[$kic])&&intval($mCon[$kic])>0 ? "href='/mold/update/id/".$mCon[$kic]."' target='_blank'" : "href='javascript:void(0);'"; ?>><?php echo $ic; ?></a>
                    </span>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </td>
                <td class="col-7 one_select">
                    <?php $imgCon = json_decode($info['img_condition'],true); ?>
                    <?php foreach (Yii::app()->params['imgCondition'] as $kimg=>$vimg): ?>
                        <?php if($model['type'] == 3): ?>
                        <?php if($kimg != 5): ?>
                        <span>
                            <?php echo $vimg; ?>
                            ( <?php echo !empty($imgCon)&&isset($imgCon[$kimg])&&intval($imgCon[$kimg])>0 ? intval($imgCon[$kimg]) : "0"; ?> )
                        </span><br>
                        <?php endif; ?>
                        <?php else: ?>
                        <span>
                            <?php echo $vimg; ?>
                            ( <?php echo !empty($imgCon)&&isset($imgCon[$kimg])&&intval($imgCon[$kimg])>0 ? intval($imgCon[$kimg]) : "0"; ?> )
                        </span><br>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </td>
                <td class="col-7 one_select"><?php echo !empty($info['product_id']) ? "已绑定" :"未绑定"; ?></td>
                <td class="col-7 one_select"><?php if($model['type'] != 3){echo !empty($info['mold']) ? "已绑定" :"未绑定";}else{ echo '无';} ?></td>
                <td class="col-7 one_select"><?php echo Yii::app()->params['infoStatus'][$info['status']]; ?></td>
                <td class="col-9">
                    [<a href="/info/update/id/<?php echo $info['id']; ?>/oid/<?php 
                    echo isset($_GET['oid']) ? $_GET['oid']:''; ?>/task">查看素材</a>]<br>
                    <?php if($model['type'] !=3): ?>
                    [<a href="javascript:void(0)" rel="<?php echo $info['id']; ?>" onclick="disPics(this)">查看360度图片</a>]<br>
                    <?php endif; ?>
                    <?php if(in_array($model['type'], Yii::app()->params['allowCinfo'])): ?>
                    <?php if(!isset($_GET['allocation'])): ?>
                    [<a href="javascript:void(0);" onclick="editStatusDia(this)" obj_id="<?php echo $info['id'];?>" type="info"  
                         sid="">编辑状态</a>]<br>
                    <?php endif; ?>
                    <?php if(!empty(Yii::app()->session['upMold']) && $model['type'] !=3): ?>
                    [<a href="javascript:void(0)" rel="<?php echo $info['id']; ?>" type="mold" onclick="showBindPM(this)">绑定模型</a>]<br>     
                    <?php endif; ?>
                    <?php if($model['type'] ==3): ?>
                    [<a href="/info/texture/id/<?php echo $info['id']; ?>">查看贴图</a>]
                    <?php endif; ?>
                    <?php if(!isset($_GET['allocation']) && in_array($model['type'], array(0,3)) && empty($isTA['task'])): ?>
                    <span>
                        <?php
                        if($model['type'] == 0){
                            if(!empty($info['task']) && isset($info['task'][1])){
                                echo "模型：".$info['task'][1]['username']."<a href='javascript:void(0);' class='reBindTask' onclick='reBindTask(this)' taskId='".
                                            $info['task'][1]['id']."' rid='".$info['task'][1]['receiver']."' type='".Yii::app()->params['typeAllow'][$model['type']]."'>&nbsp;</a><br>";
                            }else{
                                if($info['status']!=2){
                                    echo CHtml::dropDownList('taskTypeI','',$taskUser,array(
                                        'class'=>'taskUser','empty'=>'模型指定给谁','style'=>'width:auto','allocation_type'=>'1',
                                        'obj_id'=>$info['id'],'sid'=>'','task_type'=>'1','onchange'=>'bindTask(this)',
                                        )
                                    ); 
                                }
                            }
                        }
                        ?>
                    </span>
                    <span>
                        <?php
                        if(!empty($info['task']) && isset($info['task'][2])){
                            echo "贴图：".$info['task'][2]['username']."<a href='javascript:void(0);' class='reBindTask' onclick='reBindTask(this)' taskId='".
                                        $info['task'][2]['id']."' rid='".$info['task'][2]['receiver']."' type='".Yii::app()->params['typeAllow'][$model['type']]."'>&nbsp;</a><br>";
                        }else{
                            if($info['status']!=2){
                                echo CHtml::dropDownList('taskTypeII','',$taskUser,array(
                                    'class'=>'taskUser','empty'=>'贴图指定给谁','style'=>'width:auto','allocation_type'=>'1',
                                    'obj_id'=>$info['id'],'sid'=>'','task_type'=>'2','onchange'=>'bindTask(this)',
                                    )
                                );
                            }
                        }
                        ?>
                    </span>
                    <span>
                        <?php
                        if(!empty($info['task']) && isset($info['task'][3])){
                            echo "QC：".$info['task'][3]['username']."<a href='javascript:void(0);' class='reBindTask' onclick='reBindTask(this)' taskId='".
                                        $info['task'][3]['id']."' rid='".$info['task'][3]['receiver']."' type='".Yii::app()->params['typeAllow'][$model['type']]."'>&nbsp;</a><br>";
                        }else{
                            if($info['status']!=2){
                                echo CHtml::dropDownList('taskTypeIII','',$taskUser,array(
                                    'class'=>'taskUser','empty'=>'QC指定给谁','style'=>'width:auto','allocation_type'=>'1',
                                    'obj_id'=>$info['id'],'sid'=>'','task_type'=>'3','onchange'=>'bindTask(this)',
                                    )
                                );
                            }
                        }
                        ?>
                    </span>
                    <?php endif; ?>
                    <?php if(isset($_GET['allocation']) && in_array($model['type'], Yii::app()->params['allowCinfo']) ): ?>
                    <span class="one_select">
                        <?php
                        if($model['type'] == 0){
                            echo CHtml::checkBoxList('taskType',$info['TT'],array('1'=>'模型','2'=>'贴图','3'=>'QC'),
                                    array('separator'=>'&nbsp;','disabled'=>true)
                            );
                        }else{
                            echo CHtml::checkBoxList('taskType',$info['TT'],array('2'=>'贴图','3'=>'QC'),
                                    array('separator'=>'&nbsp;','disabled'=>true)
                            );
                        }
                        ?>
                    </span>
                    <?php endif; ?>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
            <tr>
                <td colspan="10" align="right"> 
                    <div class="sectionFoot-B1">
                        <div class="sectionFloat-A1 addpage_style">
                            <div class="page_list">
                                <?php $this->widget('CLinkPager', array(
                                    'header'=> '<span>共'. $count. '个</span>',
                                    "maxButtonCount"=>5,
                                    'pages' => $pages,
                                    'firstPageLabel'=>'&lt;&lt; 首页',
                                    'prevPageLabel'=>'&lt; 上一页',
                                    'nextPageLabel'=>'下一页 &gt;',
                                    'lastPageLabel'=>'末页 &gt;&gt;',
                                ))?>  
                            </div>
                            <div class="goToPages">
                                跳转到：
                                <input type="text" name="pageNum" class="pageInput" /> 页&nbsp;&nbsp;
                                <input type="button" id="loadPageNum" url="<?php echo Yii::app()->request->BaseUrl."/task/info/oid/".$_GET['oid'];?><?php echo isset($_GET['sid'])?"/sid/".$_GET['sid']:""; ?><?php echo isset($_GET['allocation'])?"/allocation/1":""; ?>/page/" onclick="transPages(this)" class="clickPageIcon" value="确定"/>&nbsp;&nbsp;
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<?php $this->endWidget(); ?>
<div class="pop_dialog_top" style="display: none;">
    <div class="dialog_two_top">
        <b id="chooseType" chooseValue="product">绑定商品(只能绑定一个)</b>
        <a href="javascript:void(0);" onclick="closeDia()"><img class="icon" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/cut_icon.png" alt="关闭弹框"/></a>
    </div>
    <div class="dialog_content_top">
        <div class="info_psearch">
            <select class="inputLen" id="selectBindType" style="margin:0">
                <option value="unbind">已绑定</option>
                <option value="bind">未绑定</option>
            </select>
            &nbsp;
            <input type="text" class="search_product_name" placeholder="商品名称(或商品货号)" name="searchName" />
            <input type="button" class="info_psearch_button"  onclick="ResSearchProduct(this)" value="搜索" />
        </div>
        <ul class="info_img_ul psBox">
            
        </ul>
        <div class="dialog_save savePfix">
            <input type="button" value="解除绑定" id="saveSecDia" rel="" bind="unbind" onclick="ResSProduct(this)"/>
        </div>
    </div>
</div>
<div class="editStatus" style="width:800px;display: none;">
    <div class="editStatus_top">
        <b>编辑状态</b>
        <a href="javascript:void(0);" onclick="closeEditDia()"><img class="icon" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/cut_icon.png" alt="关闭弹框"/></a>
    </div>
    <div class="editStatus_content">
        
    </div>
</div>
<div class="pop_dialog_top_two" style="display: none;">
    <div class="dialog_two_top">
        <b id="chooseType" chooseValue="product">360度图片展示</b>
        <a href="javascript:void(0);" onclick="closeDia_two()"><img class="icon" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/cut_icon.png" alt="关闭弹框" title="关闭弹框"/></a>
    </div>
    <div class="dialog_content_top">
        <ul class="top_two_ul">
            
        </ul>
    </div>
</div>
<script type="text/javascript">
    var i=0;
    $(".all_select").click(function(){
        if(i==0){
        i=1;
        $(".table tr td input[type=checkbox][name='Info[]']").attr("checked",true);
    }else if(i==1){
        i=0;
        $(".table tr td input[type=checkbox][name='Info[]']").attr("checked",false);
    }
    }); 
</script>