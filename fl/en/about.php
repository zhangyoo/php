<?php
require_once(dirname(__FILE__).'/../include/config.inc.php');
//初始化参数检测正确性
$cid = empty($cid) ? 49 : intval($cid);
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
                    <p class="nav_left_title_sub">ABOUT ENTERPRISE</p>
                </div>
                <ul>
                    <?php
                        $dosql->Execute("SELECT id,classname,linkurl FROM `#@__infoclass` WHERE parentid=49 AND checkinfo=true ORDER BY orderid");
                        while($row = $dosql->GetArray())
                        {
                            //获取链接地址
                            if($row['linkurl']=='' and $cfg_isreurl!='Y')
                                    $gourl = 'about.php?cid='.$row['id'];
                            else if($cfg_isreurl=='Y')
                                    $gourl = 'about-'.$row['id'].'.html';
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
                    Location：
                    <a href="<?php echo $cfg_isreurl=='Y'?'index.html':'index.php'; ?>">Home</a> > 
                    <a href="<?php echo $cfg_isreurl=='Y'?'about-50.html':'index.php?cid=50'; ?>">About Us</a> > 
                    <?php 
                        $row = $dosql->GetOne("SELECT id,classname,linkurl FROM `#@__infoclass` WHERE id=".$cid);  
                        if($row['linkurl']=='' and $cfg_isreurl!='Y'){
                            $gourl = 'about.php?cid='.$row['id'];
                        }else if($cfg_isreurl=='Y'){
                            $gourl = 'about-'.$row['id'].'.html';
                        }else{
                           $gourl = $row['linkurl']; 
                        }
                        echo '<a href="'.$gourl.'">'.$row['classname'].'</a>';
                    ?>
                </div>
                <div class="right_main_content">
                    <?php echo Info($cid); ?>
                </div>
            </div>
        </div>
    </div>
    <?php require_once('footer.php'); ?>
</body>
</html>