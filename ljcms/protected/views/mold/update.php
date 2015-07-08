<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/common/kindeditor/themes/default/default.css" />
<script src="<?php echo Yii::app()->request->baseUrl; ?>/common/kindeditor/kindeditor.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/common/kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/info.js"></script>
<div class="sectionTitle-A mb10">
    <h2>编辑模型</h2>
</div>
<div class="clear mb10">
    <div class="sectionBun-A2 L mr10">
        <a class="btn btn-primary" href="javascript:history.go(-1);">返回</a>
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
//        'focus'=>array($model,'name'),
    )); ?>
    <ul>
        <li class="mb5">
            <label class="sectionLabel-A1">模型名称*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <?php echo $form->textField($model,'name',array('size'=>'25','class'=>'L mr10 input-xxlarge sectionAlertText')); ?>
            </div>
        </li>
        <li class="mb5">
            <label class="sectionLabel-A1">模型编号*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <?php echo $form->textField($model,'item',array('size'=>'25','class'=>'')); ?>
            </div>
        </li>
        <li class="mb5">
            <label class="sectionLabel-A1">模型质量类型*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <div class="one_select" id="moldTypeSel">
                <?php echo $form->radioButtonList($model,'mold_type',Yii::app()->params['moldType'],
                            array('separator'=>'&nbsp;','disabled'=>true)); ?>
                </div>
            </div>
        </li>
        <li class="mb5 moldPath" style="display:<?php echo $model['mold_type'] == 0 ? 'block' : 'none'; ?>">
            <label class="sectionLabel-A1">模型文件路径*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <?php if($model['mold_type'] == 0){ ?>
                <?php echo CHtml::textArea('moldText',$model['mold'],array('cols'=>'30','rows'=>'1')); ?>
                <?php }else{ ?>
                <?php echo CHtml::textArea('moldText','',array('cols'=>'30','rows'=>'1')); ?>
                <?php } ?>
            </div>
        </li>
        <li class="mb5 moldUp" style="display:<?php echo $model['mold_type'] == 0 ? 'none' : 'block'; ?>">
            <label class="sectionLabel-A1">模型文件上传*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <?php echo $form->FileField($model,'mold',array('cols'=>'30','rows'=>'1')); ?>
                <?php echo !empty($model['mold']) && $model['mold_type'] != 0 ? $model['mold'] : ''; ?>
            </div>
        </li>
        <li class="mb5 moldImage" style="display:<?php echo $model['mold_type'] == 2 ? 'block' : 'none'; ?>">
            <label class="sectionLabel-A1">阴影模型缩略图*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <?php echo $form->FileField($model,'image',array('cols'=>'30','rows'=>'1')); ?>
                <p>
                    <?php 
                    $imageImg=!empty($model['image'])?Yii::app()->params['static'].$model['image']:$defaultAI;
                    echo CHtml::image($imageImg,'模型缩略图',array('width'=>'200')); 
                    ?>
                </p>
            </div>
        </li>
        <li class="mb5">
            <label class="sectionLabel-A1">模型说明：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <?php echo $form->textArea($model,'summary',array('id'=>'summary')); ?>
            </div>
        </li>
        <li class="mb5">
            <label class="sectionLabel-A1">标签分类*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <?php
                    echo CHtml::dropDownList('Mold[label_id][]',$selData['labels']['pid'],$selData['labels']['parent'],array(
                        'id'=>'labelType',
                        'empty'=>'请选择素材标签分类',
                        'ajax' =>
                            array(
                                'type'=>'POST', //request type
                                'url'=>Yii::app()->createUrl('label/getLabelChild'),
                                'update'=>'#label_child', //selector to update
                                'data'=>array('pid'=>'js:$("#labelType").val()')
                            )
                        )
                    );
                ?>
                <?php
                    echo CHtml::dropDownList('Mold[label_id][]',$selData['labels']['cid'],$selData['labels']['child'],array(
                        'id'=>'label_child',
                        'empty'=>'请选择素材标签分类',
                        )
                    );
                ?>
            </div>
        </li>
        <li class="mb5">
            <label class="sectionLabel-A1">模型尺寸(mm)*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                长: <?php echo $form->textField($model,'length',array('size'=>'25','class'=>'mr10 inputLen')); ?>
                宽: <?php echo $form->textField($model,'width',array('size'=>'25','class'=>'mr10 inputLen')); ?>
                高: <?php echo $form->textField($model,'height',array('size'=>'25','class'=>'mr10 inputLen')); ?>
            </div>
        </li>
        <li class="mb5">
            <label class="sectionLabel-A1">模型分类*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <?php
                    echo CHtml::dropDownList('top',$selData['selCat']['top_id'],$defaultData['category']['top'],array(
                        'id'=>'category_top_id',
                        'empty'=>'请选择',
                        'ajax' =>
                            array(
                                'type'=>'POST', //request type
                                'url'=>Yii::app()->createUrl('mold/getCat'),
                                'update'=>'#category_second_id', //selector to update
                                'data'=>array('pid'=>'js:$("#category_top_id").val()','model'=>'Category')
                            )
                        )
                    );
                ?>
                <?php
                    if(!empty($selData['selCat']['top_id']) && !empty($selData['selCat']['selectCat']) && !empty($selData['selCat']['second_id'])){
                        $secondTem=$selData['selCat']['selectCat'][$selData['selCat']['top_id']];
                        $secondI=$selData['selCat']['second_id'];
                    }else{
                        $secondTem=$defaultData['category']['second'];
                        $secondI='';
                    }
                    echo CHtml::dropDownList('second',$secondI,$secondTem,array(
                        'id'=>'category_second_id',
                        'empty'=>'请选择',
                        'ajax' =>
                            array(
                                'type'=>'POST', //request type
                                'url'=>Yii::app()->createUrl('mold/getCat'),
                                'update'=>'#category_third_id', //selector to update
                                'data'=>array('pid'=>'js:$("#category_second_id").val()','model'=>'Category')
                            )
                        )
                    );
                ?>
                <?php
                    if(!empty($model['category_id']) && !empty($selData['selCat']['selectCat'])){
                        $thirdTem=$selData['selCat']['selectCat'][$model['category_id']];
                        $thirdI=$model['category_id'];
                    }else{
                        $thirdTem=$defaultData['category']['third'];
                        $thirdI='';
                    }
                    echo CHtml::dropDownList('Mold[category_id]',$thirdI,$thirdTem,array(
                        'id'=>'category_third_id',
                        'empty'=>'请选择',
                        )
                    );
                ?>
            </div>
        </li>
        <li class="mb5">
            <label class="sectionLabel-A1">品牌馆/品牌/系列*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <?php
                    echo CHtml::dropDownList('Mold[brandhall_id]',$model['brandhall_id'],$defaultData['brandhall']['top'],array(
                        'id'=>'brandhall_top_id',
                        'empty'=>'请选择',
                        'ajax' =>
                            array(
                                'type'=>'POST', //request type
                                'url'=>Yii::app()->createUrl('mold/getCat'),
                                'update'=>'#brand_top_id', //selector to update
                                'data'=>array('pid'=>'js:$("#brandhall_top_id").val()','model'=>'Brand','source'=>'brandhall')
                            )
                        )
                    );
                ?>
                <?php
                    echo CHtml::dropDownList('Mold[brand_id][0]',$selData['Bsel']['pid'],$defaultData['brands']['top'],array(
                        'id'=>'brand_top_id',
                        'empty'=>'请选择',
                        'ajax' =>
                            array(
                                'type'=>'POST', //request type
                                'url'=>Yii::app()->createUrl('mold/getCat'),
                                'update'=>'#brand_second_id', //selector to update
                                'data'=>array('pid'=>'js:$("#brand_top_id").val()','model'=>'Brand')
                            )
                        )
                    );
                ?>
                <?php
                    echo CHtml::dropDownList('Mold[brand_id][1]',$selData['Bsel']['secid'],$selData['Bsel']['second'],array(
                        'id'=>'brand_second_id',
                        'empty'=>'请选择',
                        )
                    );
                ?>
            </div>
        </li>
        <li class="mb5">
            <label class="sectionLabel-A1">材质*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <?php
                    echo CHtml::dropDownList('material_id[0]',$selData['Msel']['pid'],$defaultData['materials']['top'],array(
                        'id'=>'material_top_id',
                        'empty'=>'请选择',
                        'ajax' =>
                            array(
                                'type'=>'POST', //request type
                                'url'=>Yii::app()->createUrl('mold/getCat'),
                                'update'=>'#material_second_id', //selector to update
                                'data'=>array('pid'=>'js:$("#material_top_id").val()','model'=>'Material')
                            )
                        )
                    );
                ?>
                <?php
                    echo CHtml::dropDownList('material_id[1]',$selData['Msel']['secid'],$selData['Msel']['second'],array(
                        'id'=>'material_second_id',
                        'empty'=>'请选择',
                        )
                    );
                ?>
            </div>
        </li>
        <li class="mb5">
            <label class="sectionLabel-A1">所属风格*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <span id="style">
                    <?php if(!empty($defaultData['styles'])): ?>
                    <?php foreach ($defaultData['styles'] as $ks=>$st): ?>
                    <input id="style_<?php echo $ks; ?>" type="checkbox" name="style[]" <?php echo in_array($ks, $selData['selStyle'])?'checked':''; ?> value="<?php echo $ks; ?>">
                    <label for="style_<?php echo $ks; ?>"><?php echo $st;?></label>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </span>
            </div>
        </li>
        <li class="mb5">
            <label class="sectionLabel-A1">模型类型*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <div class="one_select">
                    <?php echo $form->radioButtonList($model,'type',Yii::app()->params['productType'],
                            array('separator'=>'&nbsp;')); ?>
                        <?php echo $form->error($model,'type'); ?>
                </div>
            </div>
        </li>
        <?php if(!in_array($model['mold_type'], array_keys(Yii::app()->params['YYForm']))): ?>
        <li class="mb5">
            <label class="sectionLabel-A1">
                贴图设置*：
            </label>
            <div id="addAngles" class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <div class="texture_display sectionTable-A1">
                    <div class="mold_text_type change_mold_tex_style clearfix">
                        <div class="mold_text_GM">
                            <span>
                                <font color="#FF0000">*模型顶视图尺寸(单位mm,长×宽×高):</font>&nbsp;&nbsp;
                                <input type="text" class="inputLenmin" name="length" placeholder="长" value="<?php echo !empty($textures[0]['texture']) ? $textures[0]['texture']['length'] :"";  ?>"/>&nbsp;
                                <input type="text" class="inputLenmin" name="width" placeholder="宽" value="<?php echo !empty($textures[0]['texture']) ? $textures[0]['texture']['width'] :"";  ?>"/>&nbsp;
                                <input type="text" class="inputLenmin" name="height" placeholder="高" value="<?php echo !empty($textures[0]['texture']) ? $textures[0]['texture']['height'] :"";  ?>"/>
                            </span>
                        </div>
                    </div>
                    <table cellspacing="0" cellpadding="0" border="1">
                        <thead>
                            <tr>
                                <th width="8%" class="col-1">颜色</th>
                                <th width="38%" class="col-2">模型缩略图(请上传800×800的压缩图片.jpg)</th>
                                <th class="col-3">模型顶视图(请上传500×500的压缩图片.png)</th>
                                <th class="col-4" >操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($textures)): ?>
                            <?php foreach ($textures as $texi): ?>
                            <tr>
                                <td>
                                    <?php if(!empty($texi['value']) && !empty($texi['name'])): ?>
                                    <img class="texture_color_img" src="<?php echo $texi['value']; ?>" alt="<?php echo $texi['name']; ?>" title="<?php echo $texi['name']; ?>" />
                                    <?php else: ?>
                                    无颜色值
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php $this->renderPartial('colorTexture',array('type'=>1,'texture'=>$texi,'column'=>'image','reference'=>$reference,'colors'=>$colors)); ?>
                                </td>
                                <td>
                                    <?php $this->renderPartial('colorTexture',array('type'=>1,'texture'=>$texi,'column'=>'floorplan','reference'=>$reference,'colors'=>$colors)); ?>
                                </td>
                                <td>
                                    <?php if(!empty($texi['texture'])): ?>
                                    <a href="javascript:void(0);" obj_id="<?php echo $model['id']; ?>" type="Mold" rel="<?php echo $texi['texture']['id']; ?>" onclick="delTexture(this)">删除该颜色贴图</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <table cellspacing="0" cellpadding="0" border="1">
                        <thead>
                            <tr>
                                <th width="8%" class="col-1">颜色</th>
                                <th width="38%" class="col-2">UV贴图(颜色贴图，小图)</th>
                                <th class="col-3">法线贴图(凹凸贴图，小图)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($textures)): ?>
                            <?php foreach ($textures as $texii): ?>
                            <tr>
                                <td>
                                    <?php if(!empty($texii['value']) && !empty($texii['name'])): ?>
                                    <img class="texture_color_img" src="<?php echo $texii['value']; ?>" alt="<?php echo $texii['name']; ?>" title="<?php echo $texii['name']; ?>" />
                                    <?php else: ?>
                                    无颜色值
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php $this->renderPartial('colorTexture',array('type'=>2,'texture'=>$texii,'column'=>'uv_map','reference'=>$reference,'colors'=>$colors)); ?>
                                </td>
                                <td>
                                    <?php $this->renderPartial('colorTexture',array('type'=>2,'texture'=>$texii,'column'=>'normal_map','reference'=>$reference,'colors'=>$colors)); ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <table cellspacing="0" cellpadding="0" border="1">
                        <thead>
                            <tr>
                                <th width="8%" class="col-1">颜色</th>
                                <th width="38%" class="col-2">UV贴图(颜色贴图，大图)</th>
                                <th class="col-3">法线贴图(凹凸贴图，大图)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($textures)): ?>
                            <?php foreach ($textures as $texiii): ?>
                            <tr>
                                <td>
                                    <?php if(!empty($texiii['value']) && !empty($texiii['name'])): ?>
                                    <img class="texture_color_img" src="<?php echo $texiii['value']; ?>" alt="<?php echo $texiii['name']; ?>" title="<?php echo $texiii['name']; ?>" />
                                    <?php else: ?>
                                    无颜色值
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php $this->renderPartial('colorTexture',array('type'=>2,'texture'=>$texiii,'column'=>'m_uv_map','reference'=>$reference,'colors'=>$colors)); ?>
                                </td>
                                <td>
                                    <?php $this->renderPartial('colorTexture',array('type'=>2,'texture'=>$texiii,'column'=>'m_normal_map','reference'=>$reference,'colors'=>$colors)); ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </li>
        <?php endif; ?>
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
        //图片出现缩略图
        $(".tex_img_list img").hover(function(){
            var srcImg = $(this).attr('src');
            var img="<div id='img_msg_mold' ><img alt='' src='"+srcImg+"' /></div>";
            $(this).parent().find("#img_msg_mold").remove();
            $(this).parent().append(img);	
            $("#img_msg_mold").css("display","block");		
        },function(){
            $(this).parent().find("#img_msg_mold").remove();
        });
        
        //顶视图图片出现缩略图
        $(".tex_img_list_floorplan img").hover(function(){
            var srcImg = $(this).attr('src');
            var img="<div id='img_msg_mold' ><img alt='' src='"+srcImg+"' width='100%' /></div>";
            $(this).parent().find("#img_msg_mold").remove();
            $(this).parent().append(img);	
            $("#img_msg_mold").css("display","block");		
        },function(){
            $(this).parent().find("#img_msg_mold").remove();
        });
        
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
                            <?php if(!empty($selData['moldMapData'])): ?>
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
            var id = $(this).attr("mold_id");
            if(confirm('确定删除此贴图吗？')){
                if(mmId !='' && id !=''){
                    $.post("/mold/delMoldMap",{mmId:mmId,id:id,model:"Mold"},function(data){
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

