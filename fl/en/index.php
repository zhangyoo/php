<?php require_once(dirname(__FILE__).'/../include/config.inc.php'); ?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=9"> 
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<?php echo GetHeader(30); ?>
<link rel="stylesheet" type="text/css" href="../templates/en/css/style.css">
<script type="text/javascript" src="../templates/en/js/jquery.js"></script>
<script type="text/javascript" src="../templates/en/js/ext.js"></script>
</head>
<body>
    <?php require_once('header.php'); ?>
    <?php require_once('morebanner.php'); ?>
    <div class="main">
        <ul class="product_list">
            <?php
                $dosql->Execute("SELECT id,classname,picurl,linkurl,description FROM `#@__infoclass` WHERE parentid=31 AND checkinfo=true ORDER BY orderid asc LIMIT 0,5");
                while($row = $dosql->GetArray())
                {
                    //获取链接地址
                    if($row['linkurl']=='' and $cfg_isreurl!='Y')
                            $gourl = 'product.php?cid='.$row['id'];
                    else if($cfg_isreurl=='Y')
                            $gourl = 'product-'.$row['id'].'-1.html';
                    else
                            $gourl = $row['linkurl'];

                    //获取缩略图地址
                    if($row['picurl']!='')
                            $picurl = $row['picurl'];
                    else
                            $picurl = 'templates/default/images/nofoundpic.gif';
            ?>
            <li>
                <a href="<?php echo $gourl; ?>">
                    <img src="<?php echo $picurl; ?>" alt="<?php echo $row['classname']; ?>" title="<?php echo $row['classname']; ?>"/>
                </a>
                <p class="pro_title"><?php echo $row['classname']; ?></p>
                <p class="click_more_description"><?php echo mb_substr($row['description'],0,76,'utf-8'); ?><?php echo strlen($row['description'])>200?'......':''; ?></p>
                <p class="click_more_icon"><a href="<?php echo $gourl; ?>">了解更多</a></p>
            </li>
            <?php
                }
            ?>
        </ul>
        <div class="news_about_mv">
            <div class="news_list_index">
                <p class="news_list_title">新闻中心 News</p>
                <p class="news_list_decription">
                    <?php
                    $row = $dosql->GetOne("SELECT description FROM `#@__infoclass` WHERE id=48");
                    echo mb_substr($row['description'],0,50,'utf-8');
                    echo strlen($row['description'])>100?'......':'';
                    ?>
                </p>
                <ul>
                    <?php
                        $dosql->Execute("SELECT id,classid,linkurl,title FROM `#@__infolist` WHERE classid=48 AND delstate='' AND checkinfo=true ORDER BY orderid desc LIMIT 0,5");
                        while($row = $dosql->GetArray())
                        {
                            //获取链接地址
                            if($row['linkurl']=='' and $cfg_isreurl!='Y')
                                    $gourl = 'newsdetail.php?cid='.$row['classid'].'&id='.$row['id'];
                            else if($cfg_isreurl=='Y')
                                    $gourl = 'newsdetail-'.$row['classid'].'-'.$row['id'].'.html';
                            else
                                    $gourl = $row['linkurl'];
                    ?>
                    <li><a href="<?php echo $gourl; ?>"><?php echo $row['title']; ?></a></li>
                    <?php
                        }
                    ?>
                </ul>
                <p class="news_list_more_icon"><a href="<?php echo $cfg_isreurl!='Y'?'news.php':'news-18-1.html'; ?>">查看更多>></a></p>
            </div>
            <div class="aboutus_index">
                <p class="news_list_title">公司简介 Company</p>
                <p><img src="<?php
                    $picurl = InfoPic(50);
                    echo empty($picurl) ? '../templates/default/images/nofoundpic.gif':$picurl; 
                    
                    ?>" width="224" height="108"/></p>
                <p class="about_description"><?php
                    $infos = Info(50);
                    echo mb_substr($infos,0,100,'utf-8');
                    echo strlen($infos)>210?'......':''; 
                        ?></p>
            </div>
            <div class="mv_index">
                <img src="../templates/en/images/index_mv.gif" alt="" title=""/>
            </div>
        </div>
    </div>
    <div class="company_cooperation">
        <div class="company_cooperation_center">
        </div>
    </div>
    <div class="footer_banner">
        <div class="scrollcon">
            <div class="LeftBotton" onmousedown="ISL_GoUp()" onmouseup="ISL_StopUp()" onmouseout="ISL_StopUp()"></div>
            <div class="Cont" id="ISL_Cont">
                <div class="ScrCont">
                    <div id="List1">
                        <!-- 图片列表 begin -->
                        <?php
                            $dosql->Execute("SELECT picurl,title FROM `#@__infoimg` WHERE classid=54 AND delstate='' AND checkinfo=true ORDER BY orderid DESC");
                            while($row = $dosql->GetArray())
                            {
                        ?>
                        <div class="pic">
                            <a title="<?php echo $row['title']; ?>" href="javascript:void(0);"><img alt="" src="<?php echo $row['picurl']; ?>"  /></a>
                            <p><?php echo $row['title']; ?></p>
                        </div> 
                        <?php
                            }
                        ?>
                        <!-- 图片列表 end -->
                    </div>
                    <div id="List2"></div>
                </div>
            </div>
            <div class="RightBotton" onmousedown="ISL_GoDown()" onmouseup="ISL_StopDown()" onmouseout="ISL_StopDown()"></div>
	</div>
    </div>
    <?php require_once('footer.php'); ?>
