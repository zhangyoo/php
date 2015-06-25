<?php if(!empty($productData)): ?>
<?php foreach ($productData as $pd): ?>
<li isselect="0" onclick="choosePS(this)" objId="<?php echo $type == 'product' ? $pd['product_id'] : $pd['id'];?>">
    <div class="deleteImg hide">
        <img width="22" height="23" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/select_icon.png"/>
    </div>
    <?php if($type == 'product'): ?>
    <?php echo CHtml::image(Yii::app()->params['static'].$pd['product_img'],$pd['product_name'],array('style'=>'width:185px;height:185px')); ?>
    <span class="bind_pro_spa">
        <a href="javascript:void(0);" class="R" title="<?php echo $pd['product_name'];?>"><?php echo mb_substr(strip_tags($pd['product_name']),0,26,'utf8');?></a>
    </span>
    <?php else: ?>
    <?php echo CHtml::image(Yii::app()->params['static'].$pd['image'],$pd['name'],array('style'=>'width:185px;height:185px')); ?>
    <span class="bind_pro_spa">
        <a href="javascript:void(0);" title="<?php echo $pd['name'];?>"><?php echo mb_substr(strip_tags($pd['name']),0,12,'utf8');?></a>
        <a href="javascript:void(0);" class="R" title="<?php echo $pd['item'];?>"><?php echo mb_substr(strip_tags($pd['item']),0,12,'utf8');?></a>
    </span>
    <?php endif; ?>
</li>
<?php endforeach; ?>
<?php else: ?>
<?php if($type == 'product'): ?>
没有检索到相关的商品信息！
<?php else: ?>
没有检索到相关的模型信息！
<?php endif; ?>
<?php endif; ?>