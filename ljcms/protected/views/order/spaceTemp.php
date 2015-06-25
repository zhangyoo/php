<?php if(!empty($spaceData)): ?>
<?php foreach ($spaceData as $pd): ?>
<li isselect="0" onclick="choosePS(this)" objId="<?php echo $pd['id'];?>">
    <div class="deleteImg hide">
        <img width="22" height="23" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/select_icon.png"/>
    </div>
    <?php echo CHtml::image(Yii::app()->params['static'].$pd['image'],$pd['name'],array('style'=>'width:185px;height:185px')); ?>
    <span class="bind_pro_spa"><?php echo $pd['name'];?><a class="R"><?php echo $pd['length'];?>*<?php echo $pd['width'];?></a></span>
</li>
<?php endforeach; ?>
<?php else: ?>
没有检索到复合条件的空间数据！
<?php endif; ?>