<footer>
    <div class="footer_center">
        <p style="padding-top: 40px">
            <a href="<?php echo $cfg_isreurl=='Y'?'index.html':'index.php'; ?>">首页</a>|
            <a href="<?php echo $cfg_isreurl=='Y'?'product-1-1.html':'product.php'; ?>">产品中心</a>|
            <a href="<?php echo $cfg_isreurl=='Y'?'video-7-1.html':'video.php'; ?>">产品视频</a>|
            <a href="<?php echo $cfg_isreurl=='Y'?'industryapplication.html':'industryapplication.php'; ?>">行业应用</a>|
            <a href="<?php echo $cfg_isreurl=='Y'?'news-18-1.html':'news.php'; ?>">新闻中心</a>|
            <a href="<?php echo $cfg_isreurl=='Y'?'about-20.html':'about.php?cid=20'; ?>">关于企业</a>|
            <a href="<?php echo $cfg_isreurl=='Y'?'joinus.html':'joinus.php'; ?>">加入我们</a>|
            <a href="<?php echo $cfg_isreurl=='Y'?'linkus.html':'linkus.php'; ?>">联系我们</a>
        </p>
        <p style="padding-top: 20px">手机：:13052506373 （吴经理） 电话：021-50311307 电子邮箱：feylandsh@sina.com　传真：68362900</p>
        <p>地址：上海市南汇工业园区南芦公路106号</p>
        <p>费兰智能设备（上海）有限公司 Copyright 1998-2014   网站制作 嗅觉设计</p>
    </div>
</footer>
<script type="text/javascript">
//禁用右键、文本选择功能、复制按键
$(document).bind("contextmenu",function(){return false;});
$(document).bind("selectstart",function(){return false;});
$(document).keydown(function(){return key(arguments[0])});

//按键时提示警告
function key(e){
	var keynum;
	if(window.event){
		keynum = e.keyCode; // IE
	}else if(e.which){
		keynum = e.which; // Netscape/Firefox/Opera
	}
	if(keynum == 17){
		alert("禁止复制内容！");
		return false;
	}
}
</script>