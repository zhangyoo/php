<div class="sectionTitle-A mb10">
    <h2>编辑空间</h2>
</div>
<div class="clear mb10">
    <div class="sectionBun-A2 L mr10">
        <a class="btn btn-primary" href="/space/index">空间列表</a>
    </div>
</div>
<div class="sectionList-B1 mb20">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'=>'form-horizontal',
        'enableClientValidation'=>true,
        'htmlOptions'=>array(
            'enctype'=>'multipart/form-data',
        ),
//        'focus'=>array($model,'name'),
    )); ?>
    <ul>
        <li class="mb5">
            <label class="sectionLabel-A1">空间名称*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <?php echo $form->textField($model,'name',array('size'=>'25','class'=>'')); ?>
            </div>
        </li>
        <li class="mb5">
            <label class="sectionLabel-A1">对外名称*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <?php echo $form->textField($model,'out_name',array('size'=>'25','class'=>'')); ?>
            </div>
        </li>
        <li class="mb5">
            <label class="sectionLabel-A1">空间描述：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <?php echo $form->textArea($model,'summary',array('id'=>'summary')); ?>
            </div>
        </li>
        <li class="mb5">
            <label class="sectionLabel-A1">空间封面图*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <?php echo $form->FileField($model,'image',array('class'=>'btn btn-mini L mr10')); ?> 
                <?php echo CHtml::image(Yii::app()->params['static'].$model['image'],$model['name'],array('width'=>'200')); ?>
            </div>
        </li>
        <li class="mb5">
            <label class="sectionLabel-A1">空间视角图*：
                <a href="javascript:void(0);" onclick="addAngle()" style="color: #3B5999;font-weight: normal">添加视角</a>
            </label>
            <div id="addAngles" class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <?php if(!empty($upImg)): ?>
                <?php foreach ($upImg as $ku=>$ui): ?>
                <div class="angle_image">
                    <?php $defaultAI=Yii::app()->theme->baseUrl.'/images/defaultAngle.gif'; ?>
                    <i class="del_angle_icon"> </i>
                    <p class="angleVal_space">视角*：<?php echo CHtml::textField('angle[]',$ku,array('size'=>'25','class'=>'')); ?></p>
                    <div class="anglePics">
                        空间空模图*:<br>
                        <?php echo $form->FileField($model,'pics[]',array('class'=>'')); ?>
                        <p>
                            <?php 
                            $picsAngle=!empty($ui['pics'])?Yii::app()->params['static'].$ui['pics']:$defaultAI;
                            echo CHtml::image($picsAngle,'空间空模图',array('width'=>'200')); 
                            ?>
                        </p>
                    </div>
                    <div class="anglePics">
                        空间效果展示图*:<br>
                        <?php echo $form->FileField($model,'showpics[]',array('class'=>'')); ?>
                        <p>
                            <?php 
                            $showpicsAngle=!empty($ui['showpics'])?Yii::app()->params['static'].$ui['showpics']:$defaultAI;
                            echo CHtml::image($showpicsAngle,'空间效果展示图',array('width'=>'200')); 
                            ?>
                        </p>
                    </div>
                    <div class="anglePics">
                        空间平面布局图*:<br>
                        <?php echo $form->FileField($model,'floorplan[]',array('class'=>'')); ?>
                        <p><?php 
                            $floorplanAngle=  isset($ui['floorplan']) && !empty($ui['floorplan']) ? Yii::app()->params['static'].$ui['floorplan']:$defaultAI;
                            echo CHtml::image($floorplanAngle,'空间平面布局图',array('width'=>'200')); 
                        ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </li>
        <li class="mb5">
            <label class="sectionLabel-A1">正常尺寸(mm)*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                长: <?php echo $form->textField($model,'length',array('size'=>'25','class'=>'mr10')); ?>
                宽: <?php echo $form->textField($model,'width',array('size'=>'25','class'=>'mr10')); ?>
                高: <?php echo $form->textField($model,'height',array('size'=>'25','class'=>'mr10')); ?>
            </div>
        </li>
        <li class="mb5">
            <label class="sectionLabel-A1">缩放尺寸(mm)*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                长: <?php echo $form->textField($model,'max_length',array('size'=>'25','class'=>'mr10')); ?>
                宽: <?php echo $form->textField($model,'max_width',array('size'=>'25','class'=>'mr10')); ?>
                高: <?php echo $form->textField($model,'max_height',array('size'=>'25','class'=>'mr10')); ?>
            </div>
        </li>
        <li class="mb5">
            <label class="sectionLabel-A1">空间功能*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <div class="one_select">
                <?php echo $form->radioButtonList($model,'room_category',
                        Yii::app()->params['roomCategories'],array('separator'=>'&nbsp;')); ?>
                </div>
            </div>
        </li>
        <li class="mb5">
            <label class="sectionLabel-A1">是否公共空间*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <div class="one_select">
                    <?php echo $form->radioButtonList($model,'is_common',array(
                                                    '1'=>'是','0'=>'否',
                                                ),array('separator'=>'&nbsp;')); ?>
                    <?php echo $form->error($model,'is_common'); ?>
                </div>
            </div>
        </li>
        <li class="mb5">
            <label class="sectionLabel-A1">是否360空间*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <div class="one_select">
                    <?php echo $form->radioButtonList($model,'is_360',array(
                                                    '1'=>'是','0'=>'否',
                                                ),array('separator'=>'&nbsp;')); ?>
                    <?php echo $form->error($model,'is_360'); ?>
                </div>
            </div>
        </li>
        <li class="mb5">
            <label class="sectionLabel-A1">热度值*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <?php echo $form->textField($model,'hot_num',array('size'=>'25','class'=>'L mr10 input-xxlarge sectionAlertText inputLen')); ?>
            </div>
        </li>
        <?php if(!empty(Yii::app()->session['update'])): ?>
        <li class="mb5">
            <div class="sectionBox-A1 sectionBox-A1-1 sectionForm-A1 sectionForm-A1-2">
                <a class="button" href="#myModal" data-toggle="modal">
                    <?php echo CHtml::submitButton('保存',array('class'=>'btn btn-large btn-primary')); ?>
                </a>
            </div>
        </li>
        <?php endif; ?>
    </ul>
    <?php $this->endWidget(); ?>
</div>
<script type="text/javascript">
$(function(){
    //删除视角
    $(".del_angle_icon").live('click',function(){
        $(this).parent().remove();
    })
})
</script>
