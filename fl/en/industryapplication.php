<?php
require_once(dirname(__FILE__).'/../include/config.inc.php');
//初始化参数检测正确性
$cid = empty($cid) ? 43 : intval($cid);
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=9"> 
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<?php echo GetHeader(1,$cid); ?>
<link rel="stylesheet" type="text/css" href="../templates/en/css/style.css">
<script type="text/javascript" src="../templates/en/js/jquery.js"></script>
<script type="text/javascript" src="../templates/en/js/ext.js"></script>
</head>
<body>
    <?php require_once('header.php'); ?>
    <?php require_once('morebanner.php'); ?>
    <div class="main">
        <div class="no_index">
            <div class="nav_left">
                <div class="nav_left_title">
                    <p><b>行业应用</b></p>
                    <p class="nav_left_title_sub">INDUSTRY APPLICATION</p>
                </div>
                <?php require_once('leftnav.php'); ?>
            </div>
            <div class="right_main">
                <div class="right_main_position">
                    当前位置：
                    <a href="<?php echo $cfg_isreurl=='Y'?'index.html':'index.php'; ?>">首页</a> > 
                    <a href="javascript:void(0);">行业应用</a>
                </div>
                <div class="right_main_content">
                    <div class="right_main_content_description">
                        <?php
                        $row = $dosql->GetOne("SELECT description FROM `#@__infoclass` WHERE id=".$cid);
                        echo $row['description'];
                        ?>
                    </div>
                    
                    <div class="common_use_list_box">
                        <h3>玻璃类应用场景</h3>
                        <div class="img-scroll">
                            <span class="prev">prev</span>
                            <span class="next">next</span>
                            <div class="img-list">
                                <ul>
                                    <?php
                                        $sql = "SELECT picurl,title FROM `#@__infoimg` WHERE classid=44 AND delstate='' AND checkinfo=true ORDER BY orderid DESC";
                                        $dosql->Execute($sql);
                                        while($row = $dosql->GetArray())
                                        {
                                                if($row['picurl'] != '') $picurl = $row['picurl'];
                                                else $picurl = 'templates/default/images/nofoundpic.gif';
                                    ?>
                                    <li><img src="<?php echo $picurl ?>" alt="<?php echo $row['title'] ?>" title="<?php echo $row['title'] ?>" /></li>
                                    <?php
                                        }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="common_use_list_box">
                        <h3>汽车类应用场景</h3>
                        <div class="img-scroll">
                            <span class="prev">prev</span>
                            <span class="next">next</span>
                            <div class="img-list">
                                <ul>
                                    <?php
                                        $sql = "SELECT picurl,title FROM `#@__infoimg` WHERE classid=45 AND delstate='' AND checkinfo=true ORDER BY orderid DESC";
                                        $dosql->Execute($sql);
                                        while($row = $dosql->GetArray())
                                        {
                                                if($row['picurl'] != '') $picurl = $row['picurl'];
                                                else $picurl = 'templates/default/images/nofoundpic.gif';
                                    ?>
                                    <li><img src="<?php echo $picurl ?>" alt="<?php echo $row['title'] ?>" title="<?php echo $row['title'] ?>" /></li>
                                    <?php
                                        }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="common_use_list_box">
                        <h3>陶瓷卫浴类应用场景</h3>
                        <div class="img-scroll">
                            <span class="prev">prev</span>
                            <span class="next">next</span>
                            <div class="img-list">
                                <ul>
                                    <?php
                                        $sql = "SELECT picurl,title FROM `#@__infoimg` WHERE classid=46 AND delstate='' AND checkinfo=true ORDER BY orderid DESC";
                                        $dosql->Execute($sql);
                                        while($row = $dosql->GetArray())
                                        {
                                                if($row['picurl'] != '') $picurl = $row['picurl'];
                                                else $picurl = 'templates/default/images/nofoundpic.gif';
                                    ?>
                                    <li><img src="<?php echo $picurl ?>" alt="<?php echo $row['title'] ?>" title="<?php echo $row['title'] ?>" /></li>
                                    <?php
                                        }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="common_use_list_box">
                        <h3>化工类应用场景</h3>
                        <div class="img-scroll">
                            <span class="prev">prev</span>
                            <span class="next">next</span>
                            <div class="img-list">
                                <ul>
                                    <?php
                                        $sql = "SELECT picurl,title FROM `#@__infoimg` WHERE classid=47 AND delstate='' AND checkinfo=true ORDER BY orderid DESC";
                                        $dosql->Execute($sql);
                                        while($row = $dosql->GetArray())
                                        {
                                                if($row['picurl'] != '') $picurl = $row['picurl'];
                                                else $picurl = 'templates/default/images/nofoundpic.gif';
                                    ?>
                                    <li><img src="<?php echo $picurl ?>" alt="<?php echo $row['title'] ?>" title="<?php echo $row['title'] ?>" /></li>
                                    <?php
                                        }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <script type="text/javascript">
                    var wraper = $(".img-scroll");
                    var prev = $(".prev");
                    var next = $(".next");
                    var s = 3;
                    var or = false;
                    //点击下一个按钮
                    next.click(function(){
                        var img = $(this).parent().find("ul");
                        var w = img.find('li').outerWidth(true);
                        img.animate(
                           {'margin-left':-w},
                           function(){
                               img.find('li').eq(0).appendTo(img);
                               img.css({'margin-left':0});
                           });
                   });
                   //点击上一个按钮       
                   prev.click(function(){
                       var img = $(this).parent().find("ul");
                       var w = img.find('li').outerWidth(true);
                       img.find('li:last').prependTo(img);
                       img.css({'margin-left':-w});
                       img.animate({'margin-left':0});
                   });
                   if (or == true)
                   {
                    ad = setInterval(function() { $(".next").click();},s*1000);
                    wraper.hover(function(){clearInterval(ad);},function(){ad = setInterval(function() { next.click();},s*1000);});

                   }
               </script>
            </div>
        </div>
    </div>
    <?php require_once('footer.php'); ?>
</body>
</html>