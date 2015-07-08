                <div class="angle_image">
                    <i class="del_angle_icon colorDelIcon" rel="" mold_id=""> </i>
                    <p class="angleVal_space one_select">
                        是否有透明通道*：<?php echo CHtml::radioButtonList('texture_alpha['.$i.']','0',array('0'=>'否','1'=>'是'),
                            array('separator'=>'&nbsp;')); ?>
                        <?php echo CHtml::hiddenField('Texture[alpha][]','0',array()); ?>
                    </p>
                    <p class="angleVal_space one_select">
                        贴图类型*：<?php echo CHtml::radioButtonList('texture_type['.$i.']','1',array('1'=>'商品贴图','2'=>'墙体贴图','3'=>'地面贴图','4'=>'颜色贴图'),
                            array('separator'=>'&nbsp;')); ?>
                        <?php echo CHtml::hiddenField('Texture[type][]','1',array()); ?>
                    </p>
                    <p class="angleVal_space">
                        贴图尺寸：
                        长：<?php echo $form->textField($moldMap,'length[]',array('size'=>'25','value'=>'0','class'=>'mr10 inputLen')); ?>
                        宽：<?php echo $form->textField($moldMap,'width[]',array('size'=>'25','value'=>'0','class'=>'mr10 inputLen')); ?>
                        高：<?php echo $form->textField($moldMap,'height[]',array('size'=>'25','value'=>'0','class'=>'mr10 inputLen')); ?>
                    </p>
                    <p class="angleVal_space">
                        排序：<?php echo CHtml::textField('Texture[color_sort][]','0',array('size'=>'25','class'=>'mr10 inputLen')); ?>
                    </p>
                    <div class="anglePics moldColor">
                        模型缩略图*:<br>
                        <?php echo $form->FileField($moldMap,'image[]',array('class'=>'')); ?>
                    </div>
                    <div class="anglePics moldColor">
                        模型顶视图*:<br>
                        <?php echo $form->FileField($moldMap,'floorplan[]',array('class'=>'')); ?>
                    </div>
                    <div class="anglePics moldColor">
                        模型UV贴图*:<br>
                        <?php echo $form->hiddenField($moldMap,'uv_map[]')?>
                        <?php echo CHtml::Button('浏览...',array('class'=>'image_select'))?>
                        <span>未选择文件</span>
                    </div>
                    <div class="anglePics moldColor">
                        模型法线贴图:<br>
                        <?php echo $form->hiddenField($moldMap,'normal_map[]')?>
                        <?php echo CHtml::Button('浏览...',array('class'=>'image_select'))?>
                        <span>未选择文件</span>
                    </div>
                    <div class="anglePics moldColor">
                        模型高光贴图:<br>
                        <?php echo $form->hiddenField($moldMap,'specular_map[]')?>
                        <?php echo CHtml::Button('浏览...',array('class'=>'image_select'))?>
                        <span>未选择文件</span>
                    </div>
                </div>
<script type="text/javascript">
    var editor = KindEditor.editor({
			allowFileManager : true,
	});
    KindEditor('.image_select').click(function() {
        var $obj=$(this);
        editor.loadPlugin('image', function() {
            editor.plugin.imageDialog({
                showRemote : false,
                imageUrl : KindEditor('#url3').val(),
                clickFn : function(url, title, width, height, border, align) {
                    $obj.next().html(url.split("/")[6]);
                    $obj.prev().val(url);
                    editor.hideDialog();
                }
            });
        });
    });

</script>