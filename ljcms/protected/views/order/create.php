<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/common/kindeditor/themes/default/default.css" />
<script src="<?php echo Yii::app()->request->baseUrl; ?>/common/kindeditor/kindeditor.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/common/kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/order.js"></script>
<link rel="stylesheet" type="text/css"  href="<?php echo Yii::app()->request->BaseUrl;?>/common/datePicker/css/WdatePicker.css" />    
<script type="text/javascript" src="<?php echo Yii::app()->request->BaseUrl;?>/common/datePicker/WdatePicker.js"></script>
<div class="sectionTitle-A mb10">
    <h2><font color="#3E5999">创建订单</font></h2>
</div>
<div class="clear mb10">
    <div class="sectionBun-A2 L mr10">
        <a class="btn btn-primary" href="/order/index">订单列表</a>
    </div>
</div>
<div class="sectionList-B1 mb20">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'=>'orderForm',
        'enableClientValidation'=>true,
        'htmlOptions'=>array(
            'enctype'=>'multipart/form-data',
        ),
//        'focus'=>array($model,'title'),
    )); ?>
    <ul>
        <li class="mb5">
            <label class="sectionLabel-A1">标题*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <?php echo $form->textField($model,'title',array('class'=>'L mr10 input-xxlarge sectionAlertText')); ?>
            </div>
        </li>
        <li class="mb5">
            <label class="sectionLabel-A1">订单内容：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <textarea id="Ordercontent" name="Order[content]" style="height: 300px; width: 750px;" ><?php echo isset($model['content']) ? $model['content']:'';?></textarea>
            </div>
        </li>
        <li class="mb5">
            <label class="sectionLabel-A1">完成日期*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <?php
                if(isset($model['end_time']) && !empty($model['end_time'])){
                    echo CHtml::textField('Order[end_time]',date('Y-m-d', $model['end_time']),
                            array('style'=>'width:120px;cursor:pointer','class'=>'Wdate','onclick'=>"WdatePicker({dateFmt:'yyyy-MM-dd'})"));
                }else{
                    echo $form->textField($model,'end_time',
                            array('style'=>'width:120px;cursor:pointer','class'=>'Wdate','onclick'=>"WdatePicker({dateFmt:'yyyy-MM-dd'})"));
                }
                ?>
            </div>
        </li>
        <li class="mb5">
            <label class="sectionLabel-A1">订单类型*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <div class="one_select" id="moldTypeSel">
                <?php
                if(isset($model['id']) && !empty($model['id'])){
                    echo $form->radioButtonList($model,'type',Yii::app()->params['orderType'],
                            array('separator'=>'&nbsp;','onchange'=>'changeOType(this)','disabled '=>true)); 
                }else{
                    echo $form->radioButtonList($model,'type',Yii::app()->params['orderType'],
                            array('separator'=>'&nbsp;','onchange'=>'changeOType(this)'));
                }
                ?>
                </div>
            </div>
        </li>
    </ul>
    <ul id="changeOrderType">
        <?php if(isset($model['id']) && !empty($model['id'])){ ?>
            <?php $this->renderPartial('changeType',array('brandhalls'=>$brandhalls,'type'=>$model['type'],'default'=>$default)); ?>
        <?php }else{ ?>
        <li class="mb5">
            <label class="sectionLabel-A1">客户名称*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4 one_select customer_select">
                <?php if(!empty($brandhalls)): ?>
                <?php foreach ($brandhalls as $kbh=>$brandhall): ?>
                <span>
                    <input type="checkbox" name="brandhall[]" value="<?php echo $kbh; ?>" />
                    <label><?php echo $brandhall; ?></label>
                </span>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </li>
        <?php } ?>
    </ul>
    <?php if(!empty(Yii::app()->session['update']) && !isset($_GET['task'])): ?>
    <div class="sectionBox-A1 sectionBox-A1-1 sectionForm-A1 sectionForm-A1-2">
        <?php if(isset($model['type']) && in_array($model['type'], array(1,2))): ?>
        <?php echo CHtml::submitButton('保存订单，下一步选择素材信息',array('class'=>'btn btn-primary','id'=>'submitOrder')); ?>&nbsp;&nbsp;&nbsp;
        <?php else: ?>
        <?php echo CHtml::submitButton('保存订单，下一步创建素材信息',array('class'=>'btn btn-primary','id'=>'submitOrder')); ?>&nbsp;&nbsp;&nbsp;
        <?php endif; ?>
        <?php echo CHtml::Button('取消',array('class'=>'btn','onclick'=>'cancelOrder()')); ?>
    </div>
    <?php endif; ?>
    <?php $this->endWidget(); ?>
</div>
<div class="pop_dialog_top" style="display: none;">
    <div class="dialog_two_top">
        <b id="chooseType" chooseValue="space">绑定空间(可绑定多个,只能选择同一种功能空间)</b>
        <a href="javascript:void(0);" onclick="closeDia()"><img class="icon" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/cut_icon.png" alt="关闭弹框"/></a>
    </div>
    <div class="dialog_content_top">
        <div class="info_psearch">
            <span>功能空间：</span>&nbsp;
            <?php
                echo CHtml::dropDownList('Order[room_category]',$model['room_category'],Yii::app()->params['roomCategories'],array(
                    'id'=>'search_room_category','empty'=>'请选择','style'=>'width:auto'
                    )
                );
            ?>&nbsp;&nbsp;
            <span>空间尺寸(长*宽)：</span>&nbsp;
            <input type="text" class="search_space_lw" name="searchName" />
            <input type="button" class="info_psearch_button"  onclick="searchSpace(this)" rel="<?php echo $model['room_category']; ?>" value="搜索" />
        </div>
        <ul class="info_img_ul psBox">
            
        </ul>
        <div class="dialog_save savePfix">
            <input type="button" value="保存" id="saveSecDia" onclick="saveSpace(this)"/>
        </div>
    </div>
</div>
<script type="text/javascript">
$(function(){
    //初始化KindEditor
    KindEditor.ready(function(K) {
        //加载KindEditor编辑器
        K.create('textarea[id="Ordercontent"]', {
            filterMode : true
        });
    }); 
})   
</script>

