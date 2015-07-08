<?php
use yii\helpers\Html;
use yii\helpers\Url;
use apps\widgets\AppsNav;
use apps\widgets\Message;
/* @var $this \yii\web\View */
/* @var $content string */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="<?= Yii::$app->language ?>"> <!--<![endif]-->

<head>
    <meta name="viewport" content="width=100%; initial-scale=1; maximum-scale=1; minimum-scale=1; user-scalable=no;" />
    <link rel="icon" href="<?php echo $this->theme->baseUrl; ?>/images/favicon.png" type="image/png" />
    <link rel="shortcut icon" href="<?php echo $this->theme->baseUrl; ?>/images/favicon.png" type="image/png" />
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head(); ?>
    <?php $this->registerMetaTag(['name'=>'keywords','content'=>'']); ?>
    <?php $this->registerMetaTag(['name'=>'description','content'=>'']); ?>
    <link href="<?php echo $this->theme->baseUrl; ?>/css/bootstrap.css" type="text/css" rel="stylesheet" />
    <link href="<?php echo $this->theme->baseUrl; ?>/css/style.css" type="text/css" rel="stylesheet" />
    <link href="<?php echo $this->theme->baseUrl; ?>/css/prettyPhoto.css" type="text/css" rel="stylesheet" />
    <script type="text/javascript" src="<?php echo $this->theme->baseUrl; ?>/js/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->theme->baseUrl; ?>/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->theme->baseUrl; ?>/js/jquery.easing.1.3.js"></script>
    <script type="text/javascript" src="<?php echo $this->theme->baseUrl; ?>/js/jquery.quicksand.js"></script>
    <script type="text/javascript" src="<?php echo $this->theme->baseUrl; ?>/js/superfish.js"></script>
    <script type="text/javascript" src="<?php echo $this->theme->baseUrl; ?>/js/hoverIntent.js"></script>
    <script type="text/javascript" src="<?php echo $this->theme->baseUrl; ?>/js/jquery.hoverdir.js"></script>
    <script type="text/javascript" src="<?php echo $this->theme->baseUrl; ?>/js/jquery.flexslider.js"></script>
    <script type="text/javascript" src="<?php echo $this->theme->baseUrl; ?>/js/jflickrfeed.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->theme->baseUrl; ?>/js/jquery.prettyPhoto.js"></script>
    <script type="text/javascript" src="<?php echo $this->theme->baseUrl; ?>/js/jquery.elastislide.js"></script>
    <script type="text/javascript" src="<?php echo $this->theme->baseUrl; ?>/js/jquery.tweet.js"></script>
    <script type="text/javascript" src="<?php echo $this->theme->baseUrl; ?>/js/smoothscroll.js"></script>
    <script type="text/javascript" src="<?php echo $this->theme->baseUrl; ?>/js/jquery.ui.totop.js"></script>
    <script type="text/javascript" src="<?php echo $this->theme->baseUrl; ?>/js/main.js"></script>
    <script type="text/javascript" src="<?php echo $this->theme->baseUrl; ?>/js/ajax-mail.js"></script>
    <script type="text/javascript" src="<?php echo $this->theme->baseUrl; ?>/js/accordion.settings.js"></script>
    <!--[if lt IE 9]>
    <script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <link href="<?php echo $this->theme->baseUrl; ?>/css/ie.css" type="text/css" rel="stylesheet"/>
    <![endif]-->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>

<body>
<?php $this->beginBody() ?>
<!-- top menu -->
<section id="top-menu">
    <div class="container">
        <div class="row">
            <div class="span6 hidden-phone">
                <ul class="top-menu">
                    <li><a href="./index.html">Home</a></li>
                    <li><a href="#">Sitemap</a></li>
                    <li><a href="#">Shorcodes</a></li>
                    <li><a href="#" class="last">Contact</a></li>
                </ul>
            </div>
            <div class="span6">
                <ul class="top-social">
                    <li><a href="#" class="twitter2"></a></li>
                    <li><a href="#" class="facebook"></a></li>
                    <li><a href="#" class="google last"></a></li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- header -->
<header id="header">
    <div class="container">
        <div class="row">
            <div class="span4 logo">
                <a href="./index.html"><img src="<?php echo $this->theme->baseUrl; ?>/images/logo.png" alt="logo" /></a>
            </div>
            <div class="span8 hidden-phone">
                <a href="./index.html" class="alignright banner">
                    <img src="<?php echo $this->theme->baseUrl; ?>/images/banner-468-60.gif" alt="" />
                </a>
            </div>
        </div>
        <?= AppsNav::widget(); ?>
    </div>
</header>

<!-- container -->
<section id="container">
    <div class="container">
        <?= $content ?>
    </div>
</section>

<!--footer-->
<footer id="footer">
    <div class="container">
    <div class="row">
        <div class="span4">
            <h3>Flickr Photos</h3>
            <ul class="flickr clearfix"></ul>
        </div>
        <div class="span4">
            <h3>Last Tweet</h3>
            <div class="twitter"></div>
            <script type="text/javascript">
                $(document).ready(function(){
                    //twitter
                    $(".twitter").tweet({
                        join_text: "auto",
                        username: "twitter",
                        avatar_size: 40,
                        count: 3,
                        auto_join_text_default: "we said,",
                        auto_join_text_ed: "we",
                        auto_join_text_ing: "we were",
                        auto_join_text_reply: "we replied",
                        auto_join_text_url: "we were checking out",
                        loading_text: "loading tweets..."
                    });
                });
            </script>
        </div>
        <?= Message::widget(); ?>
    </div>
    </div>
</footer>

<!--footer menu-->
<section id="footer-menu">
    <div class="container">
        <div class="row">
            <p class="span12"><span>&copy; Copyright 2012, <span class="color2">CITYpress</span> | All Rights Reserved</span></p>
        </div>
    </div>
</section>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
