<?php 
    $colorLetter = '';
    if(isset($colors)){
        $colorLetter = '0_';
        if(!empty($texture['texture']) && !empty($texture['texture']['color_value'])){
            $colorLetter = $colors[$texture['texture']['color_value']].'_';
        } 
    }
?>
<?php if($type == 1)://1代表是字段上传单张图片 ?>
    <?php if(!empty($texture['texture']) && !empty($texture['texture'][$column])): ?>
    <p class="<?php echo $column == 'floorplan' ? "tex_img_list_floorplan":"tex_img_list"; ?>">
        <?php $form = substr($texture['texture'][$column], strrpos($texture['texture'][$column], ".")+1, strlen($texture['texture'][$column]) - strrpos($texture['texture'][$column], ".")); $newForm = strtoupper($form);?>
        <img src="<?php echo Yii::app()->params['static'].$texture['texture'][$column]; ?>" alt="<?php echo $texture['texture']['name']; ?>" title="<?php echo $texture['texture']['name']; ?>" />
        <span><?php echo $texture['texture']['name'].'_'.$colorLetter.$texture['texture']['length'].'-'.$texture['texture']['width'].'-'.$texture['texture']['height'].'_'.
                $reference['is_alpha'][$texture['texture']['alpha']].'_'.$reference['type'][$column].'_'.$texture['texture']['maker'].'.'.$newForm; ?></span>
    </p>
    <?php else: ?>
    无文件上传
    <?php endif; ?>
<?php else: ?>
    <?php if(!empty($texture['texture'])): ?>
    <?php $texImgs = json_decode($texture['texture'][$column],true); ?>
    <?php if(!empty($texImgs)): ?>
    <?php ksort($texImgs); ?>
    <?php foreach ($texImgs as $kti=>$vti): ?>
    <?php $form = substr($vti, strrpos($vti, ".")+1, strlen($vti) - strrpos($vti, ".")); $newForm = strtoupper($form);?>
    <p class="tex_img_list">
        <img src="<?php echo Yii::app()->params['static'].$vti; ?>" alt="<?php echo $texture['texture']['name']; ?>" title="<?php echo $texture['texture']['name']; ?>" />
        <span><?php echo $texture['texture']['name'].'_'.$colorLetter.$texture['texture']['length'].'-'.$texture['texture']['width'].'-'.$texture['texture']['height'].'_'.
                $reference['is_alpha'][$texture['texture']['alpha']].'_'.$reference['type'][$column].'-'.$kti.'_'.$texture['texture']['maker'].'.'.$newForm; ?></span>
        <?php if(!empty(Yii::app()->session['delete'])): ?>
        <a href="javascript:void(0);" tid="<?php echo $texture['texture']['id']; ?>" imgKey="<?php echo $kti; ?>" column="<?php echo $column; ?>" onclick="delTexImg(this)">删除</a>
        <?php endif; ?>
    </p>
    <?php endforeach; ?>
    <?php else: ?>
    无文件上传
    <?php endif; ?>
    <?php else: ?>
    无文件上传
    <?php endif; ?>
<?php endif; ?>