<?php
//获取顶部导航
$data=$this->navlist($tempArray=array('admin_top',$topNav));
$nav=$data['nav'];
$controlName=$data['controlName'];
?>
<ul class="clear">
    <li class="<?php if(Yii::app()->getController()->id =='default'){ echo 'cur';} else echo ''; ?>"><a href="/default/index">首页</a></li>
    <?php if(!empty($nav)):?>
        <?php foreach ($nav as $url=>$name) :?>
    <li class="<?php if(Yii::app()->getController()->id==$controlName[$url]){ echo 'cur'; }else{ echo ''; } ?>"><a href="<?php echo $url;?>"><?php echo $name;?></a></li>
        <?php endforeach;?>
    <?php endif;?>
</ul>