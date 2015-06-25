<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/common/kindeditor/themes/default/default.css" />
<script src="<?php echo Yii::app()->request->baseUrl; ?>/common/kindeditor/kindeditor.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/common/kindeditor/lang/zh_CN.js"></script>
<div class="sectionTitle-A mb10">
    <h2>修改商品贴图</h2>
</div>
<div class="clear mb10">
    <div class="sectionBun-A2 L mr10">
        <a class="btn btn-primary" href="/res/index">物品列表</a>
    </div>
</div>
<?php $defaultAI=Yii::app()->theme->baseUrl.'/images/defaultAngle.gif'; ?>
<div class="sectionList-B1 mb20">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'=>'form-horizontal',
        'enableClientValidation'=>true,
        'htmlOptions'=>array(
            'enctype'=>'multipart/form-data',
        ),
    )); ?>
    <ul>
        <li class="mb5">
            <label class="sectionLabel-A1">贴图设置*：
                <a href="javascript:void(0);" class="colorAddIcon" style="color: #3B5999;font-weight: normal">添加贴图</a>
            </label>
            <div id="addAngles" class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <?php if(!empty($textures)){ ?>
                <?php foreach ($textures as $km=>$kv): ?>
                <div class="angle_image">
                    <i class="del_angle_icon colorDelIcon" rel="<?php echo $kv['id']; ?>" pid="<?php echo $pid; ?>"> </i>
                    <p class="angleVal_space one_select">
                        是否有透明通道*：<?php echo CHtml::radioButtonList('texture_alpha['.$km.']',$kv['alpha'],array('0'=>'否','1'=>'是'),
                            array('separator'=>'&nbsp;')); ?>
                        <?php echo CHtml::hiddenField('Texture[alpha][]',$kv['alpha'],array()); ?>
                    </p>
                    <p class="angleVal_space one_select">
                        贴图类型*：<?php echo CHtml::radioButtonList('texture_type['.$km.']',$kv['type'],array('1'=>'商品贴图','2'=>'墙体贴图','3'=>'地面贴图'),
                            array('separator'=>'&nbsp;')); ?>
                        <?php echo CHtml::hiddenField('Texture[type][]',$kv['type'],array()); ?>
                    </p>
                    <p class="angleVal_space">
                        贴图尺寸：
                        长：<?php echo CHtml::textField('Texture[length][]',$kv['length'],array('size'=>'25','class'=>'mr10')); ?>
                        宽：<?php echo CHtml::textField('Texture[width][]',$kv['width'],array('size'=>'25','class'=>'mr10')); ?>
                        高：<?php echo CHtml::textField('Texture[height][]',$kv['height'],array('size'=>'25','class'=>'mr10')); ?>
                    </p>
                    <p class="angleVal_space">
                        排序：<?php echo CHtml::textField('Texture[color_sort][]',$selData['textures'][$kv['id']],array('size'=>'25','class'=>'mr10')); ?>
                    </p>
                    <div class="anglePics moldColor">
                        模型缩略图*:<br>
                        <?php echo $form->hiddenField($moldMap,'image[]')?>
                        <?php echo CHtml::Button('浏览...',array('class'=>'image3'))?>
                        <p>
                            <?php 
                            $imageImg=!empty($kv['image'])?Yii::app()->params['static'].$kv['image']:$defaultAI;
                            echo CHtml::image($imageImg,'模型缩略图',array('width'=>'200')); 
                            ?>
                        </p>
                    </div>
                    <div class="anglePics moldColor">
                        模型顶视图*:<br>
                        <?php echo $form->hiddenField($moldMap,'floorplan[]')?>
                        <?php echo CHtml::Button('浏览...',array('class'=>'image3'))?>
                        <p>
                            <?php 
                            $floorplanImg=!empty($kv['floorplan'])?Yii::app()->params['static'].$kv['floorplan']:$defaultAI;
                            echo CHtml::image($floorplanImg,'模型顶视图',array('width'=>'200')); 
                            ?>
                        </p>
                    </div>
                    <div class="anglePics moldColor">
                        模型UV贴图*:<br>
                        <?php echo $form->hiddenField($moldMap,'uv_map[]')?>
                        <?php echo CHtml::Button('浏览...',array('class'=>'image3'))?>
                        <p>
                            <?php 
                            $uv_mapImg=!empty($kv['uv_map'])?Yii::app()->params['static'].$kv['uv_map']:$defaultAI;
                            echo CHtml::image($uv_mapImg,'模型UV贴图',array('width'=>'200')); 
                            ?>
                        </p>
                    </div>
                    <div class="anglePics moldColor">
                        模型法线贴图:<br>
                        <?php echo $form->hiddenField($moldMap,'normal_map[]')?>
                        <?php echo CHtml::Button('浏览...',array('class'=>'image3'))?>
                        <p>
                            <?php 
                            $normal_mapImg=!empty($kv['normal_map'])?Yii::app()->params['static'].$kv['normal_map']:$defaultAI;
                            echo CHtml::image($normal_mapImg,'模型法线贴图',array('width'=>'200')); 
                            ?>
                        </p>
                    </div>
                    <div class="anglePics moldColor">
                        模型高光贴图:<br>
                        <?php echo $form->hiddenField($moldMap,'specular_map[]')?>
                        <?php echo CHtml::Button('浏览...',array('class'=>'image3'))?>
                        <p>
                            <?php 
                            $specular_mapImg=!empty($kv['specular_map'])?Yii::app()->params['static'].$kv['specular_map']:$defaultAI;
                            echo CHtml::image($specular_mapImg,'模型黑白图',array('width'=>'200')); 
                            ?>
                        </p>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php }else{ ?>
                <div class="angle_image">
                    <i class="del_angle_icon colorDelIcon" rel="" mold_id=""> </i>
                    <p class="angleVal_space one_select">
                        是否有透明通道*：<?php echo CHtml::radioButtonList('texture_alpha[0]','0',array('0'=>'否','1'=>'是'),
                            array('separator'=>'&nbsp;')); ?>
                        <?php echo CHtml::hiddenField('Texture[alpha][]','0',array()); ?>
                    </p>
                    <p class="angleVal_space one_select">
                        贴图类型*：<?php echo CHtml::radioButtonList('texture_type[0]','1',array('1'=>'商品贴图','2'=>'墙体贴图','3'=>'地面贴图'),
                            array('separator'=>'&nbsp;')); ?>
                        <?php echo CHtml::hiddenField('Texture[type][]','1',array()); ?>
                    </p>
                    <p class="angleVal_space">
                        贴图尺寸：
                        长：<?php echo $form->textField($moldMap,'length[]','',array('size'=>'25','class'=>'mr10')); ?>
                        宽：<?php echo $form->textField($moldMap,'width[]','',array('size'=>'25','class'=>'mr10')); ?>
                        高：<?php echo $form->textField($moldMap,'height[]','',array('size'=>'25','class'=>'mr10')); ?>
                    </p>
                    <p class="angleVal_space">
                        排序：<?php echo CHtml::textField('Texture[color_sort][]','0',array('size'=>'25','class'=>'mr10')); ?>
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
                        <?php echo CHtml::Button('浏览...',array('class'=>'image3'))?>
                        <span>未选择文件</span>
                    </div>
                    <div class="anglePics moldColor">
                        模型法线贴图:<br>
                        <?php echo $form->hiddenField($moldMap,'normal_map[]')?>
                        <?php echo CHtml::Button('浏览...',array('class'=>'image3'))?>
                        <span>未选择文件</span>
                    </div>
                    <div class="anglePics moldColor">
                        模型高光贴图:<br>
                        <?php echo $form->hiddenField($moldMap,'specular_map[]')?>
                        <?php echo CHtml::Button('浏览...',array('class'=>'image3'))?>
                        <span>未选择文件</span>
                    </div>
                </div>
                <?php } ?>
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
        
        var preUrl = "<?php echo Yii::app()->request->BaseUrl;?>";
        KindEditor.ready(function(K) {
            var editor = K.editor({
                allowFileManager : true
            });
            K('.image3').click(function() {
                var $obj=$(this);
                editor.loadPlugin('image', function() {
                    editor.plugin.imageDialog({
                        showRemote : false,
                        imageUrl : K('#url3').val(),
                        clickFn : function(url, title, width, height, border, align) {
                            <?php if(!empty($textures)): ?>
                            $obj.next().find("img").attr('src',preUrl+url);
                            <?php else: ?>
                            $obj.next().html(url.split("/")[6]);    
                            <?php endif; ?>
                            $obj.prev().val(url);
                            editor.hideDialog();
                        }
                    });
                });
            });

        });
            
        //添加颜色帧
        $(".colorAddIcon").live('click',function(){
            var i = (new Date()).valueOf();
            $.post("/mold/addColor",{i:i},function(html){
                $("#addAngles").append(html);
            },'html')
        })
        
        //单选按钮
        $(".angle_image .one_select input").live('click',function(){
            var radioVal = $(this).val();
            if(radioVal !='')
                $(this).parent().next().val(radioVal);
        })
        
        //删除颜色帧
        $(".colorDelIcon").live('click',function(){
            var mmId = $(this).attr("rel");
            var id = $(this).attr("pid");
            if(confirm('确定删除此贴图吗？')){
                if(mmId !='' && id !=''){
                    $.post("/mold/delMoldMap",{mmId:mmId,id:id,model:"Product"},function(data){
                        if(data.status){
                            alert(data.info);
                        }
                    },'json')
                }
                $(this).parent().remove();
            }
        })
        
    })    
</script>

