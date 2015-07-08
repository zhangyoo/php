<?php
use yii\helpers\Url;
?>
<ul class="nav nav-inline admin-nav" id="admin_nav_list">
    <li>
        <a href="<?php echo Url::to(['index/index']); ?>" class="icon-home"> 开始</a>
        <ul>
            <li><a href="<?php echo Url::to(['index/index']); ?>">登陆信息</a></li>
        </ul>
    </li>
    <li>
        <a href="<?php echo Url::to(['system/index']); ?>" class="icon-cog"> 系统</a>
        <ul>
            <li><a href="<?php echo Url::to(['system/index']); ?>">系统设置</a></li>
            <li><a href="<?php echo Url::to(['user/index']); ?>">用户管理</a></li>
        </ul>
    </li>
    <li>
        <a href="<?php echo Url::to(['article/index']); ?>" class="icon-file-text"> 内容</a>
        <ul>
            <li><a href="<?php echo Url::to(['tag/index']); ?>">标签管理</a></li>
            <li><a href="<?php echo Url::to(['category/index']); ?>">栏目管理</a></li>
            <li><a href="<?php echo Url::to(['article/index']); ?>">文章管理</a></li>
            <li><a href="<?php echo Url::to(['comment/index']); ?>">评论管理</a></li>
            <li><a href="<?php echo Url::to(['reply/index']); ?>">留言管理</a></li>
            <li><a href="<?php echo Url::to(['flink/index']); ?>">友情链接</a></li>
        </ul>
    </li>
<!--    <li><a href="#" class="icon-shopping-cart"> 订单</a></li>
    <li><a href="#" class="icon-user"> 会员</a></li>
    <li><a href="#" class="icon-file"> 文件</a></li>
    <li><a href="#" class="icon-th-list"> 栏目</a></li>-->
</ul>
