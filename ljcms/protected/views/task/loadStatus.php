<?php $form = $this->beginWidget('CActiveForm', array(
        'id'=>'statusForm',
        'action'=>'',
        'enableClientValidation'=>true,
        'htmlOptions'=>array(
            'enctype'=>'multipart/form-data',
        ),
    )); ?>
        <div class="editStatusDetail" style="margin-bottom:50px;">
            <ul id="loadStatusContent">
                <?php if(!empty($order)): ?>
                <li class="mb5">
                    <label class="sectionLabel-A1 mt10">订单状态*：</label>
                    <?php echo CHtml::hiddenField('order_id',$order['id'],array()); ?>
                    <div class="clear one_select">
                        <?php
                        if(!empty($sid)){
                            echo CHtml::radioButtonList('order_status',$order['space_status'],Yii::app()->params['orderStatus'],
                                    array('separator'=>'&nbsp;')
                            );
                        }else{
                            echo CHtml::radioButtonList('order_status',$order['status'],Yii::app()->params['orderStatus'],
                                    array('separator'=>'&nbsp;')
                            );
                        }
                        ?>
                    </div>
                </li>
                <?php endif; ?>
                <?php if(!empty($info)): ?>
                <li class="mb5">
                    <label class="sectionLabel-A1 mt10">素材状态*：</label>
                    <?php echo CHtml::hiddenField('info_id',$info['id'],array()); ?>
                    <div class="clear one_select">
                        <?php
                            echo CHtml::radioButtonList('info_status',$info['status'],array('0'=>'待制作','1'=>'进行中','2'=>'已完成'),
                                    array('separator'=>'&nbsp;')
                            );
                        ?>
                    </div>
                </li>
                <?php endif; ?>
                <?php if(!empty($ta)): ?>
                <li class="mb5">
                    <label class="sectionLabel-A1 mt10">任务状态*：</label>
                    <?php echo CHtml::hiddenField('task_id',$ta['id'],array()); ?>
                    <div class="clear one_select">
                        <?php
                            echo CHtml::radioButtonList('task_status',$ta['status'],array('0'=>'未完成','1'=>'已完成'),
                                    array('separator'=>'&nbsp;')
                            );
                        ?>
                    </div>
                </li>
                <?php if($ta['status']==1): ?>
<!--                <li class="mb5">
                    <label class="sectionLabel-A1 mt10">是否审核*：</label>
                    <div class="clear one_select">
                        <?php
                            echo CHtml::radioButtonList('is_check',$ta['is_check'],array('0'=>'未审核','1'=>'已审核','2'=>'审核不通过'),
                                    array('separator'=>'&nbsp;','onchange'=>'checkTask(this)')
                            );
                        ?>
                    </div>
                </li>-->
                <li class="mb5" id="taskSumm" style="display: none;">
                    <label class="sectionLabel-A1">审核不通过原因：</label>
                    <div class="clear">
                        <textarea id="task_summary" name="task_summary" style="height: 300px; width: 750px;" ></textarea>
                    </div>
                </li>
                <?php endif; ?>
                <?php endif; ?>
            </ul>
        </div>
        <div class="editStatus_save">
            <?php if(!empty($sid)){ echo CHtml::hiddenField('sid',$sid,array()); } ?>
            <input type="button" value="保存" rel="" onclick="saveEditStatus(this)"/>
        </div>
<?php $this->endWidget(); ?>     
<script type="text/javascript">
    //加载编辑器
    var editorVal=KindEditor.create('textarea[id="task_summary"]', {
        filterMode : true
    });
    var editor = KindEditor.editor({
			allowFileManager : true,
//            uploadJson : "/manage/design/uploadTempPic",暂时注销
	});
</script>