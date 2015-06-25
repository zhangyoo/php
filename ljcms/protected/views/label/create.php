<div class="sectionTitle-A mb10">
    <h2><font color="#3E5999">创建物品标签</font></h2>
</div>
<div class="clear mb10">
    <div class="sectionBun-A2 L mr10">
        <a class="btn btn-primary" href="/label/index">标签列表</a>
    </div>
</div>
<div class="sectionList-B1 mb20">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'=>'LabelForm',
        'enableClientValidation'=>true,
        'htmlOptions'=>array(
            'enctype'=>'multipart/form-data',
        ),
        'focus'=>array($model,'name'),
    )); ?>
    <ul>
        <li class="mb5">
            <label class="sectionLabel-A1">标签名称*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <?php echo $form->textField($model,'name',array('class'=>'L mr10 input-xxlarge sectionAlertText')); ?>
            </div>
        </li>
        <li class="mb5">
            <label class="sectionLabel-A1">排序值：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                长: <?php echo $form->textField($model,'sort_num',array('size'=>'25','class'=>'mr10 inputLen')); ?>
            </div>
        </li>
        <li class="mb5">
            <label class="sectionLabel-A1">标签分类(默认为顶级分类)：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <?php
                    echo $form->dropDownList($model,'type',Yii::app()->params['labelType'],array(
                        'id'=>'labelType',
                        'empty'=>'请选择标签类型',
                        'ajax' =>
                            array(
                                'type'=>'POST', //request type
                                'url'=>Yii::app()->createUrl('label/getLabel'),
                                'update'=>'#label_parent', //selector to update
                                'data'=>array('type'=>'js:$("#labelType").val()')
                            )
                        )
                    );
                ?>
                <?php
                    echo CHtml::dropDownList('Label[parent_id]',$model['parent_id'],$pid,array(
                        'id'=>'label_parent',
                        'empty'=>'默认顶级分类',
                        )
                    );
                ?>
            </div>
        </li>
    </ul>
    <?php if(!empty(Yii::app()->session['update'])): ?>
    <div class="sectionBox-A1 sectionBox-A1-1 sectionForm-A1 sectionForm-A1-2">
        <?php echo CHtml::submitButton('保存',array('class'=>'btn btn-primary')); ?>&nbsp;&nbsp;&nbsp;
        <?php echo CHtml::Button('取消',array('class'=>'btn')); ?>
    </div>
    <?php endif; ?>
    <?php $this->endWidget(); ?>
</div>