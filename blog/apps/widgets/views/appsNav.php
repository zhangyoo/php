<?php
use yii\helpers\Url;
?>
<nav id="menu">
    <ul class="clearfix">
        <li><a href="<?php echo Yii::$app->params['www']; ?>" class="current">首页</a></li>
        <li><a href="<?php echo Url::to(['/category/index','id'=>1]); ?>">发现生活</a>
            <ul>
                <li><a href="./about.html">About Us</a></li>
                <li class="last"><a href="./services.html">Our Services</a></li>
            </ul>
        </li>
        <li><a href="./portfolio2.html">学以致用</a>
            <ul>
                <li><a href="./portfolio2.html">Portfolio 2 col</a>
                    <ul>
                        <li><a href="./portfolio2.html">Portfolio 2 Columns</a></li>
                        <li class="last"><a href="./portfolio2ex.html">Portfolio 2 Columns Extended</a></li>
                    </ul>
                </li>
                <li><a href="./portfolio3.html">Portfolio 3 col</a>
                    <ul>
                        <li><a href="./portfolio3.html">Portfolio 3 Columns</a></li>
                        <li class="last"><a href="./portfolio3ex.html">Portfolio 3 Columns Extended</a></li>
                    </ul>
                </li>
                <li class="last"><a href="./portfolio4.html">Portfolio 4 col</a>
                    <ul>
                        <li><a href="./portfolio4.html">Portfolio 4 Columns</a></li>
                        <li class="last"><a href="./portfolio4ex.html">Portfolio 4 Columns Extended</a></li>
                    </ul>
                </li>
            </ul>
        </li>
        <li><a href="./blog.html">关于我</a>
            <ul>
                <li><a href="./blog.html">Blog</a></li>
                <li class="last"><a href="./blog-single.html">Blog Single</a></li>
            </ul>
        </li>
        <li><a href="./contact.html">留言</a></li>
    </ul>
</nav>