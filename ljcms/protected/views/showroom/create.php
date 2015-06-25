<div class="sectionTitle-A mb10">
    <h2>创建样板间</h2>
</div>
<div class="clear mb10">
    <div class="sectionBun-A2 L mr10">
        <a class="btn btn-primary" href="/showroom/index/sid/<?php echo $sid; ?>">样板间列表</a>
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
            <label class="sectionLabel-A1">样板间名称*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <?php echo $form->textField($model,'name',array('size'=>'25','class'=>'')); ?>
                
            </div>
        </li>
        <li class="mb5">
            <label class="sectionLabel-A1">视角*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <?php
                    echo $form->dropDownList($model,'angle',$angles,array(
                        'id'=>'angle_id',
                        'empty'=>'请选择',
                        )
                    );
                ?>
            </div>
        </li>
        <li class="mb5">
            <label class="sectionLabel-A1">样板间封面图*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <?php echo $form->FileField($model,'image',array('class'=>'btn btn-mini L mr10')); ?>
                <?php 
                    $defaultAI=Yii::app()->theme->baseUrl.'/images/defaultAngle.gif';
                    $picsAngle=!empty($model['image'])?Yii::app()->params['static'].$model['image']:$defaultAI;
                    echo CHtml::image($picsAngle,'空间空模图',array('width'=>'200'));
                ?>
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

