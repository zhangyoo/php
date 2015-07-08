<div class="dialogBoxWrap clearfix">
    <div class="dialogBox1 mb20 fl">
        <div class="dialogBox1-top clearfix mb5">
            <div class="dateTime fl"><?php echo date("Y-m-d H:i:s",$model['addtime']); ?></div>
            <div class="contact fr"><?php echo $mine['username']; ?></div>
        </div>
        <div class="dialogBox1-info">
            <?php echo $model['content']; ?>
        </div>                           
        <i class="trigon"></i>
    </div>
    <div class="contactPic fr mr15">
        <?php
            $imgright=  empty($mine['image']) ? '/common/images_temp/headPortrait.png' : Yii::app()->params['static'].$mine['image'];
            echo CHtml::image($imgright,$mine['username'],array('width'=>'50px','height'=>'50px')); 
        ?>
    </div>
</div>