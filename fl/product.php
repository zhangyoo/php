<!DOCTYPE html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=9"> 
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<title>产品中心列表页</title>
<link rel="stylesheet" type="text/css" href="../css/style.css">
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/ext.js"></script>
</head>
<body>
    <header>
        <div class="top">
            <div class="logo">
                <a href="" title="费兰智能设备（上海）有限公司"><img src="../images/logo.jpg" alt="费兰智能设备（上海）有限公司"/></a>
            </div>
            <ul class="nav">
                <li><a href="">首页</a></li>
                <li><a href="">产品中心</a></li>
                <li><a href="">产品视频</a></li>
                <li><a href="">行业应用</a></li>
                <li><a href="">新闻中心</a></li>
                <li><a href="">关于企业</a></li>
                <li><a href="">加入我们</a></li>
                <li><a href="">联系我们</a></li>
            </ul>
        </div>
    </header>
    <div class="banner">
        <div class="banner_content">
            <div id="focus">
                <ul>
                    <li><a href="" target="_blank"><img src="../images/banner_00.jpg" alt="QQ商城焦点图效果下载" /></a></li>
                    <li><a href="" target="_blank"><img src="../images/01.jpg" alt="QQ商城焦点图效果下载" /></a></li>
                    <li><a href="" target="_blank"><img src="../images/02.jpg" alt="QQ商城焦点图效果教程" /></a></li>
                    <li><a href="" target="_blank"><img src="../images/03.jpg" alt="jquery商城焦点图效果" /></a></li>
                    <li><a href="" target="_blank"><img src="../images/04.jpg" alt="jquery商城焦点图代码" /></a></li>
                    <li><a href="" target="_blank"><img src="../images/05.jpg" alt="jquery商城焦点图源码" /></a></li>
                </ul>
                <div class='preNext pre'></div><div class='preNext next'></div>
            </div>
            <script type="text/javascript">
            $(function() {
                    var sWidth = $("#focus").width(); //获取焦点图的宽度（显示面积）
                    var len = $("#focus ul li").length; //获取焦点图个数
                    var index = 0;
                    var picTimer;
                    //本例为左右滚动，即所有li元素都是在同一排向左浮动，所以这里需要计算出外围ul元素的宽度
                    $("#focus ul").css("width",sWidth * (len));

                    //上一页、下一页按钮透明度处理
                    $("#focus .preNext").css("opacity",0.2).hover(function() {
                            $(this).stop(true,false).animate({"opacity":"0.5"},300);
                    },function() {
                            $(this).stop(true,false).animate({"opacity":"0.2"},300);
                    });

                    //上一页按钮
                    $("#focus .pre").click(function() {
                            index -= 1;
                            if(index == -1) {index = len - 1;}
                            showPics(index);
                    });

                    //下一页按钮
                    $("#focus .next").click(function() {
                            index += 1;
                            if(index == len) {index = 0;}
                            showPics(index);
                    });

                    //鼠标滑上焦点图时停止自动播放，滑出时开始自动播放
                    $("#focus").hover(function() {
                            clearInterval(picTimer);
                    },function() {
                            picTimer = setInterval(function() {
                                    showPics(index);
                                    index++;
                                    if(index == len) {index = 0;}
                            },4000); //此4000代表自动播放的间隔，单位：毫秒
                    }).trigger("mouseleave");

                    //显示图片函数，根据接收的index值显示相应的内容
                    function showPics(index) { //普通切换
                            var nowLeft = -index*sWidth; //根据index值计算ul元素的left值
                            $("#focus ul").stop(true,false).animate({"left":nowLeft},300); //通过animate()调整ul元素滚动到计算出的position
                    }
            });
            </script>
            <div class="banner_hover">
                <div class="banner_bottom_bg"></div>
                <div class="banner_bottom_content">
                    <form action="" method="GET" class="banner_hover_left">
                        <input class="search_text" type="text" name="title"/>
                        <input class="search_submit" type="submit" value="提交" />
                    </form>
                    <div class="banner_hover_right">
                        <p>服务热线</p>
                        <p class="number_num">158-0090-2006</p>
                        <p>我们为您提供专业的搬运解决方案</p>
                        <p><img src="../images/weixin_icon.gif"/></p>
                        <p>搜索费兰关注我们</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="main">
        <div class="no_index">
            <div class="nav_left">
                <div class="nav_left_title">
                    <p><b>产品中心</b></p>
                    <p class="nav_left_title_sub">PRODUCT CENTER</p>
                </div>
                <ul>
                    <li><a href="">硬臂式机械手</a></li>
                    <li><a href="">硬臂式机械手</a></li>
                    <li><a href="">硬臂式机械手</a></li>
                    <li><a href="">硬臂式机械手</a></li>
                    <li><a href="">硬臂式机械手</a></li>
                </ul>
            </div>
            <div class="right_main">
                <div class="right_main_position">
                    当前位置：
                    <a href="">首页</a> > 
                    <a href="">产品中心</a> > 
                    <a href="">所有产品</a>
                </div>
                <div class="right_main_content">
                    <div class="right_main_content_description">
                        公司自成立以来一直致力于解决客户生产中的各种物料的搬运难题，针对复杂、多变的工艺要求提供相对应的、
                        完善的、专业的、安全而轻松的搬运和定位的解决方案。我们还能根据客户的预算提供最有
                        效和最具性价比的解决方案。 ”我们的设备搬运物料的重量从10kg到10T不等，能帮助工人完成省力，安全，高效，精准的搬运作业。
                    </div>
                    <ul class="product_list_ul">
                        <li>
                            <a href=""><image src="../images/product_07.jpg" alt="" title=""/></a>
                            <p class="product_list_li_p"><a href="">立柱式 机械手</a></p>
                            <p class="contact_online"><a href="javascript:void(0);">在线咨询&nbsp;&nbsp;&nbsp;></a></p>
                        </li>
                        <li>
                            <a href=""><image src="../images/product_07.jpg" alt="" title=""/></a>
                            <p class="product_list_li_p"><a href="">立柱式 机械手</a></p>
                            <p class="contact_online"><a href="javascript:void(0);">在线咨询&nbsp;&nbsp;&nbsp;></a></p>
                        </li>
                        <li>
                            <a href=""><image src="../images/product_07.jpg" alt="" title=""/></a>
                            <p class="product_list_li_p"><a href="">立柱式 机械手</a></p>
                            <p class="contact_online"><a href="javascript:void(0);">在线咨询&nbsp;&nbsp;&nbsp;></a></p>
                        </li>
                        <li>
                            <a href=""><image src="../images/product_07.jpg" alt="" title=""/></a>
                            <p class="product_list_li_p"><a href="">立柱式 机械手</a></p>
                            <p class="contact_online"><a href="javascript:void(0);">在线咨询&nbsp;&nbsp;&nbsp;></a></p>
                        </li>
                        <li>
                            <a href=""><image src="../images/product_07.jpg" alt="" title=""/></a>
                            <p class="product_list_li_p"><a href="">立柱式 机械手</a></p>
                            <p class="contact_online"><a href="javascript:void(0);">在线咨询&nbsp;&nbsp;&nbsp;></a></p>
                        </li>
                    </ul>
                </div>
                <div class="page_list">
                    <ul>
                        <li>第一页</li>
                        <li>第一页</li>
                        <li>第一页</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <div class="footer_center">
            <p style="padding-top: 40px">
                <a href="">首页</a>|
                <a href="">产品中心</a>|
                <a href="">产品视频</a>|
                <a href="">行业应用</a>|
                <a href="">新闻中心</a>|
                <a href="">关于企业</a>|
                <a href="">加入我们</a>|
                <a href="">联系我们</a>
            </p>
            <p style="padding-top: 20px">手机：:13052506373 （吴经理） 电话：021-50311307 电子邮箱：feylandsh@sina.com　传真：68362900</p>
            <p>地址：上海市南汇工业园区南芦公路106号</p>
            <p>费兰智能设备（上海）有限公司 Copyright 1998-2014   网站制作 嗅觉设计</p>
        </div>
    </footer>
</body>
</html>