<script type="text/javascript">
    //图片滚动列表
    var Speed = 0.01;//速度(毫秒)
    var Space = 5;//每次移动(px)
    var PageWidth = 182;//翻页宽度
    var fill = 0;//整体移位
    var MoveLock = false;
    var MoveTimeObj;
    var Comp = 0;
    var AutoPlayObj = null;
    GetObj("List2").innerHTML = GetObj("List1").innerHTML;
    GetObj('ISL_Cont').scrollLeft = fill;
    GetObj("ISL_Cont").onmouseover = function(){
            clearInterval(AutoPlayObj);
    }
    GetObj("ISL_Cont").onmouseout = function(){
            AutoPlay();
    }

    AutoPlay();

    function GetObj(objName){
            if(document.getElementById){
                    return eval('document.getElementById("'+objName+'")')
            }else{
                    return eval('document.all.'+objName)
            }
    }

    function AutoPlay(){ //自动滚动
            clearInterval(AutoPlayObj);
            AutoPlayObj = setInterval('ISL_GoDown();ISL_StopDown();',2000);//间隔时间
    }

    function ISL_GoUp(){ //上翻开始
            if(MoveLock) return;
            clearInterval(AutoPlayObj);
            MoveLock = true;
            MoveTimeObj = setInterval('ISL_ScrUp();',Speed);
    }

    function ISL_StopUp(){ //上翻停止
            clearInterval(MoveTimeObj);
            if(GetObj('ISL_Cont').scrollLeft % PageWidth - fill != 0){
                    Comp = fill - (GetObj('ISL_Cont').scrollLeft % PageWidth);
                    CompScr();
            }else{
                    MoveLock = false;
            }
            AutoPlay();
    }

    function ISL_ScrUp(){ //上翻动作
            if(GetObj('ISL_Cont').scrollLeft <= 0){
                    GetObj('ISL_Cont').scrollLeft = GetObj('ISL_Cont').scrollLeft + GetObj('List1').offsetWidth
            }
                    GetObj('ISL_Cont').scrollLeft -= Space ;
    }

    function ISL_GoDown(){ //下翻
            clearInterval(MoveTimeObj);
            if(MoveLock) return;
            clearInterval(AutoPlayObj);
            MoveLock = true;
            ISL_ScrDown();
            MoveTimeObj = setInterval('ISL_ScrDown()',Speed);
    }
    function ISL_StopDown(){ //下翻停止
            clearInterval(MoveTimeObj);
            if(GetObj('ISL_Cont').scrollLeft % PageWidth - fill != 0 ){
                    Comp = PageWidth - GetObj('ISL_Cont').scrollLeft % PageWidth + fill;
                    CompScr();
            }else{
                    MoveLock = false;
            }
            AutoPlay();
    }

    function ISL_ScrDown(){ //下翻动作
            if(GetObj('ISL_Cont').scrollLeft >= GetObj('List1').scrollWidth){
                    GetObj('ISL_Cont').scrollLeft = GetObj('ISL_Cont').scrollLeft - GetObj('List1').scrollWidth;
            }
            GetObj('ISL_Cont').scrollLeft += Space ;
    }

    function CompScr(){
            var num;
            if(Comp == 0){
                    MoveLock = false;return;
            }
            if(Comp < 0){ //上翻
                    if(Comp < -Space){
                            Comp += Space;
                            num = Space;
                    }else{
                            num = -Comp;
                            Comp = 0;
                    }
                    GetObj('ISL_Cont').scrollLeft -= num;
                    setTimeout('CompScr()',Speed);
            }else{ //下翻
                    if(Comp > Space){
                            Comp -= Space;
                            num = Space;
                    }else{
                            num = Comp;
                            Comp = 0;
                    }
                    GetObj('ISL_Cont').scrollLeft += num;
                    setTimeout('CompScr()',Speed);
            }
    }
</script>
</body>
</html>