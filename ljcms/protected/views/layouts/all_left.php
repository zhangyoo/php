<?php
//获取左侧导航
$leftNav=$this->navlist($tempArray=array('admin_left',$left,$leftArray));
?>
<ul>
    <?php if(!empty($leftNav)) :?>
        <?php foreach ($leftNav as $url=>$name) :?>
            <li class="mb5 <?php echo Yii::app()->request->url==$url ? 'cur':''; ?>"><a href="<?php echo $url;?>"><?php echo $name;?></a></li>
        <?php endforeach;?>
    <?php endif;?>
</ul>
