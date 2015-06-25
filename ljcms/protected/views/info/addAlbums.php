<?php if(!empty($albums)): ?>
<?php foreach ($albums as $pd): ?>
<li>
    <?php echo CHtml::image(Yii::app()->params['static'].$pd['image'],'360度图片',array('style'=>'width:185px;height:185px')); ?>
    <span class="bind_pro_spa"><a class="R"><?php echo $pd['sort_num'];?></a></span>
</li>
<?php endforeach; ?>
<?php else: ?>
没有图片可供展示！
<?php endif; ?>