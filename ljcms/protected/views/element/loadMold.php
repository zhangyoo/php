            <?php if(!empty($mold)): ?>
            <?php  foreach ($mold as $m): ?>
            <li objid="<?php echo $m['id']; ?>" onclick="choosePS(this)" isselect="0">
                <div class="deleteImg hide">
                    <img width="22" height="23" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/select_icon.png">
                </div>
                <img alt="<?php echo mb_substr(strip_tags($m['name']),0,20,'utf8'); ?>" src="<?php echo Yii::app()->params['static'].$m['image']; ?>" style="width:185px;height:185px">    
                <span class="bind_pro_spa">
                    <a href="javascript:void(0);" title="<?php echo $m['name']; ?>"><?php echo mb_substr(strip_tags($m['name']),0,8,'utf8'); ?>...</a>
                    <a class="R" title="<?php echo $m['item']; ?>"><?php echo mb_substr(strip_tags($m['item']),0,12,'utf8'); ?>...</a>
                </span>
            </li>
            <?php endforeach; ?>
            <?php else: ?>
            没有相关的模型数据
            <?php endif; ?>
