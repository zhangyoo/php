<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/order.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->BaseUrl;?>/common/datePicker/WdatePicker.js"></script>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/common/kindeditor/themes/default/default.css" />
<script src="<?php echo Yii::app()->request->baseUrl; ?>/common/kindeditor/kindeditor.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/common/kindeditor/lang/zh_CN.js"></script>
<div class="sectionTitle-A mb10">
    <h2>订单列表</h2>
</div>
<div class="clear mb10">
    <div class="sectionBun-A2 L">
        <a class="btn btn-primary" href="javascript:history.go(-1);">返回</a>
    </div>
    <div class="sectionSearch-A1 sectionForm-A1 sectionForm-A1-2 R sectionFloat-A1">
        <?php $ms = 'mold'; if(isset($_GET['space'])){$ms = 'space';} ?>
        <?php $form = $this->beginWidget('CActiveForm', array(
                    'id'=>'user-form',
                    'method'=>'get',
                    'action'=>'/task/order/'.$ms,
                    'htmlOptions'=>array('enctype'=>'multipart/form-data'),
          )); ?>
            <ul class="clear">
                <li>
                    <label> </label>
                    <?php
                        echo CHtml::dropDownList('brandhall',$seachData['brandhall'],$brandhalls,
                                array('separator'=>'&nbsp;','empty'=>'品牌馆','style'=>'width:auto'));
                    ?>
                </li>
                <?php if(isset($_GET['space'])): ?>
                <li>
                    <label> </label>
                    <?php
                        echo CHtml::dropDownList('type',$seachData['type'],array('1'=>'渲染订单','2'=>'新空间渲染订单'),
                                array('separator'=>'&nbsp;','empty'=>'订单类型','style'=>'width:auto'));
                    ?>
                </li>
                <?php endif; ?>
                <li>
                    <label> </label>
                    <?php
                        echo CHtml::dropDownList('status',$seachData['status'],Yii::app()->params['orderStatus'],
                                array('separator'=>'&nbsp;','empty'=>'订单状态','style'=>'width:auto'));
                    ?>
                </li>
                <li>
                    <label> </label>
                    <input type="text" value="<?php echo !empty($seachData['name']) ? $seachData['name'] :''; ?>" name="name" class="text" placeholder="<?php echo isset($_GET['mold']) ? '订单名称/编号':'订单名称/编号/空间名称'; ?>"/>
                </li>
                <li>
                    <input class="input" type="text" placeholder=" 起始时间" name="timeStart" value="<?php echo !empty($seachData['timeStart'])?$seachData['timeStart']:''; ?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd'})" size="20" style="margin-right: 20px;width: 100px" />
                </li>
                <li>
                    <input class="input" type="text" placeholder=" 截止时间" name="timeEnd" value="<?php echo !empty($seachData['timeEnd'])?$seachData['timeEnd']:''; ?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd'})" size="20" style="margin-right: 20px;width: 100px" />
                </li>
                <li class="button">                                    
                    <input class="btn btn-large btn-primary" type="submit" value="查询">
                </li>
            </ul>				      		                   
        <?php $this->endWidget(); ?>
    </div>
