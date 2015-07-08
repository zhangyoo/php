<div class="sectionTitle-A mb10">
    <h2>创建层级</h2>
</div>
<div class="clear mb10">
    <div class="sectionBun-A2 L mr10">
        <a class="btn btn-primary" href="/node/index/sid/<?php echo $_GET['sid'] ?>">层级列表</a>
    </div>
</div>
<div class="sectionList-B1 mb20">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'=>'node-form',
        'enableClientValidation'=>true,
        'htmlOptions'=>array(
            'enctype'=>'multipart/form-data',
        ),
//        'focus'=>array($model,'name'),
    )); ?>
    <ul>
        <li class="mb5">
            <label class="sectionLabel-A1">层级名称*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <?php echo $form->textField($model,'name',array('id'=>'name')); ?>
            </div>
        </li>
        <li class="mb5">
            <label class="sectionLabel-A1">层级的类型*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <div class="one_select">
                    <?php echo $form->radioButtonList($model,'type',array('软装','硬装','家具','配饰'),array('separator'=>'&nbsp;')); ?>
                </div>
                <?php echo $form->error($model,'type'); ?>
            </div>
        </li>
        <li class="mb5">
            <label class="sectionLabel-A1">视角*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <?php echo CHtml::textField('Node[angle]',''); ?>
            </div>
        </li>
        <li class="mb5">
            <label class="sectionLabel-A1">层级*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <?php echo $form->textField($model,'layer'); ?>
                <?php echo $form->error($model,'layer'); ?>
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
    
<script type="text/javascript">
   $(function(){
            //提交按钮,所有验证通过方可提交
            $(".submit").click(function(){
                if(state){
                    $("#node-form").submit();
                }else{
                    return false;
                }
            });
            //提交表单检查层级名称
            $("#node-form").bind('submit',function(){
                if($("[name='Node[name]']").val()==''){
                    alert('请输入标题');
                    $("[name='Node[name]']").focus();
                    return false;
                }
            })
   });
 </script>
       


