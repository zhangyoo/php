<?php
require_once(dirname(__FILE__).'/include/config.inc.php');
//初始化参数检测正确性
$cid = empty($cid) ? 1 : intval($cid);
$id  = empty($id)  ? 0 : intval($id);
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=9"> 
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<?php echo GetHeader(1,$cid,$id); ?>
<link rel="stylesheet" type="text/css" href="templates/cn/css/style.css">
<script type="text/javascript" src="templates/cn/js/jquery.js"></script>
<script type="text/javascript" src="templates/cn/js/ext.js"></script>
</head>
<body>
    <?php require_once('header.php'); ?>
    <?php require_once('morebanner.php'); ?>
    <div class="main">
        <div class="no_index">
            <div class="nav_left">
                <div class="nav_left_title">
                    <p><b>产品中心</b></p>
                    <p class="nav_left_title_sub">PRODUCT CENTER</p>
                </div>
                <ul>
                    <?php
                        $dosql->Execute("SELECT id,classname,linkurl FROM `#@__infoclass` WHERE parentid=1 AND checkinfo=true ORDER BY orderid");
                        while($row = $dosql->GetArray())
                        {
                            //获取链接地址
                            if($row['linkurl']=='' and $cfg_isreurl!='Y')
                                    $gourl = 'product.php?cid='.$row['id'];
                            else if($cfg_isreurl=='Y')
                                    $gourl = 'product-'.$row['id'].'.html';
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
                    <a href="<?php echo $cfg_isreurl=='Y'?'product.html':'product.php'; ?>">产品中心</a> > 
                    <?php if($cid!= 1){
                           $row = $dosql->GetOne("SELECT id,classname,linkurl FROM `#@__infoclass` WHERE id=".$cid);  
                           if($row['linkurl']=='' and $cfg_isreurl!='Y'){
                               $gourl = 'product.php?cid='.$row['id'];
                           }else if($cfg_isreurl=='Y'){
                               $gourl = 'product-'.$row['id'].'-1.html';
                           }else{
                              $gourl = $row['linkurl']; 
                           }
                           echo '<a href="'.$gourl.'">'.$row['classname'].'</a> > ';    
                           
                        }
                    ?>
                    <?php
                    //检测文档正确性
                    $r = $dosql->GetOne("SELECT title,content FROM `#@__infoimg` WHERE id=$id");
                    if(@$r)
                    {
                    //增加一次点击量
                    $dosql->ExecNoneQuery("UPDATE `#@__infoimg` SET hits=hits+1 WHERE id=$id");
                    $row = $dosql->GetOne("SELECT title,content FROM `#@__infoimg` WHERE id=$id");
                    ?>
                    <a href="javascript:void(0);"><?php echo $row['title']; ?></a>
                </div>
                <div class="right_main_content">
                    <?php echo $row['content']; ?>
                    <?php } ?>
                </div>
                <div class="talk_online">
                    <span class="talk_online_tip">如果您有任何需要可在线联系我们或直接拨打免费热线：158-0090-2006&nbsp;&nbsp;我们将立即给您答复！</span>
                    <span class="talk_online_icon"><a href="javascript:void(0);"><img src="templates/cn/images/product_03.png"/></a></span>
                </div>
                <div class="product_more_list right_main_content">
                    <div class="product_more_list_title"><b>更多产品推荐</b></div>
                    <ul class="product_list_ul">
                        <?php
                            $sql = "SELECT id,classid,picurl,title,linkurl FROM `#@__infoimg` WHERE classid=$cid AND delstate='' AND checkinfo=true ORDER BY orderid DESC limit 3";
                            $dosql->Execute($sql);
                            while($row = $dosql->GetArray())
                            {
                                    if($row['picurl'] != '') $picurl = $row['picurl'];
                                    else $picurl = 'templates/default/images/nofoundpic.gif';

                                    if($row['linkurl']=='' and $cfg_isreurl!='Y') $gourl = 'productdetail.php?cid='.$row['classid'].'&id='.$row['id'];
                                    else if($cfg_isreurl=='Y') $gourl = 'productdetail-'.$row['classid'].'-'.$row['id'].'.html';
                                    else $gourl = $row['linkurl'];
                        ?>
                        <li>
                            <a href=""><image src="<?php echo $row['picurl']; ?>" alt="<?php echo $row['title']; ?>" title="<?php echo $row['title']; ?>"/></a>
                            <p class="product_list_li_p"><a href="<?php echo $gourl; ?>"><?php echo $row['title']; ?></a></p>
                            <p class="contact_online"><a href="javascript:void(0);">在线咨询&nbsp;&nbsp;&nbsp;></a></p>
                        </li>
                        <?php
                            }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <?php require_once('footer.php'); ?>
</body>
</html>