</div>

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
            <?php if(isset($ul) && !empty($ul)): ?>
            <?php foreach ($ul as $model):?>
            <tr id="tr_<?php echo $model['id']; ?>">
                <td class="col-2"><?php echo $model['number']; ?></td>
                <td class="col-1">
                    <?php echo Yii::app()->params['orderType'][$model['type']]; ?><br>
                    <?php echo isset($_GET['space']) && !empty($model['space_name']) ? "空间：".$model['space_name'] :""; ?>
                </td>
                <td class="col-2"><?php echo $model['title']; ?></td>
                <td class="col-3"><?php echo mb_substr(strip_tags($model['content']),0,30,'utf8'); ?></td>
                <td class="col-4">
                    起始：<?php echo date("Y-m-d H:i:s", $model["create_time"]);?><br>
                    预结束：<?php echo date("Y-m-d H:i:s", $model["end_time"]);?>
                </td>
                <td class="col-5"><?php echo !empty($model["update_time"])?date("Y-m-d H:i:s",$model["update_time"]):'';?></td>
                <td class="col-6">
                    <?php if(isset($model['task']) && !empty($model['task'])){ ?>
                        <?php if($model['task']['status']==1){ ?>
                            <?php if($model['task']['is_check']==0){ ?>
                                <font color="#FF0000">待审核</font>
                            <?php }elseif($model['task']['is_check']==1){ ?>
                                <font color="#FF0000">已审核</font>
                            <?php }else{ ?>
                                <font color="#FF0000">审核不通过</font>
                            <?php } ?>
                        <?php }else{ ?>
                            <font color="#FF0000">未完成</font>    
                        <?php } ?>         
                    <?php }else{ ?>
                            <?php echo $model['type']==1 ? Yii::app()->params['orderStatus'][$model['space_status']] : Yii::app()->params['orderStatus'][$model['status']]; ?>
                    <?php } ?>
                </td>
                <td class="col-7">
                    [<a href="/order/create/id/<?php echo $model['id']; ?>/task">查看订单</a>]<br>
                    [<a href="javascript:void(0);" onclick="editStatusDia(this)" obj_id="<?php echo $model['id'];?>" type="order" 
                         sid="<?php echo isset($_GET['space']) && !empty($model['space_id']) ? $model['space_id'] :""; ?>">编辑状态</a>]<br>
                    [<a href="/task/info/oid/<?php echo $model['id']; ?><?php echo isset($_GET['space']) && !empty($model['space_id']) ? "/sid/".$model['space_id'] :""; ?>">查看详情</a>]<br>
                    <span>
                        <?php
                        $OType = array('0'=>'mold','1'=>'space','2'=>'space','3'=>'mold');
                        if(!isset($model['notask']) || empty($model['notask'])){
                            if(isset($model['task']) && empty($model['task'])){
                                if($model['type']==1 && $model['space_status']!=3){
                                    echo CHtml::dropDownList('taskUser','',$taskUser,array(
                                        'class'=>'taskUser','empty'=>'指定给谁','style'=>'width:auto','allocation_type'=>isset($_GET['space']) && !empty($model['space_id']) ? "1" :"0",
                                        'obj_id'=>$model['id'],'sid'=>isset($_GET['space']) && !empty($model['space_id']) ? $model['space_id'] :"",
                                        'task_type'=>'','task_type'=>'0','onchange'=>'bindTask(this)',
                                        )
                                    );
                                }elseif($model['type']!=1 && $model['status']!=3){
                                    echo CHtml::dropDownList('taskUser','',$taskUser,array(
                                        'class'=>'taskUser','empty'=>'指定给谁','style'=>'width:auto','allocation_type'=>isset($_GET['space']) && !empty($model['space_id']) ? "1" :"0",
                                        'obj_id'=>$model['id'],'sid'=>isset($_GET['space']) && !empty($model['space_id']) ? $model['space_id'] :"",
                                        'task_type'=>'','task_type'=>'0','onchange'=>'bindTask(this)',
                                        )
                                    );
                                }
                                
                            }elseif(isset($model['task']) && !empty($model['task'])){
                                echo "接单人员：".$model['task']['username']."<a href='javascript:void(0);' class='reBindTask' onclick='reBindTask(this)' taskId='".
                                        $model['task']['taskId']."' rid='".$model['task']['rid']."' type='".$OType[$model['type']]."'>&nbsp;</a>";
                            }
                        }
                        ?>
                    </span>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<div class="sectionFoot-B1">
    <div class="sectionFloat-A1 addpage_style">
        <div class="page_list">
        <?php $this->widget('CLinkPager', array(
            'header'=> ' ',
            "maxButtonCount"=>5,
            'pages' => $pages,
            'firstPageLabel'=>'&lt;&lt; 首页',
            'prevPageLabel'=>'&lt; 前页',
            'nextPageLabel'=>'后页 &gt;',
            'lastPageLabel'=>'末页 &gt;&gt;',
        ))?>
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
