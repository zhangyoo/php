<?php
require_once(dirname(__FILE__).'/../include/config.inc.php');
//初始化参数检测正确性
$cid = empty($cid) ? 48 : intval($cid);
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
                    <p><b>新闻中心</b></p>
                    <p class="nav_left_title_sub">NEWS</p>
                </div>
                <?php require_once('leftnav.php'); ?>
            </div>
            <div class="right_main">
                <div class="right_main_position">
                    当前位置：
                    <a href="<?php echo $cfg_isreurl=='Y'?'index.html':'index.php'; ?>">首页</a> > 
                    <a href="javascript:void(0);">新闻中心</a>
                </div>
                <div class="right_main_content">
                    <ul class="right_main_news">
                        <?php
                            $sql = "SELECT id,classid,title,linkurl,description FROM `#@__infolist` WHERE classid=$cid AND delstate='' AND checkinfo=true ORDER BY orderid DESC";
                            $dopage->GetPage($sql,9);
                            while($row = $dosql->GetArray())
                            {
                                    if($row['linkurl']=='' and $cfg_isreurl!='Y') $gourl = 'newsdetail.php?cid='.$row['classid'].'&id='.$row['id'];
                                    else if($cfg_isreurl=='Y') $gourl = 'newsdetail-'.$row['classid'].'-'.$row['id'].'.html';
                                    else $gourl = $row['linkurl'];
                        ?>
                        <li>
                            <p class="news_list_title"><a href="<?php echo $gourl; ?>"><?php echo $row['title'] ?></a></p>
                            <p>
                                <span class="news_list_zy">摘要：</span>
                                <span class="news_list_description">
                                    <?php echo $row['description'] ?>
                                </span>
                            </p>
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
</body>
</html>