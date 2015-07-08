<div class="sectionTitle-A mb10">
    <h2>修改密码</h2>
</div>
<div class="clear mb10">
    <div class="sectionBun-A2 L mr10">
        <a class="btn btn-primary" href="/default/index">返回首页</a>
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
            <label class="sectionLabel-A1">用户名*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <?php echo $form->textField($model,'username',array('size'=>'25','class'=>'L mr10 input-xxlarge sectionAlertText','onblur'=>'checkname(this)')); ?>
            </div>
        </li>
        <li class="mb5">
            <label class="sectionLabel-A1">密码*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <?php echo $form->passwordField($model,'password',array('size'=>'25','class'=>'L mr10 input-xxlarge sectionAlertText')); ?>
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
    //检查用户名和邮箱号是否重复
    function checkname(obm){
        var $obj=$(obm);
        var id=$obj.attr("id");
        var val=$obj.val();
        if(id=='User_username'){
            var type=1;//表示用户名
        }else{
            var type=2;//表示邮箱
        }
        var edit=1;
        var uid=<?php echo $_GET['id']; ?>;
        $.post("/user/check",{type:type,val:val,edit:edit,uid:uid},function(data){
            if(data.status){
                if($("#"+id).parent().find("span").length<=0){
                    $("#"+id).after("<span>"+data.info+"</span>");
                }
            }else{
                $("#"+id).parent().find("span").remove();
            }
        },'json')
    }
</script>
