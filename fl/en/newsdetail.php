<?php
require_once(dirname(__FILE__).'/../include/config.inc.php');
//初始化参数检测正确性
$cid = empty($cid) ? 48 : intval($cid);
$id  = empty($id)  ? 0 : intval($id);
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=9"> 
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<?php echo GetHeader(1,$cid,$id); ?>
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
                    <p class="nav_left_title_sub">NEWS</p>
                </div>
                <?php require_once('leftnav.php'); ?>
            </div>
            <div class="right_main">
                <div class="right_main_position">
                    Location：
                    <a href="<?php echo $cfg_isreurl=='Y'?'index.html':'index.php'; ?>">Home</a> > 
                    <a href="<?php echo $cfg_isreurl=='Y'?'news-48-1.html':'news.php'; ?>">News</a> > 
                    <?php
                    //检测文档正确性
                    $r = $dosql->GetOne("SELECT title,content FROM `#@__infolist` WHERE id=$id");
                    if(@$r)
                    {
                    //增加一次点击量
                    $dosql->ExecNoneQuery("UPDATE `#@__infoimg` SET hits=hits+1 WHERE id=$id");
                    $row = $dosql->GetOne("SELECT title,content FROM `#@__infolist` WHERE id=$id");
                    ?>
                    <a href="javascript:void(0);"><?php echo $row['title']; ?></a>
                </div>
                <div class="right_main_content">
                     <?php echo $row['content']; ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <?php require_once('footer.php'); ?>
</body>
</html>