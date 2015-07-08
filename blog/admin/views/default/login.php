<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit">
    <title>后台管理--<?= Html::encode($this->title) ?></title>
    <link rel="stylesheet" href="<?php echo $this->theme->baseUrl; ?>/css/pintuer.css">
    <link rel="stylesheet" href="<?php echo $this->theme->baseUrl; ?>/css/admin.css">
    <script src="<?php echo $this->theme->baseUrl; ?>/js/jquery.js"></script>
    <script src="<?php echo $this->theme->baseUrl; ?>/js/pintuer.js"></script>
    <script src="<?php echo $this->theme->baseUrl; ?>/js/respond.js"></script>
    <script src="<?php echo $this->theme->baseUrl; ?>/js/admin.js"></script>
    <link type="image/x-icon" href="http://www.pintuer.com/favicon.ico" rel="shortcut icon" />
    <link href="http://www.pintuer.com/favicon.ico" rel="bookmark icon" />
</head>

<body>
<?php $this->beginBody() ?>
<div class="container">
    <div class="line">
        <div class="xs6 xm4 xs3-move xm4-move">
            <br /><br />
            <div class="media media-y">
                <a href="<?php echo Yii::$app->params['www']; ?>" target="_blank"><img src="<?php echo $this->theme->baseUrl; ?>/images/logo.png" class="radius" alt="后台管理系统" /></a>
            </div>
            <br /><br />
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
            <div class="panel">
                <div class="panel-head"><strong>登录后台管理系统</strong></div>
                <input type="hidden" name="returnUrl" value="<?php echo $returnUrl; ?>" />
                <div class="panel-body" style="padding:30px;">
                    <div class="form-group">
                        <div class="field field-icon-right">
                            <input type="text" class="input" name="LoginForm[username]" placeholder="登录账号" data-validate="required:请填写账号" />
                            <span class="icon icon-user"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="field field-icon-right">
                            <input type="password" class="input" name="LoginForm[password]" placeholder="登录密码" data-validate="required:请填写密码" />
                            <span class="icon icon-key"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="field">
                            <input type="text" class="input" name="LoginForm[verifyCode]" placeholder="填写右侧的验证码" data-validate="required:请填写右侧的验证码" />
                            <?= $form->field($model, 'verifyCode', [
                                    'options' => ['class' => ''],
                            ])->widget(Captcha::className(),[
                                   'template' => '{image}',
                                   'imageOptions' => ['alt' => '验证码','title'=>'点击换图', 'style'=>'cursor:pointer','class'=>'passcode'],
                            ])->label(false); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="field field-icon-right">
                            <?= $form->field($model, 'rememberMe')->checkbox() ?>
                        </div>
                    </div>
                </div>
                <div class="panel-foot text-center">
                    <?= Html::submitButton('立即登录后台', ['class' => 'button button-block bg-main text-big', 'name' => 'login-button']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
            <div class="text-right text-small text-gray padding-top">基于<a class="text-gray" target="_blank" href="<?php echo Yii::$app->params['www']; ?>">拼图前端框架</a>构建</div>
        </div>
    </div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
