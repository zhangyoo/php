<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo CHtml::encode($this->pageTitle); ?></title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap-responsive.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/base.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/list.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/article.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery-1.7.1.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/use.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/common.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/common/popup/default.css" media="screen, projection" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/common/popup/asyncbox.js"></script>
</head>
<body class="sectionContainer-A1"> 
<div class="container-fluid">
    <div class="sectionHeader-A1 p-rel" style="border-bottom: 3px solid #3b5999">
        <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/logo.gif" style="margin: 6px 0 0 5px" alt="" class="sectionLogo-A1 sectionLogo-A1-1"/>
        <div class="sectionNav-A1 sectionFloat-A1" style="margin-left: 35px">
            <!-- 顶部导航 -->
            <?php
                if(Yii::app()->session['topNav']=='admin'){
                    $topNav = 'admin';
                }else{
                    $topNav=Yii::app()->session['topNav'];
                }
                $this->renderPartial('/layouts/top_nav',array('topNav'=>$topNav));
            ?>
            <!-- //顶部导航 -->
		</div>
		<div class="sectionNav-A2">
			<ul class="clear">
				<li class="linkBlue">
					<img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/user_edit.png" class="mr10" align="absmiddle">
					<strong class="welcome mr10">欢迎，</strong>
					<strong class="mr10"><?php echo CHtml::encode(Yii::app()->user->name); ?></strong>
					<a href="/user/updatePassword/id/<?php echo CHtml::encode(Yii::app()->user->id); ?>" class="mr10">修改密码</a>
                    <?php echo CHtml::link('退出登陆','/default/logout',array('class'=>'mr10'));?>
				</li>
			</ul>
		</div>
		
	</div>
	
	<div class="sectionWrap-A4 container-fluid">
		<div class="row-fluid">
			<div class="sectionWrap-A2 span1">
			  <div class="p10">
				<div class="sectionMenu-A1">
                    <!--左边菜单选项卡-->
                    <?php 
                        $c = Yii::app()->getController()->id;
//                        $other = array('color','material','style');
                        if(!empty($c)){
                            $left = $c;
//                            if(in_array($c,$other)) $left = "admin_other";
                            $leftArray=Yii::app()->session['leftArray'];
                            $this->renderPartial('/layouts/all_left',array('left'=>$left,'leftArray'=>$leftArray));
                        }
                    ?>
                    <!--渲染内容部分-->
				</div>
			  </div>
			</div>
			<div class="sectionWrap-A3 span11">
				<?php echo $content;?>
			</div>
		</div>
	</div>
	
	
</div>

</body>
</html>
