<div class="sectionTitle-A mb10">
    <h2>编辑元素</h2>
</div>
<div class="sectionList-B1 mb20">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'=>'element-form',
        'enableClientValidation'=>true,
        'htmlOptions'=>array('enctype'=>'multipart/form-data'),
//	    'focus'=>array($model,'name'),
    )); ?>
    <ul>
        <li class="mb5">
            <label class="sectionLabel-A1">名 称*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <?php echo $form->textField($model,'name',array('size'=>'25','class'=>'maxInputLen')); ?>
            </div>
        </li>
        <li class="mb5">
            <label class="sectionLabel-A1">元素说明：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <?php echo $form->textArea($model,'summary',array('size'=>'25','class'=>'')); ?>
            </div>
        </li>
        <?php if(!empty(Yii::app()->session['update'])): ?>
        <li class="mb5">
            <div class="sectionBox-A1 sectionBox-A1-1 sectionForm-A1 sectionForm-A1-2">
                <a class="button" href="#myModal" data-toggle="modal">
                    <input type='hidden' name='callbak' value='<?php echo $_SERVER['HTTP_REFERER'];?>'><!--获取上一页url-->
                    <?php echo CHtml::submitButton('保存',array('class'=>'btn btn-large btn-primary')); ?>
                </a>
            </div>
        </li>
        <?php endif; ?>
    </ul>
<?php $this->endWidget(); ?>
</div>