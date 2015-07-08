<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/info.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/order.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/res.js"></script>
<div class="sectionTitle-A mb10">
    <h2>查看模型</h2>
</div>
<div class="clear mb10">
    <div class="sectionBun-A2 L mr10">
        <a class="btn btn-primary" href="javascript:history.go(-1);">返回</a>
    </div>
</div>
<div class="sectionList-B1 mb20">
    <div class="order_info">
        <?php if(!empty($order)): ?>
        <span>订单编号：<?php echo $order['number']; ?></span>
        <span>起始时间：<font color="#FF0000"><?php echo date("Y-m-d H:i:s", $order["create_time"]); ?></font></span>
        <span>预计完成时间：<font color="#FF0000"><?php echo date("Y-m-d H:i:s", $order["end_time"]); ?></font></span>
        <?php endif; ?>
    </div>
    <div class="texture_display sectionTable-A1">
        <?php if(!empty($info)): ?>
        <?php $this->renderPartial('moldInfo',array('ul'=>$info,'order'=>$order,'oid'=>$order['id'],'colorsSN'=>$colorsSN)); ?>
        <?php endif; ?>
        <?php if(!in_array($model['mold_type'], array_keys(Yii::app()->params['YYForm']))): ?>
        <table border="1" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th class="col-1" width="8%">颜色</th>
                    <th class="col-2" width="38%">模型缩略图(请上传800×800的压缩图片.jpg)</th>
                    <th class="col-3" >
                        模型顶视图(请上传500×500的压缩图片.png)<br>
                        <font color="#FF0000">*模型顶视图尺寸(单位mm,长×宽×高)</font><br>
                        <span>
                            <input type="text" class="inputLenmin" name="length" placeholder="长" value="<?php echo !empty($lwh) ? $lwh['length'] :"";  ?>"/>&nbsp;
                            <input type="text" class="inputLenmin" name="width" placeholder="宽" value="<?php echo !empty($lwh) ? $lwh['width'] :"";  ?>"/>&nbsp;
                            <input type="text" class="inputLenmin" name="height" placeholder="高" value="<?php echo !empty($lwh) ? $lwh['height'] :"";  ?>"/>&nbsp;
                            <?php if(!empty(Yii::app()->session['update'])): ?>
                            <a href="javascript:void(0);" rel="<?php echo $model['id']; ?>" type="mold" onclick="changeTSize(this)" style="color:#FF0000">修改顶视图尺寸</a>
                            <?php endif; ?>
                        </span>
                    </th>
                    <th class="col-4" >操作</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($textures)): ?>
                <?php foreach ($textures as $texi): ?>
                <tr>
                    <td>
                        <input type="checkbox" name="color" disabled='true' <?php echo !empty($texi['texture']) ? "checked" :""; ?> /> &nbsp;
                        <img class="texture_color_img" src="<?php echo $texi['value']; ?>" alt="<?php echo $texi['name']; ?>" title="<?php echo $texi['name']; ?>" />
                    </td>
                    <td>
                        <?php $this->renderPartial('colorTexture',array('type'=>1,'texture'=>$texi,'column'=>'image','reference'=>$reference)); ?>
                    </td>
                    <td>
                        <?php $this->renderPartial('colorTexture',array('type'=>1,'texture'=>$texi,'column'=>'floorplan','reference'=>$reference)); ?>
                    </td>
                    <td>
                        <?php if(!empty($texi['texture']) && !empty(Yii::app()->session['delete'])): ?>
                        <a href="javascript:void(0);" obj_id="<?php echo $model['id']; ?>" type="Mold" rel="<?php echo $texi['texture']['id']; ?>" onclick="delTexture(this)">删除该颜色贴图</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <?php endif; ?>
        <div class="mold_text_type clearfix" <?php echo in_array($model['mold_type'], array_keys(Yii::app()->params['YYForm'])) ? "style='border-bottom:1px solid #CCCCCC;'":""; ?>>
            <div class="mold_text_GM">
                <span class="lef_moldTex"><b>上传模型的类型：</b></span>
                <span class="one_select rig_moldTex">
                    <?php if(!empty($moldCondition)): ?>
                    <?php foreach ($moldCondition as $kmc=>$mdc): ?>
                    <input type="radio" name="moldType" value="<?php echo $mdc['mid']; ?>" onchange="switchMT(this)" <?php echo $model['mold_type']==$kmc ? "checked":""; ?>/>
                    <label><?php echo $mdc['name']; ?></label>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </span>
            </div>
            <div class="mold_text_GM">
                <span class="lef_moldTex">模型名称*：</span>
                <span class="rig_moldTex">
                    <?php echo $model['name']; ?>
                </span>
            </div>
            <div class="mold_text_GM">
                <span class="lef_moldTex">模型路径*：</span>
                <span class="rig_moldTex"><input type="text" class="mold_path_text" name="mold_path" value="<?php echo $model['mold']; ?>" /></span>
            </div>
            <?php if(in_array($model['mold_type'], array_keys(Yii::app()->params['YYForm']))): ?>
            <div class="mold_text_GM">
                <span class="lef_moldTex">阴影贴图：</span>
                <span class="rig_moldTex">
                    <p class="tex_img_list">
                        <?php if(!empty($model['image'])): ?>
                        <?php $form = substr($model['image'], strrpos($model['image'], ".")+1, strlen($model['image']) - strrpos($model['image'], ".")); $newForm = strtoupper($form);?>
                        <img class="mold_yy_img" src="<?php echo Yii::app()->params['static'].$model['image']; ?>" alt="<?php echo $model['name']; ?>" title="<?php echo $model['name']; ?>" /> &nbsp;
                        <?php echo $model['name'].'_'.'XX_'.$model['length'].'-'.$model['width'].'-'.$model['height'].'_N_FX_'.$model['maker'].'.'; ?>
                        <?php else: ?>
                        无文件上传
                        <?php endif; ?>
                    </p>
                </span>
            </div>
            <?php endif; ?>
        </div>
        <?php if(!in_array($model['mold_type'], array_keys(Yii::app()->params['YYForm']))): ?>
        <table border="1" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th class="col-1" width="8%">颜色</th>
                    <th class="col-2" width="38%">UV贴图(颜色贴图，小图)</th>
                    <th class="col-3" >法线贴图(凹凸贴图，小图)</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($textures)): ?>
                <?php foreach ($textures as $texii): ?>
                <tr>
                    <td>
                        <input type="checkbox" name="color" disabled='true' <?php echo !empty($texii['texture']) ? "checked" :""; ?> /> &nbsp;
                        <img class="texture_color_img" src="<?php echo $texii['value']; ?>" alt="<?php echo $texii['name']; ?>" title="<?php echo $texii['name']; ?>" />
                    </td>
                    <td>
                        <?php $this->renderPartial('colorTexture',array('type'=>2,'texture'=>$texii,'column'=>'uv_map','reference'=>$reference)); ?>
                    </td>
                    <td>
                        <?php $this->renderPartial('colorTexture',array('type'=>2,'texture'=>$texii,'column'=>'normal_map','reference'=>$reference)); ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <table border="1" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th class="col-1" width="8%">颜色</th>
                    <th class="col-2" width="38%">UV贴图(颜色贴图，大图)</th>
                    <th class="col-3" >法线贴图(凹凸贴图，大图)</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($textures)): ?>
                <?php foreach ($textures as $texiii): ?>
                <tr>
                    <td>
                        <input type="checkbox" name="color" disabled='true' <?php echo !empty($texiii['texture']) ? "checked" :""; ?> /> &nbsp;
                        <img class="texture_color_img" src="<?php echo $texiii['value']; ?>" alt="<?php echo $texiii['name']; ?>" title="<?php echo $texiii['name']; ?>" />
                    </td>
                    <td>
                        <?php $this->renderPartial('colorTexture',array('type'=>2,'texture'=>$texiii,'column'=>'m_uv_map','reference'=>$reference)); ?>
                    </td>
                    <td>
                        <?php $this->renderPartial('colorTexture',array('type'=>2,'texture'=>$texiii,'column'=>'m_normal_map','reference'=>$reference)); ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>
<div class="pop_dialog_top" style="display: none;">
    <div class="dialog_two_top">
        <b id="chooseType" chooseValue="product">绑定商品(只能绑定一个)</b>
        <a href="javascript:void(0);" onclick="closeDia()"><img class="icon" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/cut_icon.png" alt="关闭弹框"/></a>
    </div>
    <div class="dialog_content_top">
        <div class="info_psearch">
            <select class="inputLen" id="selectBindType" style="margin:0">
                <option value="unbind">已绑定</option>
                <option value="bind">未绑定</option>
            </select>
            &nbsp;
            <input type="text" class="search_product_name" placeholder="商品名称(或商品货号)" name="searchName" />
            <input type="button" class="info_psearch_button"  onclick="ResSearchProduct(this)" value="搜索" />
        </div>
        <ul class="info_img_ul psBox">
            
        </ul>
        <div class="dialog_save savePfix">
            <input type="button" value="解除绑定" id="saveSecDia" rel="" bind="unbind" onclick="ResSProduct(this)"/>
        </div>
    </div>
</div>
<script type="text/javascript">
    //非顶视图图片出现缩略图
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
</script>