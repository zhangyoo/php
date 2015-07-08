<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/common/kindeditor/themes/default/default.css" />
<script src="<?php echo Yii::app()->request->baseUrl; ?>/common/kindeditor/kindeditor.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/common/kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/info.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/order.js"></script>
<div class="sectionTitle-A mb10">
    <h2><font color="#3E5999">编辑素材</font></h2>
</div>
<div class="clear mb10">
    <div class="sectionBun-A2 L mr10">
        <a class="btn btn-primary" href="javascript:history.go(-1);">返回</a>
    </div>
</div>
<div class="sectionList-B1 mb20">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'=>'orderForm',
        'enableClientValidation'=>true,
        'htmlOptions'=>array(
            'enctype'=>'multipart/form-data',
        ),
//        'focus'=>array($model,'title'),
    )); ?>
    <ul>
        <li class="mb5">
            <label class="sectionLabel-A1">素材标题*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <?php echo $form->textField($model,'title',array('class'=>'L mr10 input-xxlarge sectionAlertText')); ?>
            </div>
        </li>
        <li class="mb5">
            <label class="sectionLabel-A1">素材型号：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <?php echo $form->textField($model,'item',array('class'=>'L mr10 input-xxlarge sectionAlertText')); ?>
            </div>
        </li>
        <li class="mb5">
            <label class="sectionLabel-A1">缩略图(请上传方形的素材图片)*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <?php echo $form->FileField($model,'image',array('cols'=>'30','rows'=>'1')); ?>
                <?php echo CHtml::image(Yii::app()->params['static'].$model['image'],$model['title'],array('width'=>'200')); ?>
            </div>
        </li>
        <li class="mb5">
            <label class="sectionLabel-A1">素材标签分类*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <?php
                    echo CHtml::dropDownList('Info[label_id][]',$selData['labels']['pid'],$selData['labels']['parent'],array(
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
                    echo CHtml::dropDownList('Info[label_id][]',$selData['labels']['cid'],$selData['labels']['child'],array(
                        'id'=>'label_child',
                        'empty'=>'请选择素材标签分类',
                        )
                    );
                ?>
            </div>
        </li>
        <li class="mb5">
            <label class="sectionLabel-A1">尺寸(mm)*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                长: <?php echo $form->textField($model,'length',array('size'=>'25','class'=>'mr10 inputLen')); ?>
                宽: <?php echo $form->textField($model,'width',array('size'=>'25','class'=>'mr10 inputLen')); ?>
                高: <?php echo $form->textField($model,'height',array('size'=>'25','class'=>'mr10 inputLen')); ?>
            </div>
        </li>
        <li class="mb5">
            <label class="sectionLabel-A1">素材商品分类*：</label>
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
                    echo CHtml::dropDownList('Info[category_id]',$thirdI,$thirdTem,array(
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
                    echo CHtml::dropDownList('Info[brandhall_id]',$model['brandhall_id'],$defaultData['brandhall']['top'],array(
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
                    echo CHtml::dropDownList('Info[brand_id][0]',$selData['Bsel']['pid'],$defaultData['brands']['top'],array(
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
                    echo CHtml::dropDownList('Info[brand_id][1]',$selData['Bsel']['secid'],$selData['Bsel']['second'],array(
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
            <label class="sectionLabel-A1">适合的功能空间*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <div class="one_select">
                <?php echo CHtml::checkBoxList('room_category[]',$selData['room_categorys'],
                        Yii::app()->params['roomCategories'],array('separator'=>'&nbsp;')); ?>
                </div>
            </div>
        </li>
        <li class="mb5">
            <label class="sectionLabel-A1">所属风格(最多两个风格)*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <?php
                    echo CHtml::checkBoxList('style[]',$selData['selStyle'],$defaultData['styles'],array(
                        'id'=>'styles','separator'=>'&nbsp;'
                        )
                    );
                ?>
            </div>
        </li>
        <li class="mb5">
            <label class="sectionLabel-A1">颜色*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4 one_select">
                <div class="disColorList">
                    <?php if(!empty($defaultData['colors'])): ?>
                    <?php foreach ($defaultData['colors'] as $kc=>$dc): ?>
                    <span>
                        <input id="color_0" type="checkbox" name="color[]" <?php echo in_array($kc, $selData['infoColor']) ? "checked" :""; ?> value="<?php echo $kc; ?>">
                        <img width="15" height="15" alt="" src="<?php $imgSrc = explode('|', $kc); echo $imgSrc[1]; ?>">
                        <label for="color_0"><?php echo $dc; ?></label>
                    </span>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </li>
        <?php if(!empty($model['orders']) && $model['orders'][0]['type'] == 0): ?>
        <li class="mb5">
            <label class="sectionLabel-A1">素材类型*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <div class="one_select" id="moldTypeSel">
                <?php
                    echo $form->radioButtonList($model,'type',Yii::app()->params['productType'],
                            array('separator'=>'&nbsp;'));
                ?>
                </div>
            </div>
        </li>
        <li class="mb5">
            <label class="sectionLabel-A1">是否360度旋转*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <div class="one_select">
                    <?php echo $form->radioButtonList($model,'is_rotation',array('0'=>'否','1'=>'是'),
                            array('separator'=>'&nbsp;','onchange'=>'is_360(this)','rel'=>$model['id'])); ?>
                        <?php echo $form->error($model,'is_rotation'); ?>
                </div>
            </div>
        </li>
        
        <li class="mb5" id="is_360_Content">
            <?php if($model['is_rotation'] == 1): ?>
            <?php $this->renderPartial('changeType',array('type'=>$model['is_rotation'],'default'=>$selData)); ?>
            <?php endif; ?>
        </li>
        <li class="mb5 moldPath">
            <label class="sectionLabel-A1">参考图片路径*：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <?php echo $form->textArea($model,'furniture_pics',array('cols'=>'30','rows'=>'1')); ?>
            </div>
        </li>
        <?php endif; ?>
        <li class="mb5">
            <label class="sectionLabel-A1">素材内容：</label>
            <div class="sectionBox-A1 clear sectionForm-A1 sectionForm-A1-4">
                <textarea id="infoContent" name="Info[content]" style="height: 300px; width: 750px;" ><?php echo isset($model['content']) ? $model['content']:'';?></textarea>
            </div>
        </li>
    </ul>
    <?php if(!empty(Yii::app()->session['update']) && !isset($_GET['task'])): ?>
    <div class="sectionBox-A1 sectionBox-A1-1 sectionForm-A1 sectionForm-A1-2">
        <?php echo CHtml::submitButton('保存',array('class'=>'btn btn-primary')); ?>&nbsp;&nbsp;&nbsp;
        <?php echo CHtml::Button('取消',array('class'=>'btn')); ?>
    </div>
    <?php endif; ?>
    <?php $this->endWidget(); ?>
</div>
<script type="text/javascript">
$(function(){
    //初始化KindEditor
    KindEditor.ready(function(K) {
        //加载KindEditor编辑器
        K.create('textarea[id="infoContent"]', {
            filterMode : true
        });
    }); 
    //检查输入的内容
    $("form#InfoForm").submit(function(){
        if($("#Info_title").val() == ''){
            alert("请输入素材标题");
            return false;
        }
//        if($("#labelType").val() == ''){
//            alert("请选择素材标签分类");
//            return false;
//        }
    })
    
})   
</script>