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
    <div class="body_bg"></div>
    <?php require_once('header.php'); ?>
    <?php require_once('morebanner.php'); ?>
    <div class="main">
        <div class="no_index">
            <div class="nav_left">
                <div class="nav_left_title">
                    <p class="nav_left_title_sub">INDUSTRY APPLICATION</p>
                </div>
                <?php require_once('leftnav.php'); ?>
            </div>
            <div class="right_main">
                <div class="right_main_position">
                    Location：
                    <a href="<?php echo $cfg_isreurl=='Y'?'index.html':'index.php'; ?>">Home</a> > 
                    <a href="javascript:void(0);">Solution</a>
                </div>
                <div class="right_main_content">
                    <div class="right_main_content_description">
                        <?php
                        $row = $dosql->GetOne("SELECT description FROM `#@__infoclass` WHERE id=".$cid);
                        echo $row['description'];
                        ?>
                    </div>
                    
                    <div class="common_use_list_box">
                        <h3>Glass application scenarios </h3>
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
                                    <li><img src="../<?php echo $picurl ?>" alt="<?php echo $row['title'] ?>" title="<?php echo $row['title'] ?>" /></li>
                                    <?php
                                        }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="common_use_list_box">
                        <h3>Automotive application scenari </h3>
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
                                    <li><img src="../<?php echo $picurl ?>" alt="<?php echo $row['title'] ?>" title="<?php echo $row['title'] ?>" /></li>
                                    <?php
                                        }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="common_use_list_box">
                        <h3>Ceramic sanitary ware applicat</h3>
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
                                    <li><img src="../<?php echo $picurl ?>" alt="<?php echo $row['title'] ?>" title="<?php echo $row['title'] ?>" /></li>
                                    <?php
                                        }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="common_use_list_box">
                        <h3>Application scenario of chemic</h3>
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
                                    <li><img src="../<?php echo $picurl ?>" alt="<?php echo $row['title'] ?>" title="<?php echo $row['title'] ?>" /></li>
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
    <div class="mv_display_box">
        <div class="close_mv"><a href="javascript:void(0);"><img src="../templates/cn/images/product_01.png"/></a></div>
        <h3>默认标题</h3>
        <div class="mv_source_box" style="background: #D4D4D4">
            <img width="100%" src="../templates/default/images/nofoundpic.gif"/>
        </div>
    </div>
    <script type="text/javascript">
        $(".img-list ul li").click(function(){
            var html = '';
            var $obj = $(this);
            var objSrc = $obj.find("img").attr("src");
            var objTitle = $obj.find("img").attr("title");
            if(objSrc == ""){
                alert("该图片不可用");
                return false;
            }
            html = '<img src="'+objSrc+'"  />';
            $(".mv_display_box h3").text(objTitle);
            $(".mv_source_box").html(html);
            $(".body_bg").show();
            $(".mv_display_box").show();
        })
        $(".close_mv a").click(function(){
            $(".mv_display_box").hide();
            $(".body_bg").hide();
        })
    </script>
</body>
</html>