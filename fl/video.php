<?php
require_once(dirname(__FILE__).'/include/config.inc.php');
//初始化参数检测正确性
$cid = empty($cid) ? 7 : intval($cid);
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=9"> 
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<?php echo GetHeader(1,$cid); ?>
<link rel="stylesheet" type="text/css" href="templates/cn/css/style.css">
<script type="text/javascript" src="templates/cn/js/jquery.js"></script>
<script type="text/javascript" src="templates/cn/js/ext.js"></script>
</head>
<body>
    <div class="body_bg"></div>
    <?php require_once('header.php'); ?>
    <?php require_once('morebanner.php'); ?>
    <div class="main">
        <div class="no_index">
            <div class="nav_left">
                <div class="nav_left_title">
                    <p><b>产品视频</b></p>
                    <p class="nav_left_title_sub">PRODUCT VIDEO</p>
                </div>
                <ul>
                    <?php
                        $dosql->Execute("SELECT id,classname,linkurl FROM `#@__infoclass` WHERE parentid=7 AND checkinfo=true ORDER BY orderid");
                        while($row = $dosql->GetArray())
                        {
                            //获取链接地址
                            if($row['linkurl']=='' and $cfg_isreurl!='Y')
                                    $gourl = 'video.php?cid='.$row['id'];
                            else if($cfg_isreurl=='Y')
                                    $gourl = 'video-'.$row['id'].'-1.html';
                            else
                                    $gourl = $row['linkurl'];
                    ?>
                    <li class="<?php echo $cid == $row['id'] ? 'pro_li_hover':'';?>"><a href="<?php echo $gourl; ?>"><?php echo $row['classname']; ?></a></li>
                    <?php
                        }
                    ?>
                </ul>
            </div>
            <div class="right_main">
                <div class="right_main_position">
                    当前位置：
                    <a href="<?php echo $cfg_isreurl=='Y'?'index.html':'index.php'; ?>">首页</a> > 
                    <a href="<?php echo $cfg_isreurl=='Y'?'video-7-1.html':'video.php'; ?>">产品视频</a> > 
                    <?php if($cid == 1){ ?>
                    <a href="javascript:void(0);">所有视频</a>
                    <?php }else{ 
                           $row = $dosql->GetOne("SELECT id,classname,linkurl FROM `#@__infoclass` WHERE id=".$cid);  
                           if($row['linkurl']=='' and $cfg_isreurl!='Y'){
                               $gourl = 'video.php?cid='.$row['id'];
                           }else if($cfg_isreurl=='Y'){
                               $gourl = 'video-'.$row['id'].'-1.html';
                           }else{
                              $gourl = $row['linkurl']; 
                           }
                           echo '<a href="'.$gourl.'">'.$row['classname'].'</a> > <a href="javascript:void(0);">所有视频</a>';    
                           
                        }
                    ?>
                </div>
                <div class="right_main_content">
                    <div class="right_main_content_description">
                        <?php
                        $row = $dosql->GetOne("SELECT description FROM `#@__infoclass` WHERE id=".$cid);
                        echo $row['description'];
                        ?>
                    </div>
                    <ul class="vedio_list_ul">
                        <?php
                            if(!empty($keyword))
                            {
                                    $keyword = htmlspecialchars($keyword);

                                    $sql = "SELECT id,classid,picurl,title,linkurl,video FROM `#@__infoimg` WHERE (classid=$cid OR parentstr LIKE '%,$cid,%') AND title LIKE '%$keyword%' AND delstate='' AND checkinfo=true ORDER BY orderid DESC";
                            }
                            else
                            {
                                    $sql = "SELECT id,classid,picurl,title,linkurl,video FROM `#@__infoimg` WHERE (classid=$cid OR parentstr LIKE '%,$cid,%') AND delstate='' AND checkinfo=true ORDER BY orderid DESC";
                            }

                            $dopage->GetPage($sql,9);
                            while($row = $dosql->GetArray())
                            {
                                    if($row['picurl'] != '') $picurl = $row['picurl'];
                                    else $picurl = 'templates/default/images/nofoundpic.gif';
                        ?>
                        <li>
                            <a class="vedio_list_li_img" href="javascript:void(0);"><image src="<?php echo $picurl; ?>" alt="<?php echo $row['title']; ?>" title="<?php echo $row['title']; ?>"/></a>
                            <p class="vedio_list_li_p">
                                <span class="vedio_list_li_left"><?php echo $row['title']; ?></span>
                                <span class="vedio_list_li_right"><a href="tencent://message/?uin=<?php echo $cfg_qqcode; ?>&Site=费兰官网&Menu=yes">在线咨询&nbsp;&nbsp;&nbsp;></a></span>
                            </p>
                            <div class="video_hover_box">
                                <div class="video_hover_box_bg"></div>
                                <div class="video_hover_box_icon" av="<?php echo $row['video']; ?>" title="<?php echo $row['title']; ?>"><img src="templates/cn/images/fla_hover.png"/></div>
                            </div>
                        </li>
                        <?php
                            }
                        ?>
                    </ul>
                </div>
                <div class="page_list">
                    <?php echo $dopage->GetList(); ?> 
                </div>
            </div>
        </div>
    </div>
    <?php require_once('footer.php'); ?>
    <div class="mv_display_box">
        <div class="close_mv"><a href="javascript:void(0);"><img src="templates/cn/images/product_01.png"/></a></div>
        <h3>默认标题</h3>
        <div class="mv_source_box">
            <video id="video_mv" autoplay="autoplay">
                <source src="" type="video/mp4" />
                Your browser does not support HTML5 video.
            </video>
        </div>
        <div class="talk_online_inherit">
            <span class="talk_online_tip">如果您有任何需要可在线联系我们或直接拨打免费热线：158-0090-2006&nbsp;&nbsp;我们将立即给您答复！</span>
            <span class="talk_online_icon"><a href="tencent://message/?uin=<?php echo $cfg_qqcode; ?>&Site=费兰官网&Menu=yes"><img src="templates/cn/images/product_03.png"/></a></span>
        </div>
    </div>
    <script type="text/javascript">
        $(".video_hover_box_icon").click(function(){
            var html = '';
            var $obj = $(this);
            var objSrc = $obj.attr("av");
            var objTitle = $obj.attr("title");
            if(objSrc == ""){
                alert("没有找到可用的视频资源");
                return false;
            }
            html = '<video id="video_mv" autoplay="autoplay">'
                 + '<source src="'+objSrc+'" type="video/mp4" />'
                 + 'Your browser does not support HTML5 video.'
                 + '</video>';
            $(".mv_display_box h3").text(objTitle);
            $(".mv_source_box").html(html);
            $(".body_bg").show();
            $(".mv_display_box").show();
        })
        $(".close_mv a").click(function(){
            $(".mv_source_box video").remove();
            $(".mv_display_box").hide();
            $(".body_bg").hide();
        })
    </script>
</body>
</html>