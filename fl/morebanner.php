<div class="banner">
    <div class="banner_content">
        <div id="focus">
            <ul>
                <?php
                    $dosql->Execute("SELECT linkurl,picurl,title FROM `#@__infoimg` WHERE classid=27 AND delstate='' AND checkinfo=true ORDER BY orderid DESC LIMIT 0,5");
                    while($row = $dosql->GetArray())
                    {
                            if($row['linkurl'] != '')$gourl = $row['linkurl'];
                            else $gourl = 'javascript:;';
                ?>
                <li><a href="<?php echo $gourl; ?>" target="_blank"><img src="<?php echo $row['picurl']; ?>" alt="<?php echo $row['title']; ?>" /></a></li>
                <?php
                    }
                ?>
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
                    <p><img src="templates/cn/images/weixin_icon.gif"/></p>
                    <p>搜索费兰关注我们</p>
                </div>
            </div>
        </div>
    </div>
</div>