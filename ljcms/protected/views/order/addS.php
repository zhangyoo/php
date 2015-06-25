<?php if(!empty($psData)): ?>
<?php foreach ($psData as $pd): ?>
<li>
    <?php echo CHtml::hiddenField('space[]',$pd['id'],array()); ?>
    <div class="deleteImg">
        <img width="22" height="23" onclick="delSbind(this)" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/del_p.png"/>
    </div>
    <?php echo CHtml::image(Yii::app()->params['static'].$pd['image'],$pd['name'],array('style'=>'width:185px;height:185px')); ?>
    <span class="bind_pro_spa"><?php echo $pd['name'];?><a class="R"><?php echo $pd['length'];?>*<?php echo $pd['width'];?></a></span>
</li>
<?php endforeach; ?>
<?php endif; ?>