<?php
use yii\helpers\Html;
use admin\widgets\AdminNav;
use yii\helpers\Url;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit">
    <title>后台管理--<?= Html::encode($this->title) ?></title>
    <?php $this->head(); ?>
    <?php $this->registerMetaTag(['name'=>'keywords','content'=>'']); ?>
    <?php $this->registerMetaTag(['name'=>'description','content'=>'']); ?>
    <link rel="stylesheet" href="<?php echo $this->theme->baseUrl; ?>/css/pintuer.css">
    <link rel="stylesheet" href="<?php echo $this->theme->baseUrl; ?>/css/admin.css">
    <link rel="stylesheet" href="<?php echo $this->theme->baseUrl; ?>/css/pintuer.add.css">
    <script src="<?php echo $this->theme->baseUrl; ?>/js/jquery.js"></script>
    <script src="<?php echo $this->theme->baseUrl; ?>/js/pintuer.js"></script>
    <script src="<?php echo $this->theme->baseUrl; ?>/js/respond.js"></script>
    <script src="<?php echo $this->theme->baseUrl; ?>/js/admin.js"></script>
    <link type="image/x-icon" href="http://www.pintuer.com/favicon.ico" rel="shortcut icon" />
    <link href="http://www.pintuer.com/favicon.ico" rel="bookmark icon" />
</head>

<body>
    <?php $this->beginBody() ?>
    <div class="lefter">
        <div class="logo"><a href="<?php echo Yii::$app->params['admin']; ?>" target="_blank"><img src="<?php echo $this->theme->baseUrl; ?>/images/logo.png" alt="后台管理系统" /></a></div>
    </div>
    <div class="righter nav-navicon" id="admin-nav">
        <div class="mainer">
            <div class="admin-navbar">
                <span class="float-right">
                    <a class="button button-little bg-main" href="<?php echo Yii::$app->params['www']; ?>" target="_blank">前台首页</a>
                    <a class="button button-little bg-yellow" href="<?php echo Yii::$app->urlManager->createUrl(['default/logout']); ?>">注销登录</a>
                </span>
                <?= AdminNav::widget(); ?>
            </div>
            <div class="admin-bread">
                <span>您好，admin，欢迎您的光临。</span>
                <ul class="bread">
                    <li><a href="javascript:void(0);" class="icon-home"> 开始</a></li>
                    <li>后台首页</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="admin">
        <?= $content ?> 
    </div>

    <script type="text/javascript">
        $(function(){
            //控制显示的菜单
            window.onload = function(){
                var linkUrl =
                    '<?php
                        $linkUrl = '';
                        $controllerID = Yii::$app->controller->id;
                        $actionID = Yii::$app->controller->action->id;
                        if($controllerID != '' && $actionID != ''){
                            $linkUrl = Url::to([$controllerID.'/'.$actionID]);
                        }
                        echo $linkUrl;
                    ?>';
                if(linkUrl != '')
                    $("#admin_nav_list a[href='"+linkUrl+"']").parents('li').addClass('active');
            }
            
        })
    </script>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>