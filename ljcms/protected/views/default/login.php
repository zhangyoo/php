<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>管理登录</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap-responsive.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/base.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery-1.7.1.js"></script>
</head>
<body class="sectionContainer-A1 transitional-wide"> 
<div class="container-fluid">
	<div class="sectionWrap-A1">
		<div class="border p10 mb10">
			<div class="page-w clear">
				<div class="L">
					<img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/logo.gif" width="145" height="85" alt="" class="sectionLogo-A1 block"/>
				</div>
				<div class="L">
					<div class="sectionForm-A1 sectionForm-A1-1">
                        <?php $form=$this->beginWidget('CActiveForm', array(
                            'id'=>'login-form',
                            'htmlOptions'=>array('class'=>"form-horizontal"),
                            'enableClientValidation'=>true,
                            'clientOptions'=>array(
                                'validateOnSubmit'=>true,
                            ),
                        )); ?>
							<div class="control-group">
								<label class="control-label" for="inputEmail">用户名</label>
								<div class="controls">
                                    <input type="hidden" value="<?php echo $callback?>" name="callback" />
                                    <?php echo $form->textField($model,'account',array('id'=>'inputEmail','placeholder'=>'用户名')); ?>
                                    <?php echo $form->error($model,'account'); ?>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="inputPassword">密&nbsp;&nbsp;码</label>
								<div class="controls">
                                    <?php echo $form->passwordField($model,'password',array('id'=>'inputPassword','placeholder'=>'密码')); ?>
                                    <?php echo $form->error($model,'password'); ?>
                                </div>
							</div>
							<div class="control-group">
								<label class="control-label" for="inputYzm">验证码</label>
								<div class="controls yzm">
                                    <?php echo $form->textField($model,'verifyCode',array('id'=>'inputYzm','class'=>'text L mr10')); ?>
                                  <?php $this->widget ( 'CCaptcha', array (
                                      'showRefreshButton' => true, 'clickableImage' => true, 
                                      'buttonType' => 'link', 'buttonLabel' => '换一张', 
                                      'imageOptions' => array ('alt' => '点击换图', 'align'=>'absmiddle','style'=>'cursor:pointer'  ) 
                                      ) );
                                  ?>
								</div>
							</div>
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo $form->checkBox($model,'rememberMe',array('class'=>'btn')); ?>
                                    <?php echo $form->label($model,'rememberMe',array('class'=>'btn')); ?>
								</div>
							</div>
							<div class="control-group">
                                <div class="controls">
                                    <?php echo CHtml::submitButton('登陆',array('class'=>'btn')); ?>
                                    <?php echo CHtml::resetButton('重填',array('class'=>'btn')); ?>
                                    
								</div>
							</div>
						<?php $this->endWidget(); ?>
					</div>
				</div>
			</div>
		</div>
		<div class="page-w sectionFoot-A1">
			<p>COPYRIGHT© <a href="###">GEZLIFE.COM</a>. ALL THRUSTS RESERVED.</p>
		</div>
	</div>
</div>
<script type="text/javascript">
        /*<![CDATA[*/
        jQuery(function($) {
            jQuery(document).on('click', '#yw0', function(){
                jQuery.ajax({
                    url: "\/default\/captcha?refresh=1",
                    dataType: 'json',
                    cache: false,
                    success: function(data) {
                        jQuery('#yw0').attr('src', data['url']);
                        jQuery('body').data('captcha.hash', [data['hash1'], data['hash2']]);
                    }
                });
                return false;
            });
        });
        /*]]>*/
    </script>
</body>
</html>
