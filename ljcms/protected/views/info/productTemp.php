<?php if(!empty($productData)): ?>
<?php foreach ($productData as $pd): ?>
<li isselect="0" onclick="choosePS(this)" objId="<?php echo $pd['product_id'];?>">
    <div class="deleteImg hide">
        <img width="22" height="23" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/select_icon.png"/>
    </div>
    <?php echo CHtml::image(Yii::app()->params['static'].$pd['product_img'],$pd['product_name'],array('style'=>'width:185px;height:185px')); ?>
    <span class="bind_pro_spa"><a class="R"><?php echo $pd['product_name'];?></a></span>
</li>
<?php endforeach; ?>
<?php else: ?>
您搜索的商品不存在或者已被删除!
<?php endif; ?>