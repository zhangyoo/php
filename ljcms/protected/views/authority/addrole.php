<div class="sectionTitle-A mb10">
    <h2>添加节点</h2>
</div>
<div class="sectionList-B1 mb20">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'=>'form-horizontal',
        'enableClientValidation'=>true,
//        'focus'=>array($model,'name'),
    )); ?>
    <ul>
        <li class="mb5">
            <label class="sectionLabel-A1">节点名称*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <?php echo CHtml::textField('name','',array('id'=>'name','class'=>'L mr10 input-xxlarge sectionAlertText')); ?>
                <span style="color: #C3C3C3;">如：controler_action 控制器名称_方法名称</span>
            </div>
        </li>
        <li class="mb5">
            <label class="sectionLabel-A1">设置 读/写*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <?php echo CHtml::dropDownList('set','',array('0'=>'全部','read'=>'读取','write'=>'写入')); ?>
            </div>
        </li>
        <li class="mb5">
            <label class="sectionLabel-A1">描述：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <?php echo CHtml::textArea('description','',array('id'=>'description')); ?>
                <?php echo CHtml::hiddenField('type',1,array('id'=>'type')); ?>
                <?php echo CHtml::hiddenField('parent',$model->name); ?>
            </div>
        </li>
        <li class="mb5">
            <div class="sectionBox-A1 sectionBox-A1-1 sectionForm-A1 sectionForm-A1-2">
                <a class="button" href="#myModal" data-toggle="modal">
                    <?php echo CHtml::submitButton('保存',array('class'=>'btn btn-large btn-primary')); ?>
                </a>
            </div>
        </li>
    </ul>
    <?php $this->endWidget(); ?>
</div>