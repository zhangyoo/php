<div class="sectionTitle-A mb10">
    <h2>欢迎进入文件管理系统</h2>
    <ul>
        <li>管理员：<?php echo CHtml::encode(Yii::app()->user->name); ?></li>
        <li>您上次登录的时间：<?php echo date("Y-m-d H:i:s", $user['login_time']); ?></li>
        <li>您上次登录的IP：<?php echo $user['ip']; ?></li>
    </ul>
</div